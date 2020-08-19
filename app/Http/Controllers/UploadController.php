<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Entrust;
use App\Upload;

Class UploadController extends Controller{
    use BasicController;

	public function upload(Request $request){

        if(!getMode())
            return response()->json(['error' => trans('messages.disable_message')]);

        $user_id = (\Auth::check()) ? \Auth::user()->id : null;
        $client_id = ($request->input('client_id')) ? $request->input('client_id') : null;

    	$extension = $request->file('file')->extension();

        $size = $request->file('file')->getSize();

        $allowed_file_extensions = config('upload.'.$request->input('module').'.extension') ? : config('config.allowed_upload_file');

    	if(!in_array($extension, explode(',',$allowed_file_extensions)))
    		return response()->json(['error' => trans('messages.file_extension_not_allowed_to_upload')]);

        if($size > config('config.max_file_size_upload')*1024*1024)
            return response()->json(['error' => trans('messages.file_size_greater_than_max_allowed_file_size')]);

        $max_upload = config('upload.'.$request->input('module').'.limit') ? : 1;

        if($client_id == null) {
            if(!$request->input('module_id'))
                $existing_upload = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->whereUserId($user_id)->whereIsTempDelete(0)->count();
            else
            $existing_upload = Upload::where(function($query) use($request,$user_id) {
                $query->whereModule($request->input('module'))->whereUploadKey($request->input('key'))->whereUserId($user_id);
            })->orWhere(function($query1) use($request){
                $query1->whereModule($request->input('module'))->whereModuleId($request->input('module_id'))->whereIsTempDelete(0);
            })->count();

            if($existing_upload >= $max_upload)
            return response()->json(['error' => trans('messages.max_file_allowed',['attribute' => $max_upload])]);
            
            if ($client_id == null) 
                $filename_existing_upload = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->whereUserId($user_id)->whereUserFilename($request->file('file')->getClientOriginalName())->count();
            
            if ($client_id != null) 
                $filename_existing_upload = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->where($client_id)->whereUserFilename($request->file('file')->getClientOriginalName())->count();
            

            if($filename_existing_upload)
                return response()->json(['error' => trans('messages.file_already_uploaded')]);

            $filename = str_random(50);
            $file = $request->file('file')->storeAs('temp_attachments',$filename.".".$extension);
            $upload = new Upload;
            $upload->module = $request->input('module');
            $upload->upload_key = $request->input('key');
            $upload->attachments = $filename.".".$extension;
            $upload->user_filename = $request->file('file')->getClientOriginalName();
            $upload->user_id = $user_id;
            $upload->client_id = $client_id;
            $upload->save();

            return response()->json(['message' => trans('messages.file').' '.trans('messages.uploaded'),'status' => 'success','key' => $upload->upload_key]);
        }
        if($client_id != null) {
            if(!$request->input('module_id'))
                $existing_upload = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->where($client_id)->whereIsTempDelete(0)->count();
            else
            $existing_upload = Upload::where(function($query) use($request,$client_id) {
                $query->whereModule($request->input('module'))->whereUploadKey($request->input('key'))->where($client_id);
            })->orWhere(function($query1) use($request){
                $query1->whereModule($request->input('module'))->whereModuleId($request->input('module_id'))->whereIsTempDelete(0);
            })->count();

            if($existing_upload >= $max_upload)
            return response()->json(['error' => trans('messages.max_file_allowed',['attribute' => $max_upload])]);
            
            if ($client_id == null)
                $filename_existing_upload = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->whereUserId($user_id)->whereUserFilename($request->file('file')->getClientOriginalName())->count();
            
            if ($client_id != null)
                $filename_existing_upload = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->where($client_id)->whereUserFilename($request->file('file')->getClientOriginalName())->count();
            

            if($filename_existing_upload)
                return response()->json(['error' => trans('messages.file_already_uploaded')]);

            $filename = str_random(50);
            $file = $request->file('file')->storeAs('temp_attachments',$filename.".".$extension);
            $upload = new Upload;
            $upload->module = $request->input('module');
            $upload->upload_key = $request->input('key');
            $upload->attachments = $filename.".".$extension;
            $upload->user_filename = $request->file('file')->getClientOriginalName();
            $upload->user_id = $user_id;
            $upload->client_id = $client_id;
            $upload->save();

            return response()->json(['message' => trans('messages.file').' '.trans('messages.uploaded'),'status' => 'success','key' => $upload->upload_key]);
        }
	}

    public function uploadList(Request $request){

        $user_id = (\Auth::check()) ? \Auth::user()->id : null;
        $client_id = ($request->input('client_id')) ? $request->input('client_id') : null;

        if ($client_id == null) {
            $uploads = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->whereUserId($user_id)->get();
        }
        if ($client_id != null) {
            $uploads = Upload::whereModule($request->input('module'))->whereUploadKey($request->input('key'))->where($client_id)->get();
        }

        if(!$uploads->count())
            return;

        return view('upload.list',compact('uploads'))->render();
    }

    public function uploadDelete(Request $request){

        if(!getMode())
            return response()->json(['error' => trans('messages.disable_message')]);

        $upload = Upload::find($request->input('id'));

        if(!$upload)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        \Storage::delete('temp_attachments/'.$upload->attachments);

        $upload->delete();
        return response()->json(['message' => trans('messages.file').' '.trans('messages.deleted'),'status' => 'success']);
    }

    public function uploadTempDelete(Request $request){

        if(!getMode())
            return response()->json(['error' => trans('messages.disable_message')]);
        
        $upload = Upload::find($request->input('id'));

        if(!$upload)
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error']);

        $upload->is_temp_delete = 1;
        $upload->save();
        return response()->json(['message' => trans('messages.file').' '.trans('messages.deleted'),'status' => 'success']);
    }
}