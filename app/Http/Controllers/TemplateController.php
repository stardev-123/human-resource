<?php
namespace App\Http\Controllers;
use Entrust;
use File;
use Mail;
use Illuminate\Http\Request;
use Validator;
use App\Template;

Class TemplateController extends Controller{
    use BasicController;

	public function __construct()
	{
		$this->middleware('feature_available:enable_email_template');
	}

	public function index(Template $template){

		$table_data['template-table'] = array(
			'source' => 'template',
			'title' => trans('messages.template').' '.trans('messages.list'),
			'id' => 'template_table',
			'data' => array(
        		trans('messages.option'),
        		trans('messages.name'),
        		trans('messages.category'),
        		trans('messages.subject')
        		)
			);

		$category = array();

		foreach(config('template-field') as $key => $value)
			$category[$key] = ucwords($key);

		$assets = ['summernote','datatable'];
		return view('template.index',compact('table_data','assets','category'));
	}

	public function lists(Request $request){

		$templates = Template::all();
        $rows = array();

        foreach($templates as $template){

			$rows[] = array(
				'<div class="btn-group btn-group-xs">'.
				'<a href="#" data-href="/template/'.$template->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> '.
				(!$template->is_default ? delete_form(['template.destroy',$template->id]) : '').
				'</div>',
				$template->name,
				toWord($template->category),
				$template->subject
				);
        }
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function create(){
		
	}

	public function edit(Template $template){
		return view('template.edit',compact('template'));
	}

	public function store(Request $request, Template $template){

        $validation = Validator::make($request->all(),[
            'category' => 'required',
            'name' => 'required|unique:templates,name'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

		$data = $request->all();
		$data['slug'] = createSlug($request->input('name'));
		$template->fill($data)->save();

		$template->body = view('emails.default.default')->render();
		$template->subject = 'Email Subject';
		$template->save();
		$this->logActivity(['module' => 'template', 'module_id' => $template->id, 'activity' => 'added']);

        return response()->json(['message' => trans('messages.template').' '.trans('messages.added'), 'status' => 'success']);
	}
	
	public function content(Request $request){
		$template = Template::find($request->input('template_id'));
		$user = ($request->has('user_id')) ? \App\User::find($request->input('user_id')) : null;

		if(!$template || (!$user))
	        return response()->json(['status' => 'error']);

		$mail_data = $this->templateContent(['slug' => $template->slug,'user' => $user]);

		if(count($mail_data))
        	$response = ['body' => $mail_data['body'], 'subject' => $mail_data['subject'],'status' => 'success'];
	    else    
	        $response = ['status' => 'error'];  
        return response()->json($response);
	}

	public function update(Request $request,Template $template){

        $validation = Validator::make($request->all(),[
            'subject' => 'required',
            'body' => 'required'
        ]);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        $template->subject = $request->input('subject');
        $template->body = clean($request->input('body'),'custom');

        $template->save();

		$this->logActivity(['module' => 'template', 'module_id' => $template->id, 'activity' => 'updated']);

        return response()->json(['message' => trans('messages.template').' '.trans('messages.saved'), 'status' => 'success']);
	}

	public function destroy(Template $template,Request $request){

		if($template->is_default)
            return response()->json(['message' => trans('messages.template_cannot_delete'), 'status' => 'error']);

		$this->logActivity(['module' => 'template', 'module_id' => $template->id, 'activity' => 'deleted']);
        $template->delete();

        return response()->json(['message' => trans('messages.template').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>