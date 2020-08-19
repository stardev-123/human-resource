<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use File;
use Session;

class WMLab
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $assets = array();
        $custom_field_values = array();
        $available_date = array();
        view()->share(compact('assets','custom_field_values','available_date'));

        foreach(config('constant.system_default') as $key => $value)
            config(['config.'.$key => config('constant.system_default.'.$key)]);

        if(!checkDBConnection() && !$request->is('install') && !$request->is('update'))
            return redirect('/install');

        if($request->is('install') || $request->is('update'))
            return $next($request);

        foreach(config('constant.path') as $key => $path)
            if($key != 'verifier')
                if (!File::exists(base_path().$path))
                    abort(399,$path.' '.trans('messages.file_not_found'));

        $config_vars = \App\Config::all();
        setConfig($config_vars);

        $default_permission = array();
        foreach(config('permission') as $key => $value)
            $default_permission[] = $key;

        $db_permissions = array();
        $db_permissions = \App\Permission::all()->pluck('name')->all();
        $permissions = array_diff($default_permission,$db_permissions);
        $permission_insert = array();
        foreach($permissions as $key => $value)
            $permission_insert[] = array('category' => config('permission.'.$value),'name' => $value,'is_default' => 1);

        if(count($permission_insert))
            \App\Permission::insert($permission_insert);

        $default_role = \App\Role::whereIsHidden(1)->first();
        define("DEFAULT_ROLE",($default_role) ? $default_role->name : '');

        $all_permissions = \App\Permission::all()->pluck('id')->all();
        $permission_role = \DB::table('permission_role')->where('role_id','=',$default_role->id)->pluck('permission_id')->all();
        $permission_role_array = array_diff($all_permissions,$permission_role);
        $permission_role_insert = array();
        foreach($permission_role_array as $value)
            $permission_role_insert[] = array('permission_id' => $value,'role_id' => $default_role->id);
        \DB::table('permission_role')->insert($permission_role_insert);

        $menus = \App\Menu::pluck('name')->all();
        $config_menus = config('menu');
        $menu_items = array_diff($config_menus, $menus);
        foreach($menu_items as $menu_item){
            $db_menu = \App\Menu::firstOrNew(['name' => $menu_item]);
            if(!isset($db_menu->id))
                $db_menu->visible = 1;
            $db_menu->name = $menu_item;
            $db_menu->save();
        }
        $menus = \App\Menu::all();

        $setup_guide = \App\Setup::pluck('module')->all();
        $setup = [];
        foreach(config('setup') as $key => $value)
            $setup[] = $key;

        $setup_array = array_diff($setup, $setup_guide);
        $setup_insert = array();
        foreach($setup_array as $value)
            $setup_insert[] = array('module' => $value,'completed' => ($value == 'installation') ? 1 : 0);
        if(count($setup_insert))
            \DB::table('setup')->insert($setup_insert);

        $setup_guide = setupGuide();

        $insert_template = array();
        foreach(config('template') as $key => $value){
            if(!\App\Template::where('slug',$key)->count()){
                $insert_template[] = array(
                    'is_default' => 1,
                    'name' => toWord($key),
                    'category' => isset($value['category']) ? $value['category'] : '',
                    'slug' => $key,
                    'subject' => isset($value['subject']) ? $value['subject'] : '',
                    'body' => view('emails.default.'.$key)->render()
                    );
            }
        }
        if(count($insert_template))
            \App\Template::insert($insert_template);
        $menu = '';
        $right_sidebar = 0;

        foreach(config('constant.social_login_provider') as $value){
            config([
                'services.'.$value.'.client_id' => config('config.'.$value.'_client_id'),
                'services.'.$value.'.client_secret' => config('config.'.$value.'_client_secret'),
                'services.'.$value.'.redirect' => config('config.'.$value.'_redirect'),
                ]);
        }

        config([
            'nexmo.api_key' => config('config.nexmo_api_key'),
            'nexmo.api_secret' => config('config.nexmo_api_secret')
            ]);

        config([
            'services.nexmo.key' => config('config.nexmo_api_key'),
            'services.nexmo.secret' => config('config.nexmo_api_secret'),
            'services.nexmo.sms_from' => config('config.nexmo_from_number')
            ]);

        config([
            'session.lifetime' => (config('config.session_lifetime')) ? : '120',
            'session.expire_on_close' => (config('config.session_expire_browser_close')) ? true : false,
            'auth.passwords.user.expire' => (config('config.reset_token_lifetime')) ? : '120',
            ]);

        config(['app.name' => config('config.application_name')]);

        if(isSecure())
            config(['config.allowed_upload_file' => 'pdf,rtf,txt,png,doc,docx,xls,xlsx,jpg,jpeg,odt,ods,odg,odp,odf,odb,pptx,ppt,pub']);

        $default_timezone = config('config.timezone_id') ? config('timezone.'.config('config.timezone_id')) : 'Asia/Kolkata';
        date_default_timezone_set($default_timezone);

        $default_localization = (Session::has('localization')) ? session('localization') : ((config('config.default_localization')) ? : 'en' );

        session(['localization' => $default_localization]);
        \App::setLocale($default_localization);

        $datatable_localization = (config('localization.'.$default_localization.'.datatable')) ? : 'English';
        $calendar_localization = (config('localization.'.$default_localization.'.calendar')) ? : 'en';
        $datepicker_localization = (config('localization.'.$default_localization.'.datepicker')) ? : 'en';
        $direction = (config('config.direction')) ? : 'ltr';
        view()->share(compact('direction','default_localization','datatable_localization','calendar_localization','datepicker_localization','menus','menu','setup_guide','right_sidebar'));

        return $next($request);
    }
}
