<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ChatRequest;
use App\Chat;
use Entrust;

Class ChatController extends Controller{

    public function store(ChatRequest $request,Chat $chat){
    	$chat->message = $request->input('message');
    	$chat->user_id = \Auth::user()->id;
    	$chat->save();
    	
        return response()->json(['message' => trans('messages.message').' '.trans('messages.posted'), 'status' => 'success']);
    }

    public function index(){
    	$chats = Chat::orderBy('id','desc')->get()->take(50);
    	$chats = $chats->sortBy('id');

    	return view('chat.fetch',compact('chats'))->render();
    }
}