<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Validator;
use File;
use Image;
use Auth;
use Entrust;

class UserController extends Controller
{
    use BasicController;

    protected $form = 'user-form';

    public function index(){

        if(!Entrust::can('list-user'))
            return redirect('/home')->withErrors(trans('messages.permission_denied'));

        $data = array(
                    trans('messages.option'),
                    trans('messages.first').' '.trans('messages.name'),
                    trans('messages.last').' '.trans('messages.name'),
                    trans('messages.profile'),
                    trans('messages.username'),
                    trans('messages.email'),
                    trans('messages.designation'),
                    trans('messages.department'),
                    trans('messages.location'),
                    trans('messages.role'),
                    trans('messages.status')
                );

        $data = putCustomHeads($this->form, $data);

        $table_data['user-table'] = array(
                'source' => 'user',
                'title' => trans('messages.user').' '.trans('messages.list'),
                'id' => 'user-table',
                'form' => 'user-filter-form',
                'data' => $data
            );

        $roles = \App\Role::whereIsHidden(0)->get()->pluck('name','id')->all();
        $designations = childDesignation();
        $locations = childLocation();
        $assets = ['datatable','graph'];
        $menu = 'user';
        return view('user.index',compact('table_data','assets','menu','roles','designations','locations'));
    }

    public function lists(Request $request){
        if(!Entrust::can('list-user'))
            return;

        $query = getAccessibleUser();

        if($request->has('role_id'))
            $query->whereHas('roles',function($q) use ($request){
                $q->whereIn('role_id',$request->input('role_id'));
            });

        if($request->has('designation_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('designation_id',$request->input('designation_id'));
            });

        if($request->has('location_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('location_id',$request->input('location_id'));
            });

        if($request->has('status'))
            $query->whereIn('status',$request->input('status'));

        if($request->has('gender'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('gender',$request->input('gender'));
            });

        $users = $query->get();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        foreach($users as $user){
            $row = array();

            $setup_percentage = profileSetup($user);
            $profile_setup = $setup_percentage.'% <div class="progress progress-xs" style="margin-top:0px;">
                          <div class="progress-bar progress-bar-'.progressColor($setup_percentage).'" role="progressbar" aria-valuenow="'.$setup_percentage.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$setup_percentage.'%">
                          </div>
                        </div>';

            $user_role = '<ol>';
            foreach($user->roles as $role)
                $user_role .= '<li>'.toWord($role->name).'</li>';
            $user_role .= '</ol>';

            $user_status = '';
            if($user->status == 'active')
                $user_status = '<span class="label label-success">'.trans('messages.active').'</span>';
            elseif($user->status == 'pending_activation')
                $user_status = '<span class="label label-warning">'.trans('messages.pending_activation').'</span>';
            elseif($user->status == 'pending_approval')
                $user_status = '<span class="label label-info">'.trans('messages.pending_approval').'</span>';
            elseif($user->status == 'banned')
                $user_status = '<span class="label label-danger">'.trans('messages.banned').'</span>';

            $row = array(
                '<div class="btn-group btn-group-xs">'.
                '<a href="/user/'.$user->username.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
                (($user->status == 'active' && Entrust::can('change-user-status')) ? '<a href="#" class="btn btn-xs btn-default" data-ajax="1" data-extra="&user_id='.$user->id.'&status=ban" data-source="/change-user-status"> <i class="fa fa-ban" data-toggle="tooltip" title="'.trans('messages.ban').' '.trans('messages.user').'"></i></a>' : '').
                (($user->status == 'banned' && Entrust::can('change-user-status')) ? '<a href="#" class="btn btn-xs btn-default" data-ajax="1" data-extra="&user_id='.$user->id.'&status=active" data-source="/change-user-status"> <i class="fa fa-check" data-toggle="tooltip" title="'.trans('messages.activate').' '.trans('messages.user').'"></i></a>' : '').
                (($user->status == 'pending_approval' && Entrust::can('change-user-status')) ? '<a href="#" class="btn btn-xs btn-default" data-ajax="1" data-extra="&user_id='.$user->id.'&status=approve" data-source="/change-user-status"> <i class="fa fa-check" data-toggle="tooltip" title="'.trans('messages.approve').' '.trans('messages.user').'"></i></a>' : '').
                ((Entrust::can('login-as-user') && config('config.enable_login_as_user') && !session()->has('parent_login') && $user->id != \Auth::user()->id) ? '<a href="#" class="btn btn-xs btn-default" data-ajax="1" data-extra="&user_id='.$user->id.'" data-source="/login-as-user"> <i class="fa fa-sign-in" data-toggle="tooltip" title="'.trans('messages.login').' '.trans('messages.as').' '.$user->full_name.'"></i></a>' : '').
                (Entrust::can('delete-user') ? delete_form(['user.destroy',$user->id]) : '').
                '</div>',
                $user->Profile->first_name,
                $user->Profile->last_name,
                $profile_setup,
                $user->username.' '.(($user->is_hidden) ? '<span class="label label-danger">'.trans('messages.default').'</span>' : ''),
                $user->email,
                $user->designation_name,
                $user->department_name,
                $user->location_name,
                $user_role,
                $user_status
                );
            $id = $user->id;

            foreach($col_ids as $col_id)
                array_push($row,isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
            $rows[] = $row;
        }

        $roles = array();
        $designations = array();
        $locations = array();
        $departments = array();
        $statuses = array();
        $genders = array();
        foreach($users as $user){
            foreach($user->roles as $role){
                $roles[] = ucwords($role->name);
            }
            if($user->designation_name)
                $designations[] = $user->designation_name;
            if($user->location_name)
                $locations[] = $user->location_name;
            if($user->department_name)
                $departments[] = $user->department_name;
            if($user->status)
                $statuses[] = toWordTranslate($user->status);
            if($user->Profile->gender)
                $genders[] = toWordTranslate($user->Profile->gender);
        }

        $list['graph']['role'] = getPieCharData($roles,'role-wise-graph');
        $list['graph']['designation'] = getPieCharData($designations,'designation-wise-graph');
        $list['graph']['location'] = getPieCharData($locations,'location-wise-graph');
        $list['graph']['department'] = getPieCharData($departments,'department-wise-graph');
        $list['graph']['status'] = getPieCharData($statuses,'status-wise-graph');
        $list['graph']['gender'] = getPieCharData($genders,'gender-wise-graph');

        $list['aaData'] = $rows;
        return json_encode($list);
    }

    public function show($username){

        $user = User::whereUsername($username)->first();

        if(!$user || !$this->userAccessible($user))
            return redirect('/user')->withErrors(trans('messages.permission_denied'));

        $roles = \App\Role::whereIsHidden(0)->get()->pluck('name','id')->all();
        $all_user_roles = $user->Roles;
        $user_roles = array();
        foreach($all_user_roles as $user_role)
            $user_roles[] = $user_role->id;

        $designations = \App\Designation::whereIsHidden(0)->whereIn('id',getDesignation())->get()->pluck('designation_with_department','id')->all();
        $locations = \App\Location::whereIn('id',getLocation())->get()->pluck('name','id')->all();
        $contract_types = \App\ContractType::all()->pluck('name','id')->all();
        $templates = \App\Template::whereCategory('user')->pluck('name','id')->all();
        $user_relation = translateList('user_relation');
        $document_types = \App\DocumentType::all()->pluck('name','id')->all();
        $custom_social_field_values = getCustomFieldValues('user-social-form',$user->id);
        $custom_register_field_values = getCustomFieldValues('user-registration-form',$user->id);
        $leave_types = \App\LeaveType::all();
        $salary_heads = \App\SalaryHead::all()->pluck('name','id')->all();
        $education_levels = \App\EducationLevel::all()->pluck('name','id')->all();
        $qualification_languages = \App\QualificationLanguage::all()->pluck('name','id')->all();
        $qualification_skills = \App\QualificationSkill::all()->pluck('name','id')->all();
        $currencies = \App\Currency::all()->pluck('name','id')->all();
        $earning_salary_heads = \App\SalaryHead::whereType('earning')->get();
        $deduction_salary_heads = \App\SalaryHead::whereType('deduction')->get();

        $assets = ['summernote','timepicker'];
        $menu = 'user';
        return view('user.show',compact('user','roles','user_roles','templates','custom_social_field_values','custom_register_field_values','assets','menu','locations','designations','user_relation','document_types','contract_types','leave_types','salary_heads','education_levels','qualification_languages','qualification_skills','currencies','earning_salary_heads','deduction_salary_heads'));
    }

    public function profileSetup($id){
        $user_id = ($id) ? : \Auth::user()->id;
        $user = User::find($user_id);

        if(!$user || !$this->userAccessible($user->id,1))
            return view('global.error',['message' => trans('messages.permission_denied')]);

        $setup_percentage = profileSetup($user);
        $setup = profileSetup($user,'data');
        return view('user.profile_setup',compact('user','setup','setup_percentage'));
    }

    public function profile($username = null){
        $username = ($username) ? : \Auth::user()->username;
        $user = User::whereUsername($username)->first();

        if(!$user || !$this->userAccessible($user,1))
            return redirect('/profile');

        //$useremail = \App\User::with('profiles')->where('id', $id)->first();
        $useremail= User::findOrFail($user->id);
        $useremail->email = $user->email;
        $user_relation = translateList('user_relation');
        $document_types = \App\DocumentType::all()->pluck('name','id')->all();
        $custom_social_field_values = getCustomFieldValues('user-social-form',$user->id);
        $custom_register_field_values = getCustomFieldValues('user-registration-form',$user->id);
        $education_levels = \App\EducationLevel::all()->pluck('name','id')->all();
        $qualification_languages = \App\QualificationLanguage::all()->pluck('name','id')->all();
        $qualification_skills = \App\QualificationSkill::all()->pluck('name','id')->all();
        $useremail->save();
        //$emailpair = $request->input('email');
        	//mysqli_query('UPDATE `users` SET `email` = '.$emailpair.' WHERE `users`.`id` = '.$id.'' ,'SET FOREIGN_KEY_CHECKS = 0');

        return view('user.profile',compact('useremail','user','user_relation','document_types','custom_social_field_values','custom_register_field_values','education_levels','qualification_languages','qualification_skills'));
    }

    public function loginAsUser(Request $request){
        $user_id = $request->input('user_id');
        if(!Entrust::can('login-as-user') || !config('config.enable_login_as_user') || $user_id == \Auth::user()->id || session()->has('parent_login'))
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        $user = User::find($user_id);

        if(!$user || !$this->userAccessible($user))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $parent_user_id = \Auth::user()->id;
        \Auth::logout();
        session(['parent_login' => $parent_user_id]);
        \Auth::login($user);
        $this->logActivity(['module' => 'login','activity' => 'logged_in']);

        return response()->json(['message' => trans('messages.login_redirect_message'),'status' => 'success','redirect' => '/home']);
    }

    public function loginReturn(Request $request){
        if(!session('parent_login'))
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        $return_user = User::find(session('parent_login'));

        if(!$return_user)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        \Auth::logout();
        session(['parent_login' => $return_user->id]);
        \Auth::login($return_user);
        session()->forget('parent_login');
        $this->logActivity(['module' => 'login','activity' => 'logged_in']);

        return response()->json(['message' => trans('messages.login_redirect_message'),'status' => 'success','redirect' => '/home']);
    }

    public function detail(Request $request){
        $user = User::find($request->input('user_id'));

        if(!$user)
            return;

        $user_employment = getEmployment(date('Y-m-d'),$user->id);
        $setup_percentage = profileSetup($user);
        return view('user.detail',compact('user','user_employment','setup_percentage'))->render();
    }

    public function avatar(Request $request, $id){

        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $user = \App\User::find($id);

        if(!$this->userAccessible($user,1))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $profile = $user->Profile;

        $validation = Validator::make($request->all(),[
            'avatar' => 'image'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $filename = uniqid();

        if ($request->hasFile('avatar') && $request->input('remove_avatar') != 1){
            if(File::exists(config('constant.upload_path.avatar').config('config.avatar')))
                File::delete(config('constant.upload_path.avatar').config('config.avatar'));
            $extension = $request->file('avatar')->getClientOriginalExtension();
            $file = $request->file('avatar')->move(config('constant.upload_path.avatar'), $filename.".".$extension);
            $img = Image::make(config('constant.upload_path.avatar').$filename.".".$extension);
            $img->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(config('constant.upload_path.avatar').$filename.".".$extension);
            $profile->avatar = $filename.".".$extension;
        } elseif($request->input('remove_avatar') == 1){
            if(File::exists(config('constant.upload_path.avatar').config('config.avatar')))
                File::delete(config('constant.upload_path.avatar').config('config.avatar'));
            $profile->avatar = null;
        }

        $profile->save();

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'sub_module' => 'avatar' ,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.profile').' '.trans('messages.updated'), 'status' => 'success','redirect' => '/user/'.$user->username]);
    }

    public function profileUpdate(Request $request, $id){

        $user = \App\User::find($id);

        if(!$this->userAccessible($user) || !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $profile = $user->Profile;

        $validation = Validator::make($request->all(),[
            'employee_code' => 'required|unique:profiles,employee_code,'.$profile->id
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $validation = validateCustomField('user-registration-form',$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $profile->fill($request->all());
        $profile->employee_code = $request->input('employee_code');
        $useremail= User::findOrFail($id);
        $useremail->email = $request->input('email');
        $profile->date_of_birth = ($request->input('date_of_birth')) ? : null;
        $profile->date_of_anniversary = ($request->input('date_of_anniversary')) ? : null;
        $profile->save();
        $useremail->save();

        $data = $request->all();
        updateCustomField('user-registration-form',$user->id, $data);

        if($request->has('role_id') && !$user->hasRole(DEFAULT_ROLE)){
            $user->roles()->sync($request->input('role_id'));
        }
        $this->logActivity(['module' => 'user','module_id' => $user->id, 'sub_module' => 'profile', 'activity' => 'updated']);

        return response()->json(['message' => trans('messages.profile').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function socialUpdate(Request $request, $id){

        $user = \App\User::find($id);

        if(!$this->userAccessible($user,1))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        if($user->id != \Auth::user()->id && !Entrust::can('edit-user'))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        $validation = validateCustomField('user-social-form',$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $profile = $user->Profile;

        $data = $request->all();
        $profile->fill($data);
        $profile->save();
        updateCustomField('user-social-form',$user->id, $data);

        $this->logActivity(['module' => 'user','module_id' => $user->id, 'sub_module' => 'social_field', 'activity' => 'updated']);

        return response()->json(['message' => trans('messages.profile').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function forceChangePassword($user_id,Request $request){

        $user = \App\User::find($user_id);

        if(!$this->userAccessible($user))
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);

        if(!Entrust::can('reset-user-password'))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user_id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error']);

        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $credentials = $request->only(
                'new_password', 'new_password_confirmation'
        );

        $validation = Validator::make($request->all(),[
            'new_password' => 'required|confirmed|min:6',
            'new_password_confirmation' => 'required|same:new_password'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'force_password_changed']);
        $user->password = bcrypt($credentials['new_password']);
        $user->save();

        return response()->json(['message' => trans('messages.password').' '.trans('messages.changed'), 'status' => 'success']);
    }

    public function email(Request $request){
        if(!Entrust::can('email-user') || !config('config.enable_email_template') || !$this->userAccessible($user))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        $validation = Validator::make($request->all(),[
            'subject' => 'required',
            'body' => 'required'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = User::find($id);
        $mail['email'] = $user->email;
        $mail['subject'] = $request->input('subject');
        $body = $request->input('body');

        \Mail::send('emails.email', compact('body'), function($message) use ($mail){
            $message->to($mail['email'])->subject($mail['subject']);
        });
        $this->logEmail(array('to' => $mail['email'],'subject' => $mail['subject'],'body' => $body));

        $this->logActivity(['module' => 'user','sub_module' => 'email','module_id' => $user->id,'activity' => 'sent']);

        return response()->json(['message' => trans('messages.mail').' '.trans('messages.sent'), 'status' => 'success']);
    }

    public function changeStatus(Request $request){

        $user_id = $request->input('user_id');
        $status = $request->input('status');

        $user = \App\User::find($user_id);
        if(!$user)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        if(!Entrust::can('change-user-status') || $user->hasRole(DEFAULT_ROLE))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if(!$this->userAccessible($user))
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($status == 'ban' && $user->status != 'active')
            return redirect('/user')->withErrors(trans('messages.invalid_link'));
        elseif($status == 'approve' && $user->status != 'pending_approval')
            return redirect('/user')->withErrors(trans('messages.invalid_link'));
        elseif($status == 'active' && $user->status != 'banned')
            return redirect('/user')->withErrors(trans('messages.invalid_link'));

        if($status == 'ban')
            $user->status = 'banned';
        elseif($status == 'approve' || $status == 'active')
            $user->status  = 'active';

        $user->save();
        $user->notify(new \App\Notifications\UserStatusChange($user));

        if($request->has('ajax_submit'))
            return response()->json(['message' => trans('messages.status').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function changePassword(){
        return view('auth.change_password');
    }

    public function doChangePassword(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $credentials = $request->only(
                'new_password', 'new_password_confirmation'
        );
        $validation_messages = [
            'password.regex' => trans('messages.password_alphanumeric'),
        ];

        $validation = Validator::make($request->all(),[
            'old_password' => 'required|valid_password',
            'new_password' => 'required|confirmed|different:old_password|min:6|'.passwordRule(),
            'new_password_confirmation' => 'required|different:old_password|same:new_password'
        ],$validation_messages);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $user = \Auth::user();

        $user->password = bcrypt($credentials['new_password']);
        $user->save();
        return response()->json(['message' => trans('messages.password').' '.trans('messages.changed'), 'status' => 'success']);
    }

    public function destroy(User $user,Request $request){

        if(!Entrust::can('delete-user') || !$this->userAccessible($user) || $user->is_hidden)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        if($user->id == \Auth::user()->id)
            return response()->json(['message' => trans('messages.permission_denied'), 'status' => 'error']);

        deleteCustomField($this->form, $user->id);
        $this->logActivity(['module' => 'user','module_id' => $user->id,'activity' => 'deleted']);
        $user->delete();

        return response()->json(['message' => trans('messages.user').' '.trans('messages.deleted'), 'status' => 'success']);
    }

    public function employmentReport(){
        $data = array(
                    trans('messages.option'),
                    trans('messages.first').' '.trans('messages.name'),
                    trans('messages.last').' '.trans('messages.name'),
                    trans('messages.email'),
                    trans('messages.designation'),
                    trans('messages.department'),
                    trans('messages.location'),
                    trans('messages.role'),
                    trans('messages.date_of').' '.trans('messages.joining'),
                    trans('messages.date_of').' '.trans('messages.leaving'),
                    trans('messages.salary').' '.trans('messages.type'),
                    trans('messages.net').' '.trans('messages.salary')
                );

        $table_data['employment-report-table'] = array(
                'source' => 'employment-report',
                'title' => toWordTranslate('employment-report'),
                'id' => 'employment-report-table',
                'form' => 'employment-report-filter-form',
                'data' => $data
            );

        $roles = \App\Role::whereIsHidden(0)->get()->pluck('name','id')->all();
        $designations = childDesignation();
        $locations = childLocation();
        $assets = ['datatable','graph'];
        $menu = 'user';
        $current_report = 'employment-report';
        return view('user.employment_report',compact('table_data','assets','menu','designations','locations','roles','current_report'));
    }

    public function employmentReportLists(Request $request){
        if(!Entrust::can('list-user'))
            return;

        $query = getAccessibleUser();

        if($request->has('role_id'))
            $query->whereHas('roles',function($q) use ($request){
                $q->whereIn('role_id',$request->input('role_id'));
            });

        if($request->has('designation_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('designation_id',$request->input('designation_id'));
            });

        if($request->has('location_id'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('location_id',$request->input('location_id'));
            });

        if($request->has('status'))
            $query->whereIn('status',$request->input('status'));

        if($request->has('gender'))
            $query->whereHas('profile',function($q) use ($request){
                $q->whereIn('gender',$request->input('gender'));
            });

        $users = $query->get()->pluck('id')->all();

        if($request->input('type') == 'new')
            $field = 'date_of_joining';
        else
            $field = 'date_of_leaving';

        $user_employments = \App\UserEmployment::whereIn('user_id',$users)->where($field,'>=',$request->input('from_date'))->where($field,'<=',$request->input('to_date'))->get();

        $rows = array();
        foreach($user_employments as $user_employment){
            $row = array();

            $user = $user_employment->User;

            $salary = getUserNetSalary($request->input('from_date'),$user->id);

            $user_role = '<ol>';
            foreach($user->roles as $role)
                $user_role .= '<li>'.toWord($role->name).'</li>';
            $user_role .= '</ol>';

            $row = array(
                '<div class="btn-group btn-group-xs">'.
                '<a href="/user/'.$user->username.'" class="btn btn-xs btn-default"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
                '</div>',
                $user->Profile->first_name,
                $user->Profile->last_name,
                $user->email,
                $user->designation_name,
                $user->department_name,
                $user->location_name,
                $user_role,
                showDate($user_employment->date_of_joining),
                showDate($user_employment->date_of_leaving),
                $salary['salary_type'],
                $salary['net_salary_with_currency']
                );
            $rows[] = $row;
        }
        $list['aaData'] = $rows;
        return json_encode($list);
    }

    public function employmentReportGraph(Request $request){

        $users = User::all();
        $y_data = array();
        $x_data = array();
        $currency_legend = array();
        $total_net_salary = array();
        $all_user_employments = \App\UserEmployment::all();
        $currencies = \App\Currency::all();
        foreach($currencies as $currency)
            $currency_legend[] = $currency->detail;

        for($i=0;$i<12;$i++){
            $month_year = date('Y-m', strtotime(date('Y-m-d').' - '.$i.' months'));
            $month_year_name = date('M-Y',strtotime($month_year.'-01'));
            $first_date = date('Y-m-d',strtotime($month_year.'-01'));
            $last_date = date('Y-m-t',strtotime($month_year.'-01'));
            $total_employment = 0;
            $hourly_employee = 0;
            $monthly_employee = 0;

            $new_employment = $all_user_employments->filter(function ($item) use ($first_date,$last_date) {
                return (data_get($item, 'date_of_joining') >= $first_date) && (data_get($item, 'date_of_joining') < $last_date);
            })->count();

            $end_employment = $all_user_employments->filter(function ($item) use ($first_date,$last_date) {
                return (data_get($item, 'date_of_leaving') >= $first_date) && (data_get($item, 'date_of_leaving') < $last_date);
            })->count();

            foreach($currencies as $currency)
                $net_salary[$currency->id] = 0;

            foreach($users as $user){
                $user_employment = getEmployment($last_date,$user->id);
                if($user_employment){
                    $total_employment++;

                    $user_salary = getUserNetSalary($last_date,$user->id);
                    if($user_salary['salary_type'] == 'hourly')
                        $hourly_employee++;
                    elseif($user_salary['salary_type'] == 'monthly'){
                        $monthly_employee++;
                        $net_salary[$user_salary['currency']] += currency($user_salary['net_salary'],0);
                    }
                }
            }

            foreach($net_salary as $key => $value)
                $total_net_salary[$key][] = $value;

            $y_data[] = $month_year_name;
            $new_employment_data[] = $new_employment;
            $end_employment_data[] = $end_employment;
            $total_employment_data[] = $total_employment;
            $total_hourly_employee[] = $hourly_employee;
            $total_monthly_employee[] = $monthly_employee;
        }

        $net_salary_data = array();
        foreach($currencies as $currency){
            $net_salary_data[] = array(
                'name' => $currency->detail,
                'type' => 'bar',
                'data' => $total_net_salary[$currency->id]
                );
        }

        $extra_height = 100;
        $employment_height = 75*count($y_data) + $extra_height;
        $salary_height = 50*count($y_data) + $extra_height;
        $monthly_salary_height = 25*count($currency_legend)*count($y_data) + $extra_height;

        $graph_data = array(
            'employment' => array(
                'y_data' => $y_data,
                'height' => $employment_height,
                'text' => toWordTranslate('employment-statistics'),
                'legend' => [toWordTranslate('new-employment'),toWordTranslate('end-employment'),toWordTranslate('total-employment')],
                'x_data' => [
                        array(
                            'name' => toWordTranslate('new-employment'),
                            'type' => 'bar',
                            'data' => $new_employment_data
                        ),
                        array(
                            'name' => toWordTranslate('end-employment'),
                            'type' => 'bar',
                            'data' => $end_employment_data
                        ),
                        array(
                            'name' => toWordTranslate('total-employment'),
                            'type' => 'bar',
                            'data' => $total_employment_data
                        )
                    ]
                ),
            'salary' => array(
                'y_data' => $y_data,
                'height' => $salary_height,
                'text' => toWordTranslate('salary-statistics'),
                'legend' => [toWordTranslate('hourly-employment'),toWordTranslate('monthly-employment')],
                'x_data' => [
                        array(
                            'name' => toWordTranslate('hourly-employment'),
                            'type' => 'bar',
                            'data' => $total_hourly_employee
                        ),
                        array(
                            'name' => toWordTranslate('monthly-employment'),
                            'type' => 'bar',
                            'data' => $total_monthly_employee
                        )
                    ]
                ),
            'monthly_salary' => array(
                'y_data' => $y_data,
                'height' => $monthly_salary_height,
                'text' => toWordTranslate('monthly-salary-statistics'),
                'legend' => $currency_legend,
                'x_data' => $net_salary_data
                )
            );

        return json_encode($graph_data);
    }
}
