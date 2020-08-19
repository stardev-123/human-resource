<?php
namespace App\Http\Controllers;

Class ApiController extends Controller{
    use BasicController;

    public function clock($auth_token,$emp_code){
        if($auth_token == '' || $auth_token == null)
            return response()->json(['type' => 'error','error_code' => '100']);

        if($emp_code == '' || $emp_code == null)
            return response()->json(['type' => 'error','error_code' => '102']);

        $user = \App\User::whereAuthToken($auth_token)->first();

        if(!$user)
            return response()->json(['type' => 'error','error_code' => '101']);

        $clock_user_profile = \App\Profile::whereEmployeeCode($emp_code)->first();

        if(!$clock_user_profile)
            return response()->json(['type' => 'error','error_code' => '103']);

        $clock_user = $clock_user_profile->User;
        $user_id = $clock_user->id;

        if($clock_user->status != 'active')
            return response()->json(['type' => 'error','error_code' => '104']);

        return response()->json(['type' => 'success','user_id' => $user_id]);
    }

    public function clockIn(Request $request){

    	$auth_token = $request->input('auth_token');
    	$emp_code = $request->input('emp_code');

        $response = $this->clock($auth_token,$emp_code);
        $data = json_decode($response->content(),true);
        if($data['type'] != 'success')
            return $response;
        else
            $user_id = $data['user_id'];

        $url = url('/clock/in');
        $postData = array(
            'datetime' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'api' => 1
        );

        return postCurl($url,$postData);
    }

    public function clockOut(){

    	$auth_token = $request->input('auth_token');
    	$emp_code = $request->input('emp_code');
        
        $response = $this->clock($auth_token,$emp_code);
        $data = json_decode($response->content(),true);
        if($data['type'] != 'success')
            return $response;
        else
            $user_id = $data['user_id'];

        $url = url('/clock/out');
        $postData = array(
            'datetime' => date('Y-m-d H:i:s'),
            'user_id' => $user_id,
            'api' => 1
        );

        return postCurl($url,$postData);
    }
}