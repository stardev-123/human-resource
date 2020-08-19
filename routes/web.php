<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/','Auth\LoginController@showLoginForm');
Route::get('/under-maintenance','MiscController@maintenance');
Route::get('/terms-and-conditions','MiscController@tnc');
Route::post('/pusher-credential','NotificationController@credential');
Route::get('/whats-new',function(){
	return view('whats_new');
});
Auth::routes();

Route::get('/jobs','JobController@jobs');
Route::get('/jobs/{slug}/{id}','JobController@detail');
Route::post('/job-application',array('as' => 'job-application.store','uses' => 'JobApplicationController@store'));
Route::post('/clock/in', array('as' => 'clock.in', 'uses' => 'ClockController@in'));
Route::post('/clock/out', array('as' => 'clock.out', 'uses' => 'ClockController@out'));

Route::group(['middleware' => ['upload']],function(){
	Route::post('/upload','UploadController@upload');
	Route::post('/upload-list','UploadController@uploadList');
	Route::post('/upload-delete','UploadController@uploadDelete');
	Route::post('/upload-temp-delete','UploadController@uploadTempDelete');
});

Route::group(['middleware' => ['guest']], function () {
	Route::get('/resend-activation','Auth\ActivateController@resendActivation');
	Route::post('/resend-activation',array('as' => 'user.resend-activation','uses' => 'Auth\ActivateController@postResendActivation'));
	Route::get('/activate-account/{token}','Auth\ActivateController@activateAccount');

	Route::get('/auth/{provider}', 'SocialLoginController@providerRedirect');
    Route::get('/auth/{provider}/callback', 'SocialLoginController@providerRedirectCallback');

	Route::get('/verify-purchase', 'AccountController@verifyPurchase');
	Route::post('/verify-purchase', 'AccountController@postVerifyPurchase');
	Route::resource('/install', 'AccountController',['only' => ['index', 'store']]);
	Route::get('/update','AccountController@updateApp');
	Route::post('/update',array('as' => 'update-app','uses' => 'AccountController@postUpdateApp'));
});

Route::group(['middleware' => ['auth','web','account']],function(){
	Route::get('/verify-security','Auth\TwoFactorController@verifySecurity');
	Route::post('/verify-security',array('as' => 'verify-security','uses' => 'Auth\TwoFactorController@postVerifySecurity'));
});

Route::group(['middleware' => ['auth','web','account','lock_screen']], function () {

	Route::get('/demo-real-time-notification','HomeController@demoRealTimeNotification');
	Route::post('/generate-real-time-notification','HomeController@generateRealTimeNotification');

	Route::get('/home', 'HomeController@index');
	Route::post('/sidebar', 'HomeController@sidebar');
	Route::post('/setup-guide',array('as' => 'setup-guide','uses' => 'ConfigurationController@setupGuide'));
	Route::post('/filter','HomeController@filter');
	Route::post('/calendar-events','HomeController@calendarEvents');
	Route::post('/load-notification','NotificationController@loadNotification');
	Route::post('/check-notification','NotificationController@checkNotification');
	Route::get('/notification','NotificationController@index');
	Route::post('/notification/lists','NotificationController@lists');
	Route::post('/notification/mark-as-read','NotificationController@markAsRead');

	Route::get('/release-license','AccountController@releaseLicense');
	Route::get('/check-update','AccountController@checkUpdate');

	Route::post('/login-as-user','UserController@loginAsUser');
	Route::post('/login-return','UserController@loginReturn');

	Route::group(['middleware' => ['permission:manage-configuration']], function() {
		Route::get('/configuration', 'ConfigurationController@index');
		Route::post('/configuration',array('as' => 'configuration.store','uses' => 'ConfigurationController@store'));
		Route::post('/configuration-logo',array('as' => 'configuration.logo','uses' => 'ConfigurationController@logo'));
		Route::post('/configuration-mail',array('as' => 'configuration.mail','uses' => 'ConfigurationController@mail'));
		Route::post('/configuration-sms',array('as' => 'configuration.sms','uses' => 'ConfigurationController@sms'));
		Route::post('/configuration-menu', array('as' => 'configuration.menu','uses' => 'ConfigurationController@menu'));
		Route::post('/configuration-upload', array('as' => 'configuration.upload','uses' => 'ConfigurationController@upload'));
		Route::post('/configuration-api',array('as' => 'configuration.api','uses' => 'ConfigurationController@api'));

		Route::model('currency','\App\Currency');
		Route::post('/currency/lists','CurrencyController@lists');
		Route::resource('/currency', 'CurrencyController');

		Route::model('document_type','\App\DocumentType');
		Route::post('/document-type/lists','DocumentTypeController@lists');
		Route::resource('/document-type', 'DocumentTypeController');

		Route::model('award_category','\App\AwardCategory');
		Route::post('/award-category/lists','AwardCategoryController@lists');
		Route::resource('/award-category', 'AwardCategoryController');

		Route::model('contract_type','\App\ContractType');
		Route::post('/contract-type/lists','ContractTypeController@lists');
		Route::resource('/contract-type', 'ContractTypeController');

		Route::model('expense_head','\App\ExpenseHead');
		Route::post('/expense-head/lists','ExpenseHeadController@lists');
		Route::resource('/expense-head', 'ExpenseHeadController');

		Route::model('leave_type','\App\LeaveType');
		Route::post('/leave-type/lists','LeaveTypeController@lists');
		Route::resource('/leave-type', 'LeaveTypeController');

		Route::model('salary_head','\App\SalaryHead');
		Route::post('/salary-head/lists','SalaryHeadController@lists');
		Route::resource('/salary-head', 'SalaryHeadController');

		Route::model('task_category','\App\TaskCategory');
		Route::post('/task-category/lists','TaskCategoryController@lists');
		Route::resource('/task-category', 'TaskCategoryController');

		Route::model('task_priority','\App\TaskPriority');
		Route::post('/task-priority/lists','TaskPriorityController@lists');
		Route::resource('/task-priority', 'TaskPriorityController');

		Route::model('ticket_category','\App\TicketCategory');
		Route::post('/ticket-category/lists','TicketCategoryController@lists');
		Route::resource('/ticket-category', 'TicketCategoryController');

		Route::model('ticket_priority','\App\TicketPriority');
		Route::post('/ticket-priority/lists','TicketPriorityController@lists');
		Route::resource('/ticket-priority', 'TicketPriorityController');

		Route::model('education_level','\App\EducationLevel');
		Route::post('/education-level/lists','EducationLevelController@lists');
		Route::resource('/education-level', 'EducationLevelController');

		Route::model('qualification_language','\App\QualificationLanguage');
		Route::post('/qualification-language/lists','QualificationLanguageController@lists');
		Route::resource('/qualification-language', 'QualificationLanguageController');

		Route::model('qualification_skill','\App\QualificationSkill');
		Route::post('/qualification-skill/lists','QualificationSkillController@lists');
		Route::resource('/qualification-skill', 'QualificationSkillController');

		Route::get('/upload-log','BulkUploadController@log');
		Route::post('/upload-log/lists','BulkUploadController@lists');
		Route::get('/upload-log/{id}','BulkUploadController@download');
	});

	Route::group(['middleware' => ['permission:manage-localization']], function () {
		Route::post('/localization/lists','LocalizationController@lists');
		Route::resource('/localization', 'LocalizationController');
		Route::post('/localization/addWords',array('as'=>'localization.add-words','uses'=>'LocalizationController@addWords'));
		Route::patch('/localization/plugin/{locale}',array('as'=>'localization.plugin','uses'=>'LocalizationController@plugin'));
		Route::patch('/localization/updateTranslation/{id}', ['as' => 'localization.update-translation','uses' => 'LocalizationController@updateTranslation']);
	});

	Route::group(['middleware' => ['permission:manage-backup']], function() {
		Route::model('backup','\App\Backup');
		Route::post('/backup/lists','BackupController@lists');
		Route::resource('/backup', 'BackupController',['only' => ['index','show','store','destroy']]);
		Route::get('/backup/{id}/download','BackupController@download');
	});

	Route::group(['middleware' => ['permission:manage-ip-filter']], function() {
		Route::model('ip_filter','\App\IpFilter');
		Route::post('/ip-filter/lists','IpFilterController@lists');
		Route::resource('/ip-filter', 'IpFilterController');
	});

	Route::group(['middleware' => ['permission:manage-todo']], function() {
		Route::model('todo','\App\Todo');
		Route::resource('/todo', 'TodoController');
	});

	Route::group(['middleware' => ['permission:manage-template']], function() {
		Route::model('template','\App\Template');
		Route::post('/template/lists','TemplateController@lists');
		Route::resource('/template', 'TemplateController');
	});
	Route::post('/template/content','TemplateController@content',['middleware' => ['permission:enable_email_template']]);

	Route::group(['middleware' => ['permission:manage-email-log']], function () {
		Route::model('email','\App\Email');
		Route::post('/email/lists','EmailController@lists');
		Route::resource('/email', 'EmailController',['only' => ['index','show']]);
	});

	Route::group(['middleware' => ['permission:manage-custom-field']], function() {
		Route::model('custom_field','\App\CustomField');
		Route::post('/custom-field/lists','CustomFieldController@lists');
		Route::resource('/custom-field', 'CustomFieldController');
	});

	Route::group(['middleware' => ['permission:manage-message']], function() {
		Route::get('/message', 'MessageController@index');
		Route::post('/load-message','MessageController@load');
		Route::post('/message/{type}/lists','MessageController@lists');
		Route::get('/message/forward/{uuid}','MessageController@forward');
		Route::post('/message', ['as' => 'message.store', 'uses' => 'MessageController@store']);
		Route::post('/message-reply/{id}', ['as' => 'message.reply', 'uses' => 'MessageController@reply']);
		Route::post('/message-forward/{uuid}', ['as' => 'message.post-forward', 'uses' => 'MessageController@postForward']);
		Route::get('/message/{id}/download','MessageController@download');
		Route::post('/message/starred','MessageController@starred');
		Route::get('/message/{uuid}', array('as' => 'message.view', 'uses' => 'MessageController@view'));
		Route::delete('/message/{id}/trash', array('as' => 'message.trash', 'uses' => 'MessageController@trash'));
		Route::post('/message/restore', array('as' => 'message.restore', 'uses' => 'MessageController@restore'));
		Route::delete('/message/{id}/delete', array('as' => 'message.destroy', 'uses' => 'MessageController@destroy'));
	});

	Route::group(['middleware' => ['permission:manage-shift']], function() {
		Route::model('shift','\App\Shift');
		Route::post('/shift/lists','ShiftController@lists');
		Route::resource('/shift', 'ShiftController');
	});

	Route::group(['middleware' => ['permission:manage-role']], function() {
		Route::model('role','\App\Role');
		Route::post('/role/lists','RoleController@lists');
		Route::resource('/role', 'RoleController');
	});

	Route::group(['middleware' => ['permission:manage-permission']], function() {
		Route::model('permission','\App\Permission');
		Route::post('/permission/lists','PermissionController@lists');
		Route::resource('/permission', 'PermissionController');
		Route::get('/save-permission','PermissionController@permission');
		Route::post('/save-permission',array('as' => 'permission.save-permission','uses' => 'PermissionController@savePermission'));
	});

	Route::model('chat','\App\Chat');
	Route::resource('/chat', 'ChatController',['only' => 'store']);
	Route::post('/fetch-chat','ChatController@index');

	Route::get('/lock','HomeController@lock');
	Route::post('/lock',array('as' => 'unlock','uses' => 'HomeController@unlock'));

	Route::group(['middleware' => ['feature_available:enable_activity_log']],function() {
		Route::get('/activity-log','HomeController@activityLog');
		Route::post('/activity-log/lists','HomeController@activityLogList');
	});

	Route::get('/change-localization/{locale}','LocalizationController@changeLocalization',['middleware' => ['permission:change-localization']]);

	Route::model('user','\App\User');
	Route::get('/profile/{username?}','UserController@profile');
	Route::post('/user/lists','UserController@lists');
	Route::get('/employment-report','UserController@employmentReport');
	Route::post('/employment-report/lists','UserController@employmentReportLists');
	Route::post('/employment-report-graph','UserController@employmentReportGraph');
	Route::resource('/user', 'UserController',['except' => ['store','edit','show']]);
	Route::post('/user/profile-update/{id}',array('as' => 'user.profile-update','uses' => 'UserController@profileUpdate'));
	Route::post('/user/social-update/{id}',array('as' => 'user.social-update','uses' => 'UserController@socialUpdate'));
	Route::post('/user/detail','UserController@detail');
	Route::post('/user/avatar/{id}',array('as' => 'user.avatar','uses' => 'UserController@avatar'));
	Route::get('/user/{id}/profile-setup','UserController@profileSetup');
	Route::post('/change-user-status','UserController@changeStatus');
	Route::post('/force-change-user-password/{user_id}',array('as' => 'user.force-change-password','uses' => 'UserController@forceChangePassword'));
	Route::get('/user/{username}','UserController@show');
	Route::get('/change-password', 'UserController@changePassword');
	Route::post('/change-password',array('as'=>'change-password','uses' =>'UserController@doChangePassword'));
	Route::post('/user/email/{id}',array('as' => 'user.email', 'uses' => 'UserController@email'));

	Route::model('department','\App\Department');
	Route::post('/department/lists','DepartmentController@lists');
	Route::resource('/department', 'DepartmentController');

	Route::model('designation','\App\Designation');
	Route::post('/designation/lists','DesignationController@lists');
	Route::resource('/designation', 'DesignationController');
	Route::post('/designation/hierarchy','DesignationController@hierarchy');

	Route::model('location','\App\Location');
	Route::post('/location/lists','LocationController@lists');
	Route::resource('/location', 'LocationController');
	Route::post('/location/hierarchy','LocationController@hierarchy');

	Route::model('user_contact','\App\UserContact');
	Route::post('/user-contact/lists','UserContactController@lists');
	Route::post('/user-contact/toggle-lock','UserContactController@toggleLock');
	Route::resource('/user-contact', 'UserContactController');
	Route::post('/user-contact/{id}',array('uses' => 'UserContactController@store','as' => 'user-contact.store'));

	Route::model('user_bank_account','\App\UserBankAccount');
	Route::post('/user-bank-account/lists','UserBankAccountController@lists');
	Route::post('/user-bank-account/toggle-lock','UserBankAccountController@toggleLock');
	Route::resource('/user-bank-account', 'UserBankAccountController');
	Route::post('/user-bank-account/{id}',array('uses' => 'UserBankAccountController@store','as' => 'user-bank-account.store'));

	Route::model('user_qualification','\App\UserQualification');
	Route::post('/user-qualification/lists','UserQualificationController@lists');
	Route::post('/user-qualification/toggle-lock','UserQualificationController@toggleLock');
	Route::resource('/user-qualification', 'UserQualificationController');
	Route::post('/user-qualification/{id}',array('uses' => 'UserQualificationController@store','as' => 'user-qualification.store'));
	Route::get('/user-qualification/{id}/download','UserQualificationController@download');

	Route::model('user_employment','\App\UserEmployment');
	Route::post('/user-employment/lists','UserEmploymentController@lists');
	Route::resource('/user-employment', 'UserEmploymentController');
	Route::post('/user-employment/{id}',array('uses' => 'UserEmploymentController@store','as' => 'user-employment.store'));

	Route::model('user_document','\App\UserDocument');
	Route::post('/user-document/lists','UserDocumentController@lists');
	Route::post('/user-document/toggle-lock','UserDocumentController@toggleLock');
	Route::resource('/user-document', 'UserDocumentController');
	Route::post('/user-document/{id}',array('uses' => 'UserDocumentController@store','as' => 'user-document.store'));
	Route::get('/user-document/{id}/download','UserDocumentController@download');

	Route::model('user_designation','\App\UserDesignation');
	Route::post('/user-designation/lists','UserDesignationController@lists');
	Route::resource('/user-designation', 'UserDesignationController');
	Route::post('/user-designation/{id}',array('uses' => 'UserDesignationController@store','as' => 'user-designation.store'));

	Route::model('user_location','\App\UserLocation');
	Route::post('/user-location/lists','UserLocationController@lists');
	Route::resource('/user-location', 'UserLocationController');
	Route::post('/user-location/{id}',array('uses' => 'UserLocationController@store','as' => 'user-location.store'));

	Route::model('user_contract','\App\UserContract');
	Route::post('/user-contract/lists','UserContractController@lists');
	Route::resource('/user-contract', 'UserContractController');
	Route::post('/user-contract/{id}',array('uses' => 'UserContractController@store','as' => 'user-contract.store'));
	Route::get('/user-contract/{id}/download','UserContractController@download');

	Route::model('user_shift','\App\UserShift');
	Route::post('/user-shift/lists','UserShiftController@lists');
	Route::resource('/user-shift', 'UserShiftController',['except' => ['create','store']]);
	Route::post('/user-shift/{id}',array('uses' => 'UserShiftController@store','as' => 'user-shift.store'));
	Route::get('/user-shift/{id}/create','UserShiftController@create');

	Route::model('user_leave','\App\UserLeave');
	Route::post('/user-leave/lists','UserLeaveController@lists');
	Route::resource('/user-leave', 'UserLeaveController',['except' => ['create','store']]);
	Route::post('/user-leave/{id}',array('uses' => 'UserLeaveController@store','as' => 'user-leave.store'));

	Route::model('user_salary','\App\UserSalary');
	Route::post('/user-salary/lists','UserSalaryController@lists');
	Route::resource('/user-salary', 'UserSalaryController',['except' => ['create','store']]);
	Route::post('/user-salary/{id}',array('uses' => 'UserSalaryController@store','as' => 'user-salary.store'));

	Route::model('user_qualification','\App\UserQualification');
	Route::post('/user-qualification/lists','UserQualificationController@lists');
	Route::resource('/user-qualification', 'UserQualificationController',['except' => ['create','store']]);
	Route::post('/user-qualification/{id}',array('uses' => 'UserQualificationController@store','as' => 'user-qualification.store'));

	Route::model('user_experience','\App\UserExperience');
	Route::post('/user-experience/lists','UserExperienceController@lists');
	Route::post('/user-experience/toggle-lock','UserExperienceController@toggleLock');
	Route::resource('/user-experience', 'UserExperienceController',['except' => ['create','store']]);
	Route::post('/user-experience/{id}',array('uses' => 'UserExperienceController@store','as' => 'user-experience.store'));

	Route::model('daily_report','\App\DailyReport');
	Route::post('/daily-report/lists','DailyReportController@lists');
	Route::post('/daily-report/toggle-lock','DailyReportController@toggleLock');
	Route::resource('/daily-report', 'DailyReportController');
	Route::get('/daily-report/{id}/download','DailyReportController@download');

	Route::group(['middleware' => ['permission:manage-holiday']], function() {
		Route::model('holiday','\App\Holiday');
		Route::post('/holiday/lists','HolidayController@lists');
		Route::resource('/holiday', 'HolidayController');
	});

	Route::post('/upload-column/{module}',array('as' => 'upload-column','uses' => 'BulkUploadController@uploadColumn'));
	Route::post('/attendance/bulk-upload',array('as' => 'attendance.bulk-upload','uses' => 'ClockController@bulkUpload'));

	Route::model('clock','\App\Clock');
	Route::resource('/clock', 'ClockController');
	Route::post('/clock/button','ClockController@clockButton');
	Route::post('/clock/lists','ClockController@lists');
	Route::post('/attendance/lists','ClockController@attendanceLists');
	Route::get('/daily-shift','ClockController@dailyShift');
	Route::post('/daily-shift/lists','ClockController@dailyShiftLists');
	Route::get('/date-wise-shift','ClockController@dateWiseShift');
	Route::post('/date-wise-shift/lists','ClockController@dateWiseShiftLists');
	Route::get('/daily-attendance','ClockController@dailyAttendance');
	Route::post('/daily-attendance/lists','ClockController@dailyAttendanceLists');
	Route::get('/date-wise-attendance','ClockController@dateWiseAttendance');
	Route::post('/date-wise-attendance/lists','ClockController@dateWiseAttendanceLists');
	Route::get('/user-wise-summary-attendance','ClockController@userWiseSummaryAttendance');
	Route::post('/user-wise-summary-attendance/lists','ClockController@userWiseSummaryAttendanceLists');
	Route::get('/date-wise-summary-attendance','ClockController@dateWiseSummaryAttendance');
	Route::post('/date-wise-summary-attendance/lists','ClockController@dateWiseSummaryAttendanceLists');
	Route::get('/update-attendance','ClockController@updateAttendance');
	Route::post('/update-attendance',array('as' => 'clock.update-attendance','uses' => 'ClockController@updateAttendance'));
	Route::post('/clock/{user_id}/{date}',array('as' => 'update-clock','uses' => 'ClockController@clock'));
	Route::post('/clock/{user_id}/{date}/{clock_id?}',array('as' => 'update-clock','uses' => 'ClockController@clock'));

	Route::get('/payroll','PayrollController@index');
	Route::post('/payroll/lists','PayrollController@lists');
	Route::get('/payroll/create','PayrollController@create');
	Route::post('/payroll/create',array('as' => 'payroll.create','uses' => 'PayrollController@create'));
	Route::post('/payroll',array('as' => 'payroll.store','uses' => 'PayrollController@store'));
	Route::get('/payroll/{id}','PayrollController@show');
	Route::delete('/payroll/{id}',array('uses' => 'PayrollController@destroy', 'as' => 'payroll.destroy'));
	Route::get('/payroll/{id}/edit','PayrollController@edit');
	Route::patch('/payroll/{id}/update',array('as' => 'payroll.update','uses' => 'PayrollController@update'));
	Route::get('/payroll/{id}/generate/{action}','PayrollController@generate');
	Route::get('/payroll/create/multiple','PayrollController@createMultiple');
	Route::post('/payroll/create/multiple',array('as' => 'payroll.create-multiple','uses' => 'PayrollController@postCreateMultiple'));
	Route::post('/payroll-monthly-report-graph','PayrollController@monthlyReportGraph');

	Route::model('award','\App\Award');
	Route::post('/award/lists','AwardController@lists');
	Route::resource('/award', 'AwardController');
	Route::get('/award/{id}/download','AwardController@download');

	Route::model('client','\App\Client');
	Route::post('/client/lists','ClientController@lists');
	//Route::post('/client/form2',array('as' => 'client.form2','uses' => 'ClientController@updateClient'));
	Route::resource('/client', 'ClientController');
	Route::get('/client/{id}/download','ClientController@download');

	Route::model('library','\App\Library');
	Route::post('/library/lists','LibraryController@lists');
	Route::resource('/library', 'LibraryController');
	Route::get('/library/{id}/download','LibraryController@download');

    Route::model('announcement','\App\Announcement');
	Route::post('/announcement/lists','AnnouncementController@lists');
	Route::resource('/announcement', 'AnnouncementController');
	Route::get('/announcement/{id}/download','AnnouncementController@download');

	Route::model('ticket','\App\Ticket');
	Route::post('/ticket/lists','TicketController@lists');
	Route::resource('/ticket', 'TicketController',['except' => ['show']]);
	Route::get('/ticket/{id}/download','TicketController@download');
	Route::post('/ticket/detail','TicketController@detail');
	Route::post('/ticket/reply','TicketController@reply');
	Route::post('/ticket/{uuid}/reply',array('as' => 'ticket.store-reply','uses' => 'TicketController@storeReply'));
	Route::get('/ticket/{uuid}','TicketController@show');
	Route::get('/ticket-reply/{id}/download','TicketController@downloadReply');

	Route::model('expense','\App\Expense');
	Route::post('/expense/lists','ExpenseController@lists');
	Route::post('/expense/detail','ExpenseController@detail');
	Route::resource('/expense', 'ExpenseController',['except' => ['show']]);
	Route::get('/expense/{uuid}','ExpenseController@show');
	Route::get('/expense/{id}/download','ExpenseController@download');
	Route::post('/expense/{id}/update-status',array('as' => 'expense.update-status','uses' => 'ExpenseController@updateStatus'));
	Route::post('/expense-status-detail','ExpenseController@expenseStatusDetail');
	Route::post('/expense-monthly-report-graph','ExpenseController@monthlyReportGraph');

	Route::model('leave','\App\Leave');
	Route::post('/leave/lists','LeaveController@lists');
	Route::get('/leave-balance-report','LeaveController@balanceReport');
	Route::post('/leave-balance-report/lists','LeaveController@balanceReportLists');
	Route::get('/date-wise-leave-report','LeaveController@dateWiseReport');
	Route::post('/date-wise-leave-report/lists','LeaveController@dateWiseReportLists');
	Route::resource('/leave', 'LeaveController',['except' => ['show']]);
	Route::get('/leave/{uuid}','LeaveController@show');
	Route::get('/leave/{id}/download','LeaveController@download');
	Route::post('/leave/detail','LeaveController@detail');
	Route::post('/leave/{id}/update-status',array('as' => 'leave.update-status','uses' => 'LeaveController@updateStatus'));
	Route::post('/leave/current-status','LeaveController@currentStatus');
	Route::post('/leave-status-detail','LeaveController@leaveStatusDetail');

	Route::group(['middleware' => ['permission:manage-job']], function() {
		Route::model('job','\App\Job');
		Route::post('/job/lists','JobController@lists');
		Route::resource('/job', 'JobController');
		Route::get('/job/{id}/download','JobController@download');

		Route::model('job_application','\App\JobApplication');
		Route::post('/job-application/lists','JobApplicationController@lists');
		Route::resource('/job-application', 'JobApplicationController',['except' => ['store']]);
		Route::get('/job-application/{id}/download','JobApplicationController@download');
		Route::post('/job-application/{id}/update-status',array('as' => 'job-application.update-status','uses' => 'JobApplicationController@updateStatus'));
		Route::post('/job-application/list-status','JobApplicationController@listStatus');
		Route::post('/job-application/detail','JobApplicationController@detail');
		Route::delete('/job-application/{id}/delete-status',array('as' => 'job-application-status-detail.destroy','uses' => 'JobApplicationController@destroyStatus'));
	});

	//////// Task Route Start /////////////////
	Route::model('task','\App\Task');
	Route::post('/task/lists','TaskController@lists');
	Route::post('/task/fetch','TaskController@fetch');
	Route::post('/task/top-chart','TaskController@topChart');
	Route::resource('/task', 'TaskController',['except' => ['show']]);
	Route::get('/task/{uuid}','TaskController@show');
	Route::post('/task-detail','TaskController@detail');
	Route::post('/task-description','TaskController@description');
	Route::post('/task-starred','TaskController@starred');
	Route::post('/task-activity','TaskController@activity');
	Route::post('/task-comment','TaskController@comment');
	Route::post('/task/{id}/progress',array('as' => 'task.progress','uses' => 'TaskController@progress'));
	Route::post('/task-rating-type','TaskController@ratingType');
	Route::get('/task/{id}/download','TaskController@download');

	Route::post('/task/{id}/sub-task',array('as' => 'task.add-sub-task','uses' => 'SubTaskController@store'));
	Route::post('/sub-task/lists','SubTaskController@lists');
	Route::get('/sub-task/{id}/edit','SubTaskController@edit');
	Route::patch('/sub-task/{id}',array('as' => 'sub-task.update','uses' => 'SubTaskController@update'));
	Route::delete('/sub-task/{id}',array('as' => 'sub-task.destroy','uses' => 'SubTaskController@destroy'));

	Route::get('/task-rating/{task_id}/{user_id}','TaskController@rating');
	Route::post('/task-rating/lists','TaskController@listRating');
	Route::post('/task-rating/{task_id}/{user_id}',['as' => 'task.store-rating', 'uses' => 'TaskController@storeRating']);
	Route::post('/task-rating-destroy','TaskController@destroyTaskRating');

	Route::post('/sub-task-rating/lists','TaskController@listSubTaskRating');
	Route::get('/sub-task-rating/{task_id}/{user_id}/show','TaskController@showSubTaskRating');
	Route::get('/sub-task-rating/{task_id}/{user_id}','TaskController@subTaskRating');
	Route::post('/sub-task-rating-destroy','TaskController@destroySubTaskRating');
	Route::get('/user-task-rating','TaskController@userTaskRating');
	Route::post('/user-task-rating/lists','TaskController@userTaskRatingLists');
	Route::get('/user-task-summary','TaskController@userTaskSummary');
	Route::post('/user-task-summary/lists','TaskController@userTaskSummaryLists');

	Route::post('/task-note/{id}',array('uses' => 'TaskNoteController@store','as' => 'task-note.store'));

	Route::post('/task-comment/{id}',array('uses' => 'TaskCommentController@store','as' => 'task-comment.store'));
	Route::delete('/task-comment/{id}',array('uses' => 'TaskCommentController@destroy','as' => 'task-comment.destroy'));

	Route::post('/task-attachment/lists','TaskAttachmentController@lists');
	Route::post('/task-attachment/{id}',array('uses' => 'TaskAttachmentController@store','as' => 'task-attachment.store'));
	Route::delete('/task-attachment/{id}',array('uses' => 'TaskAttachmentController@destroy','as' => 'task-attachment.destroy'));
	Route::get('/task-attachment/{id}/download','TaskAttachmentController@download');

	Route::post('/task-sign-off-request','TaskController@signOffRequest');
	Route::post('/task-sign-off-request-action','TaskController@signOffRequestAction');


	//////// Task Route End /////////////////
});

Route::get('/api/clock-in/{auth_token?}/{emp_code?}','ApiController@clockIn');
Route::get('/api/clock-out/{auth_token?}/{emp_code?}','ApiController@clockOut');
