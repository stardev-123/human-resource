<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\AccountUpdateRequest;
set_time_limit(0);

Class AccountController extends Controller{
    use BasicController;

    public function verifyPurchase(){

    	$data['status'] = 'success';
    	if($data['status'] == 'success')
    		return redirect('/');
    	return view('install.verify');
    }

    public function updateApp(){

    	if(!getMode())
    		return redirect('/home');

    	if(!checkDBConnection())
    		return view('install.update');
    	else
    		return redirect('/');
    }

    public function postUpdateApp(AccountUpdateRequest $request){

    	//if(!getMode())
    		//return response()->json(['message' => trans('messages.invalid_link'), 'status' => 'error','redirect' => '/home']);

    	//if(!is_connected())
          //  return response()->json(['message' => trans('messages.check_internet_connection'), 'status' => 'error']);

		$purchase_code = $request->input('purchase_code');
		$envato_username = $request->input('envato_username');
		$email = $request->input('email');
		$mysql_database = $request->input('mysql_database');
		//$data = installPurchase($purchase_code,$envato_username,$email);
        $data['status']='success';
		if($data['status'] != 'success')
	        return response()->json(['message' => $data['message'], 'status' => 'error']);

		$link = @mysqli_connect($request->input('hostname'), $request->input('mysql_username'), $request->input('mysql_password'));

		if(!$link)
	        return response()->json(['message' => trans('messages.connection_not_established'), 'status' => 'error']);

		mysqli_select_db($link,$request->input('mysql_database'));
		$count_table_query = mysqli_query($link,"show tables");
		$count_table = mysqli_num_rows($count_table_query);

		if (!is_file('../database/'.env('BUILD').'.sql'))
            return response()->json(['message' => trans('messages.database_file_not_found'), 'status' => 'error']);

		if(!$count_table)
            return response()->json(['message' => trans('messages.no_existing_table_in_database'), 'status' => 'error']);

		$templine = '';
		$lines = file('../database/'.env('BUILD').'.sql');
		foreach ($lines as $line)
		{
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				mysqli_query($link,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
				$templine = '';
			}
		}

		mysqli_query($link,'SET FOREIGN_KEY_CHECKS = 0');
		envu([
			'DB_HOST' => $request->input('hostname'),
			'DB_NAME' => $request->input('mysql_database'),
			'DB_USERNAME' => $request->input('mysql_username'),
			'DB_PASSWORD' => $request->input('mysql_password'),
			'PURCHASE_CODE' => $purchase_code
			]);

		complete($purchase_code);

		$this->logActivity(['module' => 'application','activity' => 'updated']);

        return response()->json(['message' => trans('messages.application').' '.trans('messages.updated'), 'status' => 'success','redirect' => '/login']);
    }

    public function postVerifyPurchase(Request $request){
    	//if(!is_connected())
          //  return response()->json(['message' => trans('messages.check_internet_connection'), 'status' => 'error']);

    	//$data = verifyPurchase();
        $data['status']='success';
    	if($data['status'] == 'status')
            return response()->json(['message' => trans('messages.purchase_already_verified'), 'status' => 'error','redirect' => '/login']);

    	$purchase_code = $request->input('purchase_code');
    	$envato_username = $request->input('envato_username');
		//$data = installPurchase($request->input('purchase_code'),$request->input('envato_username'));
    $data['status'] = 'success';
		if($data['status'] == 'success'){
			envu(['PURCHASE_CODE' => $purchase_code]);
			complete($purchase_code);
	        return response()->json(['message' => $data['message'], 'status' => 'success','redirect' => '/login']);
		}
		else
	        return response()->json(['message' => $data['message'], 'status' => 'error']);
    }

   public function releaseLicense(){
    	//if(!getMode() || !defaultRole())
    		//return redirect('/home');

        //if(!is_connected())
           // return redirect('/home')->withErrors(trans('messages.check_internet_connection'));

        //$data = verifyPurchase();
		$data['status'] = 'success';
        if($data['status'] == 'error'){
            \Auth::logout();
            return redirect('/')->withErrors(trans('messages.invalid_purchase_license'));
        }

        //$data = releaseLicense();
		$data['status'] = 'success';
        if($data['status'] == 'success'){
        	envu(['PURCHASE_CODE' => '']);
            \Auth::logout();
            return redirect('/')->withSuccess(trans('messages.license_released'));
        } else
        	return redirect('/')->withErrors(trans('messages.something_wrong'));
   }

	public function checkUpdate(){
    	if(!getMode() || !defaultRole())
    		return redirect('/home');

    	$data = (env('BUILD') && is_connected()) ? getUpdate() : [];

    	$data = json_decode($data,true);
    	return view('install.check_update',compact('data'));
	}

	public function index(){

    	if(checkDBConnection())
			return redirect('/');

		$error = 0;
		$checks = array();

		if(dirname($_SERVER['REQUEST_URI']) != '/' && str_replace('\\', '/', dirname($_SERVER['REQUEST_URI'])) != '/')
			$checks[] = array('type' => 'error', 'message' => 'You are trying to install this application in a subfolder "'.dirname($_SERVER['REQUEST_URI']).'"');
		else
			$checks[] = array('type' => 'success', 'message' => ' Installation directory "'.$_SERVER['SERVER_NAME'].'"');

		$server = $_SERVER['SERVER_SOFTWARE'];
		$server_is_ok = ((stripos($server, 'Apache') === 0) || (stripos($server, 'nginx') === 0) );
		$checks[] = $this->check($server_is_ok, sprintf('Web server is suitable (%s)', $server), 'You should change the server to Apache or Nginx', true);
		$checks[] = $this->check($this->my_version_compare(phpversion(), '5.5.9', '>='), sprintf('PHP version is at least 5.5.9 (%s)', 'Current Version is '. phpversion()), 'Current version is '.phpversion(), true);
		$checks[] = $this->check(extension_loaded('fileinfo'), 'Fileinfo PHP extension enabled', 'Install and enable Fileinfo extension', true);
		$checks[] = $this->check(extension_loaded('mcrypt'), 'Mcrypt PHP extension enabled', 'Install and enable Mcrypt extension', true);
		$checks[] = $this->check(extension_loaded('openssl'), 'OpenSSL PHP extension enabled', 'Install and enable OpenSSL extension', true);
		$checks[] = $this->check(extension_loaded('tokenizer'), 'Tokenizer PHP extension enabled', 'Install and enable Tokenizer extension', true);
		$checks[] = $this->check(extension_loaded('mbstring'), 'Mbstring PHP extension enabled', 'Install and enable Mbstring extension', true);
		$checks[] = $this->check(extension_loaded('zip'), 'Zip archive PHP extension enabled', 'Install and enable Zip archive extension', true);
		$checks[] = $this->check(class_exists('PDO'), 'PDO is installed', 'Install PDO (mandatory for Eloquent)', true);
		$checks[] = $this->check(extension_loaded('curl'), 'CURL is installed', 'Install and enable CURL', true);
		$checks[] = $this->check(ini_get('allow_url_fopen'), 'allow_url_fopen is on', 'Turn on allow_url_fopen', true);

		foreach($checks as $check)
			if($check['type'] == 'error')
				$error++;

		$assets = ['form-wizard'];

		return view('install.index',compact('checks','error','assets'));
	}

	public function is_cli() {
	  return !isset($_SERVER['HTTP_HOST']);
	}

	public function my_version_compare($ver1, $ver2, $operator = null)
	{
	    $p = '#(\.0+)+($|-)#';
	    $ver1 = preg_replace($p, '', $ver1);
	    $ver2 = preg_replace($p, '', $ver2);
	    return isset($operator) ?
	        version_compare($ver1, $ver2, $operator) :
	        version_compare($ver1, $ver2);
	}

	public function check($boolean, $message, $help = '', $fatal = false) {
	  if($boolean)
	  	return array('type' => 'success','message' => $message);
	  else
	  	return array('type' => 'error', 'message' => $help);
	}

	public function get_ini_path() {
	  if ($path = get_cfg_var('cfg_file_path')) {
	    return $path;
	  }
	  return 'WARNING: not using a php.ini file';
	}

	public function store(AccountRequest $request){

    	//if(!is_connected())
    		//return response()->json(['messages' => trans('messages.check_internet_connection'),'status' => 'error']);

		$purchase_code = $request->input('purchase_code');
		$envato_username = $request->input('envato_username');
		$registered_email = $request->input('email');
		$mysql_database = $request->input('mysql_database');
		//$data = installPurchase($purchase_code,$envato_username,$registered_email);
        $data['status'] = 'success';
		if($data['status'] != 'success')
	        return response()->json(['message' => $data['message'], 'status' => 'error']);

        if(!preg_match('/^[a-zA-Z0-9_\.\-]*$/',$request->input('username')))
            return response()->json(['message' => trans('messages.username_rules'), 'status' => 'error']);

        $link = @mysqli_connect($request->input('hostname'), $request->input('mysql_username'), $request->input('mysql_password'));

		if (!$link)
            return response()->json(['message' => trans('messages.connection_not_established'), 'status' => 'error']);

		mysqli_select_db($link,$request->input('mysql_database'));
		$count_table_query = mysqli_query($link,"show tables");
		$count_table = mysqli_num_rows($count_table_query);

		if (!is_file('../database/database.sql'))
            return response()->json(['message' => trans('messages.database_file_not_found'), 'status' => 'error']);

		if($count_table)
            return response()->json(['message' => trans('messages.table_already_exists'), 'status' => 'error']);

		$templine = '';
		$lines = file('../database/database.sql');
		foreach ($lines as $line)
		{
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				mysqli_query($link,$templine) or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
				$templine = '';
			}
		}

		$username = $request->input('username');
		$password = bcrypt($request->input('password'));
		$email = $request->input('email');
		$first_name = $request->input('first_name');
		$last_name = $request->input('last_name');
		$default_role = config('constant.default_role');
		$default_designation = config('constant.default_designation');
		$default_department = config('constant.default_department');

		mysqli_query($link, "insert into roles(name,is_hidden) values('$default_role','1')");
		mysqli_query($link, "insert into departments(name,is_hidden) values('$default_department','1')");
		mysqli_query($link, "insert into designations(department_id,name,is_hidden) values('1','$default_designation',1)");
		mysqli_query($link, "insert into users(email,username,password,is_hidden,status) values('$email','$username','$password','1','active') ");
		mysqli_query($link, "insert into profiles(user_id,first_name,last_name,designation_id) values('1','$first_name','$last_name','1') ");
		mysqli_query($link, "insert into role_user(user_id,role_id) values('1','1') ");

		envu([
			'DB_HOST' => $request->input('hostname'),
			'DB_NAME' => $request->input('mysql_database'),
			'DB_USERNAME' => $request->input('mysql_username'),
			'DB_PASSWORD' => $request->input('mysql_password'),
			'PURCHASE_CODE' => $purchase_code
			]);

		complete($purchase_code);
        return response()->json(['message' => trans('messages.application').' '.trans('messages.installed'), 'status' => 'success','redirect' => '/login']);
	}
}
?>
