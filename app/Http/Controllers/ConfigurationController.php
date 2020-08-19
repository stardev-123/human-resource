<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use File;
use Image;
use Swift_SmtpTransport;
use Swift_TransportException;

class ConfigurationController extends Controller
{
    use BasicController;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $localizations = array();
        foreach(config('localization') as $key => $value)
            $localizations[$key] = $value['localization'];
        $assets = ['tags'];
        $push_notification_modules = translateList('push_notification_module');
        return view('configuration.index',compact('localizations','assets','push_notification_modules'));
    }

    public function store(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $validation = Validator::make($request->all(),[
            'company_name' => 'sometimes|required',
            'contact_person' => 'sometimes|required',
            'email' => 'sometimes|email|required',
            'country_id' => 'sometimes|required',
            'timezone_id' => 'sometimes|required',
            'application_name' => 'sometimes|required',
            'leave_approval_level' => 'sometimes|required',
            'leave_no_of_level' => 'required_if:leave_approval_level,multiple|numeric',
            'leave_approval_level_designation' => 'required_if:leave_approval_level,designation',
            'expense_approval_level' => 'sometimes|required',
            'expense_no_of_level' => 'required_if:expense_approval_level,multiple|numeric',
            'expense_approval_level_designation' => 'required_if:expense_approval_level,designation',
            'pusher_app_id' => 'required_if:enable_push_notification,1',
            'pusher_key' => 'required_if:enable_push_notification,1',
            'pusher_secret' => 'required_if:enable_push_notification,1',
            'pusher_cluster' => 'required_if:enable_push_notification,1',
            'attendance_auto_lock_days' => 'required_if:enable_attendance_auto_lock,1|numeric|min:1',
            'list_user_criteria' => 'sometimes|required|array'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $input = $request->all();
        foreach($input as $key => $value){
            if(!in_array($key, config('constant.ignore_var'))){
                $value = (is_array($value)) ? implode(',',$value) : $value;
                $config = \App\Config::firstOrNew(['name' => $key]);
                if($value != config('config.hidden_value'))
                $config->value = isset($value) ? $value : null;
                $config->save();
            }
        }
        $sub_module = $request->input('config_type');
        $this->logActivity(['module' => 'configuration','sub_module' => $sub_module,'activity' => 'updated']);

        $response = ['message' => trans('messages.configuration').' '.trans('messages.updated'), 'status' => 'success']; 

        if($sub_module == 'general')
            $response = $this->getSetupGuide($response,'general_configuration');
        elseif($sub_module == 'system')
            $response = $this->getSetupGuide($response,'system_configuration');
        return response()->json($response);
    }

    public function mail(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $validation = Validator::make($request->all(),[
                'from_address' => 'required|email',
                'from_name' => 'required',
                'host' => 'required_if:driver,smtp',
                'port' => 'required_if:driver,smtp',
                'username' => 'required_if:driver,smtp',
                'password' => 'required_if:driver,smtp',
                'encryption' => 'in:ssl,tls|required_if:driver,smtp',
                'mailgun_host' => 'required_if:driver,mailgun',
                'mailgun_port' => 'required_if:driver,mailgun',
                'mailgun_username' => 'required_if:driver,mailgun',
                'mailgun_password' => 'required_if:driver,mailgun',
                'mailgun_domain' => 'required_if:driver,mailgun',
                'mailgun_secret' => 'required_if:driver,mailgun',
                'mandrill_secret' => 'required_if:driver,mandrill',
                ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        if($request->input('driver') == 'smtp'){
            $stmp = 0;
            try{
                    $transport = Swift_SmtpTransport::newInstance($request->input('host'), $request->input('port'), $request->input('encryption'));
                    $transport->setUsername($request->input('username'));
                    $transport->setPassword($request->input('password'));
                    $mailer = \Swift_Mailer::newInstance($transport);
                    $mailer->getTransport()->start();
                    $stmp =  1;
                } catch (Swift_TransportException $e) {
                    $stmp =  $e->getMessage();
                } 

            if($stmp != 1)
                return response()->json(['message' => $stmp, 'status' => 'error']);
        }
        $input = $request->all();
        foreach($input as $key => $value){
            if(!in_array($key, config('constant.ignore_var'))){
                $config = \App\Config::firstOrNew(['name' => $key]);
                if($value != config('config.hidden_value'))
                $config->value = $value;
                $config->save();
            }
        }

        $this->logActivity(['module' => 'configuration','sub_module' => 'mail','activity' => 'updated']);

        $response = ['message' => trans('messages.mail').' '.trans('messages.configuration').' '.trans('messages.updated'), 'status' => 'success']; 
        $response = $this->getSetupGuide($response,'mail');
        return response()->json($response);
    }

    public function upload(Request $request){
        if(isSecure())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $limit = $request->input('limit');
        $extension = $request->input('extension');

        if($request->has('max_file_size_upload') && $request->input('max_file_size_upload')*1024*1024 > getMaxFileUploadSize())
            return response()->json(['message' => trans('messages.system_max_file_size_upload',['attribute' => formatMemorySizeUnits(getMaxFileUploadSize())]), 'status' => 'error']);

        foreach($limit as $value)
            if(!is_numeric($value) || $value < 1)
                return response()->json(['message' => trans('messages.upload_limit_numeric_and_required'),'status' => 'error']);

        foreach($extension as $value)
            if($value == '' || $value == null)
                return response()->json(['message' => trans('messages.validation_required',['attribute' => 'extension']),'status' => 'error']);

        $config = \App\Config::firstOrNew(['name' => 'max_file_size_upload']);
        $config->value = $request->input('max_file_size_upload');
        $config->save();

        $upload = array();
        foreach(config('upload') as $key => $value)
            $upload[$key] = ['limit' => $limit[$key],'extension' => $extension[$key]];

        $filename = base_path().config('constant.path.upload');
        File::put($filename,var_export($upload, true));
        File::prepend($filename,'<?php return ');
        File::append($filename, ';');
        $this->logActivity(['module' => 'configuration','sub_module' => 'upload','activity' => 'updated']);

        return response()->json(['message' => trans('messages.upload').' '.trans('messages.configuration').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function sms(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $validation = Validator::make($request->all(),[
                'nexmo_api_key' => 'required',
                'nexmo_api_secret' => 'required',
                'nexmo_from_number' => 'required',
                'your_number' => 'required',
                ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        config(['nexmo.api_key' => $request->input('nexmo_api_key'),'nexmo.api_secret' => $request->input('nexmo_api_secret')]);
        try {
            $nexmo = app('Nexmo\Client');
            $nexmo->message()->send([
                'to' => $request->input('your_number'), 
                'from' => $request->input('nexmo_from_number'), 
                'text' => 'Test Message!'
                ]);
        } catch (\Nexmo\Client\Exception\Request $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error']);
        }
        
        $input = $request->all();
        foreach($input as $key => $value){
            if(!in_array($key, config('constant.ignore_var'))){
                $config = \App\Config::firstOrNew(['name' => $key]);
                if($value != config('config.hidden_value'))
                $config->value = $value;
                $config->save();
            }
        }
        $this->logActivity(['module' => 'configuration','sub_module' => 'sms','activity' => 'updated']);

        return response()->json(['message' => trans('messages.sms').' '.trans('messages.configuration').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function logo(Request $request){
        if(!getMode())
            return response()->json(['message' => trans('messages.disable_message'), 'status' => 'error']);

        $validation = Validator::make($request->all(),[
            'company_logo' => 'image'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $filename = uniqid();
        $config = \App\Config::firstOrNew(['name' => 'company_logo']);

        if ($request->hasFile('company_logo') && $request->input('remove_logo') != 1){
            if(File::exists(config('constant.upload_path.company_logo').config('config.company_logo')))
                File::delete(config('constant.upload_path.company_logo').config('config.company_logo'));
            $extension = $request->file('company_logo')->getClientOriginalExtension();
            $file = $request->file('company_logo')->move(config('constant.upload_path.company_logo'), $filename.".".$extension);
            $img = Image::make(config('constant.upload_path.company_logo').$filename.".".$extension);
            $img->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(config('constant.upload_path.company_logo').$filename.".".$extension);
            $config->value = $filename.".".$extension;
        } elseif($request->input('remove_logo') == 1){
            if(File::exists(config('constant.upload_path.company_logo').config('config.company_logo')))
                File::delete(config('constant.upload_path.company_logo').config('config.company_logo'));
            $config->value = null;
        }

        $config->save();

        $this->logActivity(['module' => 'configuration','sub_module' => 'logo','activity' => 'updated']);

        return response()->json(['message' => trans('messages.configuration').' '.trans('messages.updated'), 'status' => 'success','redirect' => '/configuration']);
    }
    
    public function menu(Request $request){

        $data = $request->all();
        foreach(\App\Menu::all() as $menu_item){
            $menu_item->order = $request->input($menu_item->name);
            $menu_item->visible = $request->has($menu_item->name.'-visible') ? 1 : 0;
            $menu_item->save();
        }

        $config_type = $request->input('config_type');
        
        $this->logActivity(['module' => 'configuration','sub_module' => 'menu','activity' => 'updated']);

        $response = ['status' => 'success','message' => trans('messages.menu').' '.trans('messages.configuration').' '.trans('messages.updated')];
        $response = $this->getSetupGuide($response,'menu');
        return response()->json($response);
    }

    public function setupGuide(Request $request){

        $setup = \App\Setup::orderBy('id','asc')->get();
        $setup_total = 0;
        $setup_completed = 0;
        foreach($setup as $value){
            $setup_total += config('setup.'.$value->module.'.weightage');
            if($value->completed)
                $setup_completed += config('setup.'.$value->module.'.weightage');
        }
        $setup_percentage = ($setup_total) ? round(($setup_completed/$setup_total) * 100) : 0;

        if($setup_percentage != 100 && !config('config.setup_guide'))
            return response()->json(['status' => 'success']);

        $config = \App\Config::firstOrNew(['name' => 'setup_guide']);
        $config->value = 0;
        $config->save();

        $this->logActivity(['module' => 'configuration','sub_module' => 'setup_guide','activity' => 'updated']);

        return response()->json(['message' => trans('messages.setup_guide_hide'), 'status' => 'success']);
    }

    public function api(Request $request){
        $user = \Auth::user();
        $user->auth_token = str_random(40);
        $user->save();
        return response()->json(['message' => 'API '.trans('messages.token').' '.trans('messages.updated'), 'status' => 'success','auth_token' => $user->auth_token]);
    }
}
