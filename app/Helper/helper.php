<?php
function delete_form($value,$param = array()){
    $name = (array_key_exists('name', $param)) ? $param['name'] : randomString(10);
    $form_option = ['method' => 'DELETE',
        'route' => $value,
        'class' => 'form-inline',
        'id' => $name.'_'.$value[1]
        ];

    $label = (array_key_exists('label', $param)) ? $param['label'] : '';
    $size = (array_key_exists('size', $param)) ? $param['size'] : 'xs';
    if(array_key_exists('redirect', $param))
        $form_option['data-redirect'] = $param['redirect'];
    if(array_key_exists('ajax', $param))
        $form_option['data-submit'] = $param['ajax'];
    if(array_key_exists('table-refresh', $param))
        $form_option['data-table-refresh'] = $param['table-refresh'];
    if(array_key_exists('refresh-content', $param))
        $form_option['data-refresh'] = $param['refresh-content'];

    $alert_message = trans('messages.action_confirm_message');
    if(array_key_exists('alert-message', $param))
        $alert_message = $param['alert-message'];

    $form = Form::open($form_option);
    $form .= Html::decode(Form::button('<i class="fa fa-trash-o"></i> '.$label,['data-toggle' => 'tooltip', 'title' => trans('messages.delete'), 'class' => 'btn btn-danger btn-'.$size, 'data-submit-alert-message' => 'Yes', 'type' => 'submit','data-alert-message' => $alert_message]));
    return $form .= Form::close();
}

function getNotificationMusicName($name){
    $name = str_replace('notification-music\\', '', $name);
    $name = str_replace('notification-music/', '', $name);
    $name = str_replace('.mp3', '', $name);
    return toWord($name);
}

function getAllNotificationMusicList(){
    $list = array();
    foreach(File::allFiles('notification-music') as $file){
        $name = str_replace('notification-music\\', '', $file);
        $name = str_replace('notification-music/', '', $name);
        $list[$name] = getNotificationMusicName($file);
    }
    return $list;
}

function getYearDiff($date){
    return date('Y') - date('Y',strtotime($date));
}

function getAge($birthday){
    if($birthday == '' || $birthday == null)
        return 0;
    $birthday = strtotime($birthday);
    $age = strtotime(date('Y-m-d')) - $birthday;
    return floor($age / 60 / 60 / 24 / 365);
}

function getUuid(){
    return \Uuid::generate();
}

function progressColor($progress){
    if($progress <= 20)
        return 'danger';
    elseif($progress>20  && $progress <=50)
        return 'warning';
    elseif($progress>50  && $progress <=99)
        return 'info';
    else
        return 'success';
}

function attendanceReport(){
    return ['daily-attendance','date-wise-attendance','user-wise-summary-attendance','date-wise-summary-attendance'];
}

function shiftReport(){
    return ['daily-shift','date-wise-shift'];
}

function userReport(){
    return ['employment-report'];
}

function leaveReport(){
    return ['leave-balance-report','date-wise-leave-report'];
}

function jobApplicationStatusLable($status){
    if($status == 'applied')
        return '<span class="label label-default">'.trans('messages.applied').'</span>';
    elseif($status == 'pending')
        return '<span class="label label-info">'.trans('messages.pending').'</span>';
    elseif($status == 'disqualified')
        return '<span class="label label-danger">'.trans('messages.disqualified').'</span>';
    elseif($status == 'shortlisted')
        return '<span class="label label-success">'.trans('messages.shortlisted').'</span>';
    elseif($status == 'interviewing')
        return '<span class="label label-warning">'.trans('messages.interviewing').'</span>';
    elseif($status == 'made_offer')
        return '<span class="label label-success">'.trans('messages.made_offer').'</span>';
    elseif($status == 'recruited')
        return '<span class="label label-success">'.trans('messages.recruited').'</span>';
    elseif($status == 'offer_rejected')
        return '<span class="label label-danger">'.trans('messages.offer_rejected').'</span>';
    else
        return;
}

function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function currency($amount, $symbol = 0, $currency_id = null){

    if($currency_id)
        $currency = \App\Currency::find($currency_id);
    else
        $currency = \App\Currency::whereIsDefault(1)->first();

    $amount = round($amount,2);
    if(!$symbol || !$currency)
        return $amount;

    if($currency->position == 'suffix')
        return $amount.' '.$currency->symbol;
    else
        return $currency->symbol.' '.$amount;
}

function getTaskStatus($task,$label_size = ''){
    if($task->progress < 100)
        $status = '<span class="label label-'.progressColor($task->progress).' '.$label_size.'">'.trans('messages.pending').'</span>';
    elseif($task->progress == 100)
        $status = '<span class="label label-success'.' '.$label_size.'">'.trans('messages.complete').'</span>';

    if($task->due_date < date('Y-m-d') && $task->progress < 100){
        $by_days = dateDiff($task->due_date,date('Y-m-d')) - 1;
        $status = '<span class="label label-danger'.' '.$label_size.'">'.trans('messages.overdue').' by '.$by_days.' '.trans('messages.days').'</span>';
    }
    return $status;
}

function getTicketStatus($status){
    if($status == 'open' || $status == '')
        return '<span class="label label-danger">'.trans('messages.open').'</span>';
    elseif($status == 'pending')
        return '<span class="label label-info">'.trans('messages.pending').'</span>';
    elseif($status == 'close')
        return '<span class="label label-success">'.trans('messages.close').'</span>';
}

function getPieCharData($entity,$name){
    $data = array();
    $legend = array();
    $entity = array_count_values($entity);
    foreach($entity as $key => $value){
        $data[] = ['value' => $value,'name' => $key];
        $legend[] = $key;
    }
    return [
        'data' => $data,
        'legend' => $legend,
        'name' => toWordTranslate($name),
        'title_text' => toWordTranslate($name)
    ];
}

function dateDiff($date1,$date2){
    if($date2 > $date1)
        return date_diff(date_create($date1),date_create($date2))->days + 1;
    else
        return date_diff(date_create($date2),date_create($date1))->days + 1;
}

function getDateInArray($date1,$date2,$show_date = 0){
    $days = array();
    $start_date = ($date1 > $date2) ? $date2 : $date1;
    $end_date = ($date1 > $date2) ? $date1 : $date2;
    while($start_date <= $end_date){
        $days[] = ($show_date) ? showDate($start_date) : $start_date;
        $start_date = date('Y-m-d',strtotime($start_date.' +1 days'));
    }

    return $days;
}

function getLocation($user = null, $self = 0){
    $user = ($user) ? $user : \Auth::user();

    if($user->can('manage-all-location'))
        return \App\Location::all()->pluck('id')->all();
    elseif($user->can('manage-subordinate-location')){
        $childs = childLocation($user->Profile->location_id,1);
        if($self)
            array_push($childs, $user->Profile->location_id);
        return $childs;
    } else
        return ($self) ? \App\Location::whereId($user->Profile->location_id)->pluck('id')->all() : [];
}

function getDesignation($user = null, $self = 0){
    $user = ($user) ? $user : \Auth::user();

    if($user->is_hidden)
        return \App\Designation::all()->pluck('id')->all();
    elseif($user->can('manage-all-designation'))
        return \App\Designation::whereIsHidden(0)->get()->pluck('id')->all();
    elseif($user->can('manage-subordinate-designation')){
        $childs = childDesignation($user->Profile->designation_id,1);
        if($self)
            array_push($childs, $user->Profile->designation_id);
        return $childs;
    } else
        return ($self) ? \App\Designation::whereId($user->Profile->designation_id)->pluck('id')->all() : [];
}

function getAccessibleUser($user_id = null,$self = 0){
    if(!$user_id)
        $user_id = \Auth::user()->id;

    $user = \App\User::find($user_id);

    if(defaultRole()){
        $query = \App\User::whereNotNull('id');
        return $query;
    }

    $query = \App\User::with('profile')->whereHas('profile',function($qry) use($user,$self){
        $qry->whereIn('designation_id',getDesignation($user,$self));
    })->whereIn('status',explode(',',config('config.list_user_criteria')));

    $location_users = array();
    if(!config('config.location_level')){
        $location_users = \App\User::with('profile')->whereHas('profile',function($qry) use($user){
            $qry->whereLocationId($user->Profile->location_id);
        })->get()->pluck('id')->all();
        $query->whereIn('id',$location_users);
    }
    return $query;
}

function getAccessibleUserId($user_id = null, $self = 0){
    $query = getAccessibleUser($user_id,$self);
    return $query->get()->pluck('id')->all();
}

function getAccessibleUserList($user_id = null, $self = 0, $type = 'name_with_designation_and_location'){

    if(!in_array($type,['name_with_designation_and_location','name_with_designation','name_with_location','full_name']))
        $type = 'full_name';

    $query = getAccessibleUser($user_id,$self);
    return $query->get()->pluck($type,'id')->all();
}

function getUserDesignation($date = '', $user_id = '',$value = 'id'){
    if($user_id == '')
        $user_id = Auth::user()->id;
    if($date == '')
        $date = date('Y-m-d');

    $user_designation = \App\UserDesignation::whereUserId($user_id)->where('from_date','<=',$date)->where('to_date','>=',$date)->first();

    if(!$user_designation)
        $user_designation = \App\UserDesignation::whereUserId($user_id)->where('from_date','<=',$date)->whereNull('to_date')->orderBy('from_date','desc')->first();

    if($user_designation)
        return ($value == 'name') ? $user_designation->Designation->name : $user_designation->designation_id;
    else
        return null;
}

function getUserLocation($date = '', $user_id = '',$value = 'id'){
    if($user_id == '')
        $user_id = Auth::user()->id;
    if($date == '')
        $date = date('Y-m-d');

    $user_location = \App\UserLocation::whereUserId($user_id)->where('from_date','<=',$date)->where('to_date','>=',$date)->first();

    if(!$user_location)
        $user_location = \App\UserLocation::whereUserId($user_id)->where('from_date','<=',$date)->whereNull('to_date')->orderBy('from_date','desc')->first();

    if($user_location)
        return ($value == 'name') ? $user_location->Location->name : $user_location->location_id;
    else
        return;
}

function updateReadNotification($notification){
    $notification_user = explode(',',$notification->user);
    $notification_user = array_diff($notification_user, array(\Auth::user()->id));
    $notification->user = implode(',', $notification_user);
    if($notification->user_read != ''){
        $notification_user_read = explode(',',$notification->user_read);
        array_push($notification_user_read, \Auth::user()->id);
    }
    else
        $notification_user_read = array(\Auth::user()->id);
    $notification->user_read = implode(',', array_unique($notification_user_read));
    $notification->save();
}

function setupGuide(){

    $url = \Request::path();
    $con = is_numeric(strpos($url, 'configuration'));

    $setup = \App\Setup::orderBy('id','asc')->get();
    $setup_total = 0;
    $setup_completed = 0;
    foreach($setup as $value){
        $setup_total += config('setup.'.$value->module.'.weightage');
        if($value->completed)
            $setup_completed += config('setup.'.$value->module.'.weightage');
    }
    $setup_percentage = ($setup_total) ? round(($setup_completed/$setup_total) * 100) : 0;

    return view('global.setup_guide',compact('setup_percentage','setup','con'))->render();
}

function getCompanyLogo(){
    if(File::exists(config('constant.upload_path.company_logo').config('config.company_logo')) && config('config.company_logo'))
        return '<img src="'.url('/'.config('constant.upload_path.company_logo').config('config.company_logo')).'">';
    else
        return;
}

function menuAvailable($menus,$menu){
    $menu_item = $menus->where('name',$menu)->first();
    return $menu_item->visible;
}

function menuAttr($menus,$menu){
    $menu_item = $menus->where('name',$menu)->first();

    if($menu_item)
        return 'data-position="'.(($menu_item->order == null) ? $menu_item->id : $menu_item->order).'" data-visible="'.$menu_item->visible.'"';
    else
        return '';
}

function setEncryptionKey(){
    if(!env('APP_KEY') || strlen(env('APP_KEY')) != 32)
        envu(['APP_KEY' => randomString(32)]);
}

function backupDatabase(){
    \Storage::makeDirectory('backup');
    try {
        $db_export = \App\Helper\Shuttle_Dumper::create(array(
            'host' => config('database.connections.primary.host'),
            'username' => config('database.connections.primary.username'),
            'password' => config('database.connections.primary.password'),
            'db_name' => config('database.connections.primary.database'),
        ));
        $filename = 'backup_'.date('Y_m_d_H_i_s').'.sql.gz';
        $full_path = storage_path().config('constant.storage_root').'backup/'.$filename;
        $db_export->dump($full_path);
        return ['status' => 'success','filename' => $filename];
    } catch(\App\Helper\Shuttle_Exception $e) {
        $message = $e->getMessage();
        return ['status' => 'error'];
    }
}

function getColor(){
    $color = ['warning','danger','success','info','primary'];
    $index=array_rand($color);
    return $color[$index];
}

function showDateTime($time = ''){
    if($time == '')
        return;

    $format = config('config.date_format') ? : 'Y-m-d';
    if(config('config.time_format'))
        return date($format.',h:i a',strtotime($time));
    else
        return date($format.',H:i',strtotime($time));
}

function showTime($time = ''){
    if($time == '' || $time == null)
        return;
    if(config('config.time_format') == '24hrs')
        return date('H:i',strtotime($time));
    else
        return date('h:i a',strtotime($time));
}

function showDate($date = ''){
    if($date == '' || $date == null)
        return;

    $format = config('config.date_format') ? : 'Y-m-d';
    return date($format,strtotime($date));
}

function showDuration($second){
    $hour = floor($second / 3600);
    $minute = floor(($second / 60) % 60);
    return str_pad($hour, 2, '0', STR_PAD_LEFT).':'.str_pad($minute, 2, '0', STR_PAD_LEFT);
}

function createLineTreeView($array, $currentParent = 1, $currLevel = 0, $prevLevel = -1) {
    foreach ($array as $categoryId => $category) {
    if ($currentParent == $category['parent_id']) {
        if ($currLevel > $prevLevel) echo " <ul class='tree'> ";
        if ($currLevel == $prevLevel) echo " </li> ";

            echo '<li>'.$category['name'];

        if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
        $currLevel++;
        createLineTreeView ($array, $categoryId, $currLevel, $prevLevel);
        $currLevel--;
        }
    }
    if ($currLevel == $prevLevel) echo " </li>  </ul> ";
}

function getChilds($array, $currentParent = 1, $level = 1, $child = array(), $currLevel = 0, $prevLevel = -1) {
    foreach ($array as $categoryId => $category) {
    if ($currentParent == $category['parent_id']) {
        if ($currLevel > $prevLevel){}
        if ($currLevel == $prevLevel){}
        $child[] = $categoryId;
        if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
        $currLevel++;
        if($level)
            $child = getChilds($array, $categoryId, $level, $child, $currLevel, $prevLevel);
        $currLevel--;
        }
    }
    if ($currLevel == $prevLevel){}
    return $child;
}

function childLocation($location_id = '', $type = 0, $level = 1){

    // $type = 1 => only Id of locations else name with Id of locations
    // $level = 1 => All location level else first location level

    if($location_id == '')
        $location_id = Auth::user()->Profile->location_id;

    $location_name = \App\Location::all()->pluck('name','id')->all();

    if(defaultRole())
        $children = \App\Location::all()->pluck('id')->all();

    if(!config('config.location_level'))
        $children = \App\Location::whereTopLocationId($location_id)->get()->pluck('id')->all();

    $tree = array();
    $locations = \App\Location::whereNotNull('top_location_id')->get();
    foreach($locations as $location)
        $tree[$location->id] = ['parent_id' => $location->top_location_id];

    $children = getChilds($tree,$location_id,$level);

    if($type)
        return $children;

    $children_with_name = array();
    foreach($children as $child)
        $children_with_name[$child] = !empty($location_name[$child]) ? $location_name[$child] : null;

    return $children_with_name;
}

function childDesignation($designation_id = '', $type = 0, $level = 1){

    // $type = 1 => only Id of designations else name with Id of designations
    // $level = 1 => All designation level else first designation level

    if($designation_id == '')
        $designation_id = \Auth::user()->Profile->designation_id;

    $designation_name = \App\Designation::all()->pluck('designation_with_department','id')->all();

    if(defaultRole())
        $children =  \App\Designation::all()->pluck('id')->all();

    if(!config('config.designation_level'))
        $children =  \App\Designation::whereTopDesignationId($designation_id)->get()->pluck('id')->all();

    $tree = array();
    $designations = \App\Designation::whereNotNull('top_designation_id')->get();
    foreach($designations as $designation)
        $tree[$designation->id] = ['parent_id' => $designation->top_designation_id];
    $children = getChilds($tree,$designation_id,$level);

    if($type)
        return $children;

    $children_with_name = array();
    foreach($children as $child)
        $children_with_name[$child] = !empty($designation_name[$child]) ? $designation_name[$child] : null;

    return $children_with_name;
}

function getUserFromDesignation($designation_id){
    $users = \App\User::whereHas('profile',function($query) use($designation_id){
        $query->where('designation_id',$designation_id);
    })->get()->pluck('id')->all();

    return $users;
}

function getParent($designation_id = ''){
    $designation_id = ($designation_id) ? : \Auth::user()->Profile->designation_id;
    $designations = \App\Designation::all()->pluck('top_designation_id','id')->all();
    return getParentDesignation($designation_id,$designations);
}

function getParentUser($designation_id){
    $top_designation_users = \App\User::whereHas('profile',function($query) use($designation_id){
        $query->whereIn('designation_id',getParent($designation_id));
    })->get();

    return $top_designation_users;
}

function getParentUserId($designation_id){
    $query = getParentUser($designation_id);
    if($query->count())
        return $query->pluck('id')->all();
    else
        return [];
}

function getDirectParentUserId($user = null){
    $user = ($user) ? : \Auth::user();
    if(!$user->Profile->designation_id)
        return [];

    $designation = \App\Designation::whereId($user->Profile->designation_id)->first();

    if($designation->top_designation_id)
        return getUserFromDesignation($designation->top_designation_id);
    else
        return [];
}

function getParentDesignation($designation_id, $data, $parents=array()) {
    $parent_id = isset($data[$designation_id]) ? $data[$designation_id] : null;
    if ($parent_id != null) {
        $parents[] = $parent_id;
        return getParentDesignation($parent_id, $data, $parents);
    }
    return $parents;
}

function isChild($child_designation_id,$parent_designation_id = ''){
    if($parent_designation_id == '')
        $parent_designation_id = Auth::user()->Profile->designation_id;

    if(in_array($child_designation_id,childDesignation($parent_designation_id, 1)))
        return true;
    else
        return false;
}

function profileSetup($user,$type = 'percentage'){
    $profile = $user->Profile;
    $data['name'] = ($user->full_name) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['employee_code'] = ($profile->employee_code) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['gender'] = ($profile->gender) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['unique_identification_number'] = ($profile->unique_identification_number) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['marital_status'] = ($profile->marital_status) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['nationality'] = ($profile->nationality) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['date_of_birth'] = ($profile->date_of_birth) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['date_of_anniversary'] = ($profile->marital_status == 'married' && !$profile->date_of_anniversary) ? ['status' => 1,'weightage' => 2] : ['status' => 0, 'self' => 0];
    $data['phone'] = ($profile->phone) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['address_line_1'] = ($profile->address_line_1) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['city'] = ($profile->city) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['state'] = ($profile->state) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['country'] = ($profile->country_id) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['avatar'] = ($profile->avatar) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['avatar'] = ($profile->avatar) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['avatar'] = ($profile->avatar) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['designation'] = ($profile->designation_id) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['location'] = ($profile->location_id) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['contact'] = ($user->UserContact->count()) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['bank_account'] = ($user->UserBankAccount->count()) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['document'] = ($user->UserDocument->count()) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['qualification'] = ($user->UserQualification->count()) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['experience'] = ($user->UserExperience->count()) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['employment'] = (getEmployment(date('Y-m-d'),$user->id)) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['shift'] = (getShift(date('Y-m-d'),$user->id)) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['leave'] = (getUserLeave(date('Y-m-d'),$user->id)) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['salary'] = (getUserSalary(date('Y-m-d'),$user->id)) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['contract'] = (getUserContract(date('Y-m-d'),$user->id)) ? ['status' => 1,'weightage' => 5] : ['status' => 0];

    if($type == 'percentage'){
        $complete = 0;
        foreach($data as $value){
            if($value['status'])
                $complete += $value['weightage'];
        }
        return $complete;
    }

    return $data;
}

function profileclientSetup($client,$type = 'percentage'){
    $profileclient = $client->Profileclient;
    $data['name'] = ($client->full_name) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['gender'] = ($profileclient->gender) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['date_of_birth'] = ($profileclient->date_of_birth) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['phone'] = ($profileclient->phone) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['address_line_1'] = ($profileclient->address_line_1) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['city'] = ($profileclient->city) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['state'] = ($profileclient->state) ? ['status' => 1,'weightage' => 2] : ['status' => 0];
    $data['country'] = ($profileclient->country_id) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['avatar'] = ($profileclient->avatar) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['avatar'] = ($profileclient->avatar) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['avatar'] = ($profileclient->avatar) ? ['status' => 1,'weightage' => 5] : ['status' => 0];
    $data['document'] = ($client->ClientDocument->count()) ? ['status' => 1,'weightage' => 5] : ['status' => 0];

    if($type == 'percentage'){
        $complete = 0;
        foreach($data as $value){
            if($value['status'])
                $complete += $value['weightage'];
        }
        return $complete;
    }

    return $data;
}

function getEmployment($date = null, $user_id = null){
    $user_id = ($user_id) ? : \Auth::user()->id;
    $date = ($date) ? : date('Y-m-d');

    $employment = \App\UserEmployment::whereUserId($user_id)->where('date_of_joining','<=',$date)->where(function($query) use($date){
        $query->where('date_of_leaving','>=',$date)->orWhereNull('date_of_leaving');
    })->first();

    return ($employment) ? $employment : null;
}

function getUserLeave($date = null, $user_id = null){
    $user_id = ($user_id) ? : \Auth::user()->id;
    $date = ($date) ? : date('Y-m-d');

    $user_leave = \App\UserLeave::whereUserId($user_id)->where('from_date','<=',$date)->where('to_date','>=',$date)->first();

    return ($user_leave) ? $user_leave : null;
}

function getUserSalary($date = null, $user_id = null){
    $user_id = ($user_id) ? : \Auth::user()->id;
    $date = ($date) ? : date('Y-m-d');

    $user_salary = \App\UserSalary::whereUserId($user_id)->where('from_date','<=',$date)->where('to_date','>=',$date)->first();

    return ($user_salary) ? $user_salary : null;
}

function getUserNetSalary($date = null, $user_id = null){
    $user_id = ($user_id) ? : \Auth::user()->id;
    $date = ($date) ? : date('Y-m-d');

    $user_salary = getUserSalary($date,$user_id);

    if(!$user_salary){
        $data['salary_type'] = '-';
        $data['net_salary'] = '-';
        $data['currency'] = '-';
        $data['net_salary_with_currency'] = '-';
        return $data;
    } else {

        if($user_salary->type == 'hourly'){
            $data['salary_type'] = 'hourly';
            $data['net_salary'] = $user_salary->hourly_rate;
            $data['currency'] = $user_salary->currency_id;
            $data['net_salary_with_currency'] = currency($data['net_salary'],1,$data['currency']).' / '.trans('messages.hour');
            return $data;
        }

        $net_salary = 0;
        foreach($user_salary->UserSalaryDetail as $user_salary_detail){
            if($user_salary_detail->SalaryHead->type == 'earning')
                $net_salary += $user_salary_detail->amount;
            else
                $net_salary -= $user_salary_detail->amount;

        }
        $data['salary_type'] = 'monthly';
        $data['net_salary'] = $net_salary;
        $data['currency'] = $user_salary->currency_id;
        $data['net_salary_with_currency'] = currency($data['net_salary'],1,$data['currency']).' / '.trans('messages.month');
        return $data;
    }

}

function getUserContract($date = null, $user_id = null){
    $user_id = ($user_id) ? : \Auth::user()->id;
    $date = ($date) ? : date('Y-m-d');

    $user_contract = \App\UserContract::whereUserId($user_id)->where('from_date','<=',$date)->where(function($query) use($date){
        $query->where('to_date','>=',$date)->orWhereNull('to_date');
    })->first();

    return ($user_contract) ? $user_contract : null;
}

function getShift($date = null, $user_id = null){
    $user_id = ($user_id) ? : \Auth::user()->id;
    $date = ($date) ? : date('Y-m-d');

          $shift = \App\UserShift::whereUserId($user_id)->where('from_date','<=',$date)->where(function($query) use($date){
              $query->where('to_date','>=',$date)->orWhereNull('to_date');
          })->first();

          if($shift)
              return ($shift->shift_id) ? $shift->Shift->ShiftDetail->where('day',strtolower(date('l',strtotime($date))))->first() : $shift;

          $default_shift = \App\Shift::whereIsDefault(1)->first();

          return ($default_shift) ? $default_shift->ShiftDetail->where('day',strtolower(date('l',strtotime($date))))->first() : '';
}

function ipRange($network, $ip) {
    $network=trim($network);
    $orig_network = $network;
    $ip = trim($ip);
    if ($ip == $network) {
        return TRUE;
    }
    $network = str_replace(' ', '', $network);
    if (strpos($network, '*') !== FALSE) {
        if (strpos($network, '/') !== FALSE) {
            $asParts = explode('/', $network);
            $network = @ $asParts[0];
        }
        $nCount = substr_count($network, '*');
        $network = str_replace('*', '0', $network);
        if ($nCount == 1) {
            $network .= '/24';
        } else if ($nCount == 2) {
            $network .= '/16';
        } else if ($nCount == 3) {
            $network .= '/8';
        } else if ($nCount > 3) {
            return TRUE;
        }
    }

    $d = strpos($network, '-');
    if ($d === FALSE) {
        $ip_arr = explode('/', $network);
        if (!preg_match("@\d*\.\d*\.\d*\.\d*@", $ip_arr[0], $matches)){
            $ip_arr[0].=".0";
        }
        $network_long = ip2long($ip_arr[0]);
        $x = ip2long($ip_arr[1]);
        $mask = long2ip($x) == $ip_arr[1] ? $x : (0xffffffff << (32 - $ip_arr[1]));
        $ip_long = ip2long($ip);
        return ($ip_long & $mask) == ($network_long & $mask);
    } else {
        $from = trim(ip2long(substr($network, 0, $d)));
        $to = trim(ip2long(substr($network, $d+1)));
        $ip = ip2long($ip);
        return ($ip>=$from and $ip<=$to);
    }
}

function randomString($length,$type = 'token'){
    if($type == 'password')
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    elseif($type == 'username')
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    else
         $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $token = substr( str_shuffle( $chars ), 0, $length );
    return $token;
}

function checkDBConnection(){
    $link = @mysqli_connect(config('database.connections.primary.host'),
        config('database.connections.primary.username'),
        config('database.connections.primary.password'));

    if($link)
        return mysqli_select_db($link,config('database.connections.primary.database'));
    else
        return false;
}

function setConfig($config_vars){

    foreach($config_vars as $config_var){
        config(['config.'.$config_var->name => (isset($config_var->value) && $config_var->value != '' && $config_var->value != null) ? $config_var->value : config('config.'.$config_var->name)]);
    }

    $config_pusher_app_id = $config_vars->where('name','pusher_app_id')->first();
    $pusher_app_id = ($config_pusher_app_id && $config_pusher_app_id->value) ? $config_pusher_app_id->value : null;

    $config_pusher_app_key = $config_vars->where('name','pusher_key')->first();
    $pusher_app_key = ($config_pusher_app_key && $config_pusher_app_key->value) ? $config_pusher_app_key->value : null;

    $config_pusher_app_secret = $config_vars->where('name','pusher_secret')->first();
    $pusher_app_secret = ($config_pusher_app_secret && $config_pusher_app_secret->value) ? $config_pusher_app_secret->value : null;

    $config_pusher_cluster = $config_vars->where('name','pusher_cluster')->first();
    $pusher_app_cluster = ($config_pusher_cluster && $config_pusher_cluster->value) ? $config_pusher_cluster->value : null;

    $config_pusher_encrypted = $config_vars->where('name','pusher_encrypted')->first();
    $pusher_encrypted = ($config_pusher_encrypted && $config_pusher_encrypted->value) ? $config_pusher_encrypted->value : true;

    config([
    'broadcasting.default' => 'pusher',
    'broadcasting.connections.pusher.driver' => 'pusher',
    'broadcasting.connections.pusher.key' => $pusher_app_key,
    'broadcasting.connections.pusher.secret' => $pusher_app_secret,
    'broadcasting.connections.pusher.app_id' => $pusher_app_id,
    'broadcasting.connections.pusher.options.cluster' => $pusher_app_cluster,
    'broadcasting.connections.pusher.options.encrypted' => $pusher_encrypted,
    ]);

    $config_mail_driver = $config_vars->where('name','driver')->first();
    $mail_driver = ($config_mail_driver && $config_mail_driver->value) ? $config_mail_driver->value : env('MAIL_DRIVER');

    $config_mail_from_address = $config_vars->where('name','from_address')->first();
    $mail_from_address = ($config_mail_from_address && $config_mail_from_address->value) ? $config_mail_from_address->value : env('MAIL_FROM_ADDRESS');

    $config_mail_from_name = $config_vars->where('name','from_name')->first();
    $mail_from_name = ($config_mail_from_name && $config_mail_from_name->value) ? $config_mail_from_name->value : env('MAIL_FROM_NAME');

    $config_mail_encryption = $config_vars->where('name','encryption')->first();
    $mail_encryption = ($config_mail_encryption && $config_mail_encryption->value) ? $config_mail_encryption->value : env('MAIL_ENCRYPTION');

    config([
    'mail.driver' => $mail_driver,
    'mail.from.address' => $mail_from_address,
    'mail.from.name' => $mail_from_name,
    'mail.encryption' => $mail_encryption
    ]);

    if(config('mail.driver') == 'smtp'){
        config([
        'mail.host' => ($config_vars->where('name','host')->first()) ? $config_vars->where('name','host')->first()->value : '',
        'mail.port' => ($config_vars->where('name','port')->first()) ? $config_vars->where('name','port')->first()->value : '',
        'mail.username' => ($config_vars->where('name','username')->first()) ? $config_vars->where('name','username')->first()->value : '',
        'mail.password' => ($config_vars->where('name','password')->first()) ? $config_vars->where('name','password')->first()->value : ''
        ]);
    }

    if(config('mail.driver') == 'mailgun'){
        config([
        'mail.host' => ($config_vars->where('name','mailgun_host')->first()) ? $config_vars->where('name','mailgun_host')->first()->value : '',
        'mail.port' => ($config_vars->where('name','mailgun_port')->first()) ? $config_vars->where('name','mailgun_port')->first()->value : '',
        'mail.username' => ($config_vars->where('name','mailgun_username')->first()) ? $config_vars->where('name','mailgun_username')->first()->value : '',
        'mail.password' => ($config_vars->where('name','mailgun_password')->first()) ? $config_vars->where('name','mailgun_password')->first()->value : '',
        'services.mailgun.domain' => ($config_vars->where('name','mailgun_domain')->first()) ? $config_vars->where('name','mailgun_domain')->first()->value : '',
        'services.mailgun.secret' => ($config_vars->where('name','mailgun_secret')->first()) ? $config_vars->where('name','mailgun_secret')->first()->value : '',
        ]);
    }

    if(config('mail.driver') == 'mandrill'){
        config([
            'services.mandrill.secret' => ($config_vars->where('name','mandrill_secret')->first()) ? $config_vars->where('name','mandrill_secret')->first()->value : ''
        ]);
    }
}

function createSlug($string){
   if(checkUnicode($string))
        $slug = str_replace(' ', '-', $string);
   else
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));
   return $slug;
}

function getDesc($string,$length = 100){
    $string = strip_tags($string);
    return substr($string, 0, $length);
}

function checkUnicode($string)
{
    if(strlen($string) != strlen(utf8_decode($string)))
    return true;
    else
    return false;
}

function toWord($word){
    $word = str_replace('_', ' ', $word);
    $word = str_replace('-', ' ', $word);
    $word = ucwords($word);
    return $word;
}

function toWordTranslate($word){

    if(\Lang::has('messages.'.$word))
        return trans('messages.'.$word);

    $word = str_replace(' ', '-', $word);
    $word = str_replace('_', '-', $word);
    $word = strtolower($word);
    $word = explode('-',$word);
    $translation = array();
    foreach($word as $value)
        $translation[] = trans('messages.'.$value);
    return implode(' ',$translation);
}

function generateCSV($data,$filename){
    \Storage::makeDirectory('failed_bulk_upload');
    $filename = storage_path().config('constant.storage_root').'failed_bulk_upload/'.$filename;
    $handle = fopen($filename, 'w+');
    foreach($data as $column)
        fputcsv($handle, $column);
    fclose($handle);
}

function getAlphabet($i){
    $data = [
        '0' => 'a',
        '1' => 'b',
        '2' => 'c',
        '3' => 'd',
        '4' => 'e',
        '5' => 'f',
        '6' => 'g',
        '7' => 'h',
        '8' => 'i',
        '9' => 'j',
        '10' => 'k',
        '11' => 'l',
        '12' => 'm',
        '13' => 'n',
        '14' => 'o',
        '15' => 'p',
        '16' => 'q',
        '17' => 'r',
        '18' => 's',
        '19' => 't',
        '20' => 'u',
        '21' => 'v',
        '22' => 'w',
        '23' => 'x',
        '24' => 'y',
        '25' => 'z'
    ];

    return $data[$i];
}

function getCustomFields($form, $custom_field_values = array()){
    if(!config('config.enable_custom_field'))
        return;

    $custom_fields = \App\CustomField::whereForm($form)->get();

    echo '<div class="row">';
    foreach($custom_fields as $custom_field){

      $c_values = (array_key_exists($custom_field->name, $custom_field_values)) ? $custom_field_values[$custom_field->name] : '';
      $options = explode(',',$custom_field->options);

      $required = '';

      echo '<div class="col-md-6"><div class="form-group">';
      echo '<label for="'.$custom_field->name.'">'.$custom_field->title.'</label>';

      if($custom_field->type == 'select'){
        echo '<select class="form-control show-tick" placeholder="'.trans('messages.select_one').'" id="'.$custom_field->name.'" name="'.$custom_field->name.'"'.$required.'>
        <option value="">'.trans('messages.select_one').'</option>';
        foreach($options as $option){
            if($option == $c_values)
                echo '<option value="'.$option.'" selected>'.ucfirst($option).'</option>';
            else
                echo '<option value="'.$option.'">'.ucfirst($option).'</option>';
        }
        echo '</select>';
      }
      elseif($custom_field->type == 'radio'){
        echo '<div>
            <div class="radio">';
            foreach($options as $option){
                if($option == $c_values)
                    $checked = "checked";
                else
                    $checked = "";
                echo '<label><input type="radio" name="'.$custom_field->name.'" id="'.$custom_field->name.'" value="'.$option.'" '.$required.' '.$checked.' class="icheck"> '.ucfirst($option).'</label> ';
            }
        echo '</div>
        </div>';
      }
      elseif($custom_field->type == 'checkbox'){
        echo '<div>
            <div class="checkbox">';
            foreach($options as $option){
                if(in_array($option,explode(',',$c_values)))
                    $checked = "checked";
                else
                    $checked = "";
                echo '<label><input type="checkbox" name="'.$custom_field->name.'[]" id="'.$custom_field->name.'" value="'.$option.'" '.$checked.' '.$required.' class="icheck"> '.ucfirst($option).'</label> ';
            }
        echo '</div>
        </div>';
      }
      elseif($custom_field->type == 'textarea')
       echo '<textarea class="form-control" data-limit="'.config('config.textarea_limit').'" placeholder="'.$custom_field->title.'" name="'.$custom_field->name.'" cols="30" rows="3" id="'.$custom_field->name.'"'.$required.' data-show-counter=1 data-autoresize=1>'.$c_values.'</textarea><span class="countdown"></span>';
      else
        echo '<input class="form-control '.(($custom_field->type == 'date') ? 'datepicker' : '').'" value="'.$c_values.'" placeholder="'.$custom_field->title.'" name="'.$custom_field->name.'" type="text" value="" id="'.$custom_field->name.'"'.$required.' '.(($custom_field->type == 'date') ? 'readonly' : '').'>';
      echo '</div></div>';
    }
    echo '</div>';
}

function putCustomHeads($form, $col_heads){
    if(!config('config.enable_custom_field'))
        return $col_heads;

    $custom_fields = \App\CustomField::whereForm($form)->get();
    foreach($custom_fields as $custom_field)
        array_push($col_heads, $custom_field->title);
    return $col_heads;
}

function validateCustomField($form,$request){
    $custom_validation = array();
    $friendly_names = array();
    if(!config('config.enable_custom_field')){}
    else {
        $custom_fields = \App\CustomField::whereForm($form)->get();
        foreach($custom_fields as $custom_field){
            if($custom_field->is_required){
                $custom_validation[$custom_field->name] = 'required'.(($custom_field->type == 'date') ? '|date' : '').(($custom_field->type == 'number') ? '|numeric' : '').(($custom_field->type == 'email') ? '|email' : '').(($custom_field->type == 'url') ? '|url' : '');
                $friendly_names[$custom_field->name] = $custom_field->title;
            }
       }
    }

   $validation = \Validator::make($request->all(),$custom_validation);
   $validation->setAttributeNames($friendly_names);
   return $validation;
}

function fetchCustomValues($form){
    $values = array();
    if(!config('config.enable_custom_field'))
        return $values;

    $rows = \DB::table('custom_fields')
    ->join('custom_field_values','custom_field_values.custom_field_id','=','custom_fields.id')
    ->where('form','=',$form)
    ->select(\DB::raw('unique_id,custom_field_id,value,type'))
    ->get();
    foreach($rows as $row){
        $field_values = [];
        $value = '';
        if($row->type == 'checkbox'){
            $field_values = explode(',',$row->value);
            $value .= '<ol>';
            foreach($field_values as $fv)
                $value .= '<li>'.toWord($fv).'</li>';
            $value .= '</ol>';
        } else
        $value = toWord($row->value);

        $values[$row->unique_id][$row->custom_field_id] = $value;
    }
    return $values;
}

function getCustomFieldValues($form,$id){
    if(!config('config.enable_custom_field'))
        return [];

    return \DB::table('custom_fields')
    ->join('custom_field_values','custom_field_values.custom_field_id','=','custom_fields.id')
    ->where('form','=',$form)
    ->where('unique_id','=',$id)
    ->pluck('value','name')->all();
}

function getCustomColId($form){
    if(!config('config.enable_custom_field'))
        return [];

    return \App\CustomField::whereForm($form)->pluck('id')->all();
}

function storeCustomField($form, $id, $request){
    if(!config('config.enable_custom_field'))
        return;

    $custom_fields = \App\CustomField::whereForm($form)->get();
    foreach($custom_fields as $custom_field){
        $custom_field_value = new \App\CustomFieldValue;
        $value = $request[$custom_field->name];
        if(is_array($value))
            $value = implode(',',$value);
        $custom_field_value->value = $value;
        $custom_field_value->custom_field_id = $custom_field->id;
        $custom_field_value->unique_id = $id;
        $custom_field_value->save();
    }
}

function updateCustomField($form, $id, $request){
    if(!config('config.enable_custom_field'))
        return;

    $custom_fields = \App\CustomField::whereForm($form)->get();
    foreach($custom_fields as $custom_field){
        $value = array_key_exists($custom_field->name, $request) ? $request[$custom_field->name] : '';

        if(is_array($value))
            $value = implode(',',$value);

        $custom = \DB::table('custom_fields')
            ->join('custom_field_values','custom_field_values.custom_field_id','=','custom_fields.id')
            ->where('form','=',$form)
            ->where('name','=',$custom_field->name)
            ->where('unique_id','=',$id)
            ->select(\DB::raw('custom_field_values.id'))
            ->first();

        if($custom)
            $custom_field_value = \App\CustomFieldValue::find($custom->id);
        else
            $custom_field_value = new \App\CustomFieldValue;
        $custom_field_value->value = $value;
        $custom_field_value->custom_field_id = $custom_field->id;
        $custom_field_value->unique_id = $id;
        $custom_field_value->save();
    }
}

function deleteCustomField($form, $id){
    $data = \DB::table('custom_field_values')
        ->join('custom_fields','custom_fields.id','=','custom_field_values.custom_field_id')
        ->where('form','=',$form)
        ->where('unique_id','=',$id)
        ->delete();
}

function getUpload($module,$module_id){
    return \App\Upload::whereModule($module)->whereModuleId($module_id)->whereStatus(1)->get();
}

function validateUpload($module,$request){
    $file_uploaded_count = \App\Upload::whereIn('upload_key',$request->input('upload_key'))->count();
    if($file_uploaded_count > config('upload.'.$module.'.limit'))
        return ['message' => trans('messages.max_file_allowed',['attribute' => config('upload.'.$module.'.limit')]),'status' => 'error'];
    else
        return ['status' => 'success'];
}

function storeUpload($module,$module_id,$request){
    foreach($request->input('upload_key') as $upload_key){
        $uploads = \App\Upload::whereModule($module)->whereUploadKey($upload_key)->get();
        foreach($uploads as $upload){
            $upload->module_id = $module_id;
            $upload->uuid = getUuid();
            $upload->status = 1;
            $upload->save();
            \Storage::move('temp_attachments/'.$upload->attachments, 'attachments/'.$upload->attachments);
        }
    }
}

function editUpload($module,$module_id){
    \App\Upload::whereModule($module)->whereModuleId($module_id)->whereStatus(1)->update(['is_temp_delete' => 0]);
    return \App\Upload::whereModule($module)->whereModuleId($module_id)->whereStatus(1)->get();
}

function updateUpload($module,$module_id,$request){
    $existing_upload = \App\Upload::whereModule($module)->whereModuleId($module_id)->whereIsTempDelete(0)->count();

    $new_upload_count = 0;
    foreach($request->input('upload_key') as $upload_key)
        $new_upload_count += \App\Upload::whereModule($module)->whereUploadKey($upload_key)->count();

    if($existing_upload + $new_upload_count > config('upload.'.$module.'.limit'))
        return ['message' => trans('messages.max_file_allowed',['attribute' => config('upload.'.$module.'.limit')]),'status' => 'error'];

    foreach($request->input('upload_key') as $upload_key){
        $uploads = \App\Upload::whereModule($module)->whereUploadKey($upload_key)->get();
        foreach($uploads as $upload){
            $upload->module_id = $module_id;
            $upload->uuid = getUuid();
            $upload->status = 1;
            $upload->save();
            \Storage::move('temp_attachments/'.$upload->attachments, 'attachments/'.$upload->attachments);
        }
    }

    $temp_delete_uploads = \App\Upload::whereModule($module)->whereModuleId($module_id)->whereIsTempDelete(1)->get();
    foreach($temp_delete_uploads as $temp_delete_upload)
        \Storage::delete('attachments/'.$temp_delete_upload->attachments);

    \App\Upload::whereModule($module)->whereModuleId($module_id)->whereIsTempDelete(1)->delete();

    return ['status' => 'success'];
}

function deleteUpload($module,$module_id){
    if(getMode()){
        $uploads = \App\Upload::whereModule($module)->whereModuleId($module_id)->get();
        foreach($uploads as $upload)
            \Storage::delete('attachments/'.$upload->attachments);
        \App\Upload::whereModule($module)->whereModuleId($module_id)->delete();
    }
}

function getUpload2($module,$module_id){
    return \App\Upload::whereModule($module)->whereModuleId($module_id)->whereStatus(1)->get();
}

function validateUpload2($module,$request){
    $file_uploaded_count = \App\Upload::whereIn('upload_key',$request->input('upload_key'))->count();
    if($file_uploaded_count > config('upload.'.$module.'.limit'))
        return ['message' => trans('messages.max_file_allowed',['attribute' => config('upload.'.$module.'.limit')]),'status' => 'error'];
    else
        return ['status' => 'success'];
}

function storeUpload2($module,$module_id,$request){
    foreach($request->input('upload_key') as $upload_key){
        $uploads = \App\Upload::whereModule($module)->whereUploadKey($upload_key)->get();
        foreach($uploads as $upload){
            $upload->module_id = $module_id;
            $upload->uuid = getUuid();
            $upload->status = 1;
            $upload->save();
            \Storage::move('temp_attachments/'.$upload->attachments, 'attachments/'.$upload->attachments);
        }
    }
}

function editUpload2($module,$module_id){
    \App\Upload::whereModule($module)->whereModuleId($module_id)->whereStatus(1)->update(['is_temp_delete' => 0]);
    return \App\Upload::whereModule($module)->whereModuleId($module_id)->whereStatus(1)->get();
}

function updateUpload2($module,$module_id,$request){
    $existing_upload = \App\Upload::whereModule($module)->whereModuleId($module_id)->whereIsTempDelete(0)->count();

    $new_upload_count = 0;
    foreach($request->input('upload_key') as $upload_key)
        $new_upload_count += \App\Upload::whereModule($module)->whereUploadKey($upload_key)->count();

    if($existing_upload + $new_upload_count > config('upload.'.$module.'.limit'))
        return ['message' => trans('messages.max_file_allowed',['attribute' => config('upload.'.$module.'.limit')]),'status' => 'error'];

    foreach($request->input('upload_key') as $upload_key){
        $uploads = \App\Upload::whereModule($module)->whereUploadKey($upload_key)->get();
        foreach($uploads as $upload){
            $upload->module_id = $module_id;
            $upload->uuid = getUuid();
            $upload->status = 1;
            $upload->save();
            \Storage::move('temp_attachments/'.$upload->attachments, 'attachments/'.$upload->attachments);
        }
    }

    $temp_delete_uploads = \App\Upload::whereModule($module)->whereModuleId($module_id)->whereIsTempDelete(1)->get();
    foreach($temp_delete_uploads as $temp_delete_upload)
        \Storage::delete('attachments/'.$temp_delete_upload->attachments);

    \App\Upload::whereModule($module)->whereModuleId($module_id)->whereIsTempDelete(1)->delete();

    return ['status' => 'success'];
}

function deleteUpload2($module,$module_id){
    if(getMode()){
        $uploads = \App\Upload::whereModule($module)->whereModuleId($module_id)->get();
        foreach($uploads as $upload)
            \Storage::delete('attachments/'.$upload->attachments);
        \App\Upload::whereModule($module)->whereModuleId($module_id)->delete();
    }
}

function defaultRole(){
    if(\Entrust::hasRole(DEFAULT_ROLE))
        return 1;
    else
        return 0;
}

function getSubTaskRating($task_id,$user_id,$only = 0){
    $task = \App\Task::find($task_id);
    $sub_tasks = $task->SubTask->pluck('id')->all();
    $rating = \App\SubTaskRating::where('user_id','=',$user_id)->whereIn('sub_task_id',$sub_tasks)->avg('rating');
    if(!$only)
        return getRatingStar($rating);
    else
        return round(($rating*2),0)/2;
}

function getRatingStar($rating,$only = 0){
    $rating = round($rating * 2,0) / 2;
    $full_star = floor($rating);
    $half_star = $rating - $full_star;
    $star = '<div>';
    for($i = 1; $i <= $full_star; $i++)
        $star .= '<i class="fa fa-lg fa-star icon rating-star" ></i>';

    if($half_star)
        $star .= '<i class="fa fa-lg fa-star-half icon rating-star" ></i>';
    $star .= '</div>';

    if(!$only)
        return $star;
    else
        return ($rating) ? $rating : '';
}

function passwordRule(){
    $str = 'regex:/^.*(?=.{2,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/';
    return $str;
}

function usernameRule(){
    $str = 'regex:/^[a-zA-Z0-9_\.\-]*$/';
    return $str;
}

function getMonthNumber($month){
    $month = strtolower($month);

    if($month == 'january')
        return 1;
    elseif($month == 'february')
        return 2;
    elseif($month == 'march')
        return 3;
    elseif($month == 'april')
        return 4;
    elseif($month == 'may')
        return 5;
    elseif($month == 'june')
        return 6;
    elseif($month == 'july')
        return 7;
    elseif($month == 'august')
        return 8;
    elseif($month == 'september')
        return 9;
    elseif($month == 'october')
        return 10;
    elseif($month == 'november')
        return 11;
    elseif($month == 'december')
        return 12;
}

function timeAgo($time_ago){
    $time_ago = strtotime($time_ago);
    $cur_time = strtotime(date('Y-m-d H:i:s'));
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    if($seconds <= 60){
        return "$seconds seconds ago";
    }
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hours ago";
        }
    }
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}

function getDateDiff($date){
    $difference = date('z',strtotime($date)) - date('z');
    if($difference == 0)
        return trans('messages.today');
    elseif($difference == 1)
        return trans('messages.tomorrow');
    elseif($difference == -1)
        return trans('messages.yesterday');
    else
        return 0;
}

function daySuffix($num){
    $num = $num % 100;
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
        }
    }
    return 'th';
}

function getSelectOption($data){
    $new_data = array();
    foreach($data as $value)
        $new_data[$value] = $value;
    return $new_data;
}

function verifyPurchase($purchase_code = ''){
    $purchase_code = ($purchase_code != '') ? $purchase_code : env('PURCHASE_CODE');
    return true;
}

function is_connected()
{
    $connected = @fsockopen("www.google.com", 80);
    if ($connected){
        $is_conn = true;
        fclose($connected);
    }else{
        $is_conn = false;
    }
    return $is_conn;
}

function installPurchase($purchase_code,$envato_username,$email = ''){
    return true;
}

function complete($purchase_code){
    return true;
}

function releaseLicense(){
    return true;
}

function getUpdate(){
    return false;
}

function envu($data = array()){
    if(count($data) > 0){
        $env = file_get_contents(base_path() . '/.env');
        $env = preg_split('/\s+/', $env);;
        foreach((array)$data as $key => $value){
            foreach($env as $env_key => $env_value){
                $entry = explode("=", $env_value, 2);
                if($entry[0] == $key)
                    $env[$env_key] = $key . "=" . $value;
                else
                    $env[$env_key] = $env_value;
            }
        }
        $env = implode("\n", $env);
        file_put_contents(base_path() . '/.env', $env);
        return true;
    } else {
        return false;
    }
}

function write2Config($data,$file){
    $filename = base_path().'/config/'.$file.'.php';
    File::put($filename,var_export($data, true));
    File::prepend($filename,'<?php return ');
    File::append($filename, ';');
}

function translateList($config_lists){
    $lists = config('lists.'.$config_lists);
    $translated_list = array();
    foreach($lists as $key)
        $translated_list[$key] = transw($key);

    return $translated_list;
}

function transw($word){
    $word = (\Lang::has('messages.'.$word)) ? trans('messages.'.$word) : toWordTranslate($word);
    $word = ucwords($word);
    return str_replace('.', '', $word);
}

function getYears($start_year=1980,$end_year=2020){
    for($i=$start_year;$i<=$end_year;$i++)
    $years[$i] = $i;
    return $years;
}

function isSecure(){
    if(!getMode())
        return 1;

    $url = \Request::url();
    $result = strpos($url, 'wmlab');
    if($result === FALSE)
        return 0;
    else
        return 1;
}

function validateIp(){

    $ip = \Request::getClientIp();

    $wl_ips = \App\IpFilter::all();
    $allowedIps = array();
    foreach($wl_ips as $wl_ip){
        if($wl_ip->end)
            $allowedIps[] = $wl_ip->start.'-'.$wl_ip->end;
        else
            $allowedIps[] = $wl_ip->start;
    }

    foreach ($allowedIps as $allowedIp)
    {
        if (strpos($allowedIp, '*'))
        {
            $range = [
                str_replace('*', '0', $allowedIp),
                str_replace('*', '255', $allowedIp)
            ];
            if(ipExistsInRange($range, $ip)) return true;
        }
        else if(strpos($allowedIp, '-'))
        {
            $range = explode('-', str_replace(' ', '', $allowedIp));
            if(ipExistsInRange($range, $ip)) return true;
        }
        else
        {
            if (ip2long($allowedIp) === ip2long($ip)) return true;
        }
    }
    return false;
}

function ipExistsInRange(array $range, $ip)
{
    if (ip2long($ip) >= ip2long($range[0]) && ip2long($ip) <= ip2long($range[1]))
        return true;
    return false;
}

function postCurl($url,$postData){
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
    ));
    $data = curl_exec($ch);
    $gresponse = json_decode($data,true);
    return $gresponse;
}

function getAvatar($id, $size = 60){
    $user = \App\User::find($id);
    $profile = $user->Profile;
    $name = $user->full_name;
    $tooltip = $name;
    if(isset($profile->avatar))
        return '<img src="/'.config('constant.upload_path.avatar').$profile->avatar.'" class="img-circle" style="width:'.$size.'px";" alt="User avatar" data-toggle="tooltip" title="'.$tooltip.'">';
    else
        return '<p class="textAvatar" data-toggle="tooltip" title="'.$tooltip.'" data-image-size="'.$size.'">'.$name.'</p>';
}

function getclientAvatar($id, $size = 60){
    $user = \App\Client::find($id);
    $profileclient = $client->Profileclient;
    $name = $client->full_name;
    $tooltip = $name;
    if(isset($profileclient->avatar))
        return '<img src="/'.config('constant.upload_path.avatar').$profileclient->avatar.'" class="img-circle" style="width:'.$size.'px";" alt="User avatar" data-toggle="tooltip" title="'.$tooltip.'">';
    else
        return '<p class="textAvatar" data-toggle="tooltip" title="'.$tooltip.'" data-image-size="'.$size.'">'.$name.'</p>';
}

function getMode(){
    return config('constant.mode');
}

function getEnvironment(){

    if(!getMode())
        return 0;

    return (config('app.env') == 'local') ? 0 : 1;
}

function convertPHPSizeToBytes($sSize)
{
    if ( is_numeric( $sSize) ) {
       return $sSize;
    }
    $sSuffix = substr($sSize, -1);
    $iValue = substr($sSize, 0, -1);
    switch(strtoupper($sSuffix)){
    case 'P':
        $iValue *= 1024;
    case 'T':
        $iValue *= 1024;
    case 'G':
        $iValue *= 1024;
    case 'M':
        $iValue *= 1024;
    case 'K':
        $iValue *= 1024;
        break;
    }
    return $iValue;
}

function getMaxFileUploadSize()
{
    return min(convertPHPSizeToBytes(ini_get('post_max_size')), convertPHPSizeToBytes(ini_get('upload_max_filesize')));
}

function formatMemorySizeUnits($bytes)
{
    if ($bytes >= 1073741824)
        $bytes = round($bytes / 1073741824, 2) . ' GB';
    elseif ($bytes >= 1048576)
        $bytes = round($bytes / 1048576, 2) . ' MB';
    elseif ($bytes >= 1024)
        $bytes = round($bytes / 1024, 2) . ' kB';
    elseif ($bytes > 1)
        $bytes = $bytes . ' bytes';
    elseif ($bytes == 1)
        $bytes = $bytes . ' byte';
    else
        $bytes = '0 bytes';
    return $bytes;
}

function numberToWord($number){
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . numberToWord(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWord($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numberToWord($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
?>
