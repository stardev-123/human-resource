<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use Entrust;
use App\Client;

class ClientController extends Controller
{
    use BasicController;

    protected $form = 'client-form';

    public function isAccessible($client)
    {
        $accessible = Client::all()->count();

        if ($accessible) {
            return 1;
        } else {
            return 0;
        }
    }

    public function index()
    {
        if (!Entrust::can('list-client')) {
            return redirect('/home')->withErrors(trans('messages.permission_denied'));
        }

        $data = array(
                    trans('messages.option'),
                    trans('messages.first').' '.trans('messages.name'),
                    trans('messages.last').' '.trans('messages.name'),
                    trans('messages.email'),
      	        		trans('messages.created_at')
                        );

        $data = putCustomHeads($this->form, $data);

        $table_data['client-table'] = array(
                'source' => 'client',
                'title' => trans('messages.client').' '.trans('messages.list'),
                'id' => 'client_table',
                'data' => $data
            );

            $assets = ['datatable','summernote','graph'];
            $menu = 'client';
            return view('client.index', compact('table_data', 'assets', 'menu'));
    }

    public function lists(Request $request)
    {
        if (!Entrust::can('list-client')) {
            return;
        }

        $clients = Client::all();

        $col_ids = getCustomColId($this->form);
        $values = fetchCustomValues($this->form);
        $rows = array();

        //This has to match the number of title arrays above
        foreach ($clients as $client) {
            $row = array(
              '<div class="btn-group btn-group-xs">'.
              '<a href="#" data-href="/client/'.$client->id.'" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-arrow-circle-right" data-toggle="tooltip" title="'.trans('messages.view').'"></i></a> '.
              ((Entrust::can('edit-client')) ? '<a href="#" data-href="/client/'.$client->id.'/edit" class="btn btn-xs btn-default" data-toggle="modal" data-target="#myModal"> <i class="fa fa-edit" data-toggle="tooltip" title="'.trans('messages.edit').'"></i></a> ' : '').
              ((Entrust::can('delete-client')) ? delete_form(['client.destroy',$client->id]) : '').
              '</div>',
              $client->first_name,
              $client->last_name,
              $client->email,
              showDateTime($client->created_at)
              );
            $id = $client->id;

            foreach ($col_ids as $col_id) {
                array_push($row, isset($values[$id][$col_id]) ? $values[$id][$col_id] : '');
            }
            $rows[] = $row;
        }

        $genders = array();
        foreach ($clients as $client) {
            if ($client->gender) {
                $genders[] = toWordTranslate($client->gender);
            }
        }

        $list['aaData'] = $rows;
        return json_encode($list);
    }

    public function edit(Client $client)
    {
        if (!Entrust::can('edit-client')) {
            return view('global.error', ['message' => trans('messages.permission_denied')]);
        }

        $uploads = editUpload2('client', $client->id);
        $custom_field_values = getCustomFieldValues($this->form, $client->id);

        return view('client.edit', compact('client', 'uploads', 'custom_field_values'));
    }

    public function show(Client $client)
    {
        if (!$this->isAccessible($client)) {
            return view('global.error', ['message' => trans('messages.permission_denied')]);
        }

        $uploads = editUpload2('client', $client->id);
        return view('client.show', compact('client', 'uploads'));
    }

    public function download($id)
    {
        $upload = \App\Upload::whereUuid($id)->whereModule('client')->whereStatus(1)->first();

        if (!$upload) {
            return redirect('/client')->withErrors(trans('messages.invalid_link'));
        }

        $client = Client::find($upload->module_id);

        if (!$client) {
            return redirect('/client')->withErrors(trans('messages.invalid_link'));
        }

        if (!$this->isAccessible($client)) {
            return redirect('/client')->withErrors(trans('messages.permission_denied'));
        }

        if (!\Storage::exists('attachments/'.$upload->attachments)) {
            return redirect('/client')->withErrors(trans('messages.file_not_found'));
        }

        $download_path = storage_path().config('constant.storage_root').'attachments/'.$upload->attachments;

        return response()->download($download_path, $upload->user_filename);
    }

    public function store(ClientRequest $request, Client $client)
    {
        if (!Entrust::can('create-client')) {
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);
        }

        $validation = validateCustomField($this->form, $request);

        if ($validation->fails()) {
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
        }

        $upload_validation = validateUpload2('client', $request);

        if ($upload_validation['status'] == 'error') {
            return response()->json($upload_validation);
        }

        $data = $request->all();
        $client->fill($data);
        $client->note = clean($request->input('note'), 'custom');
        $client->save();
        $this->logActivity(['module' => 'client','module_id' => $client->id,'activity' => 'added']);
        storeCustomField($this->form, $client->id, $data);
        storeUpload2('client', $client->id, $request);

        return response()->json(['message' => trans('messages.client').' '.trans('messages.added'), 'status' => 'success']);
    }

    public function update(ClientRequest $request, Client $client)
    {
        if (!Entrust::can('edit-client')) {
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);
        }

        $validation = validateCustomField($this->form, $request);

        if ($validation->fails()) {
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);
        }

        $upload_validation = updateUpload2('client', $client->id, $request);

        if ($upload_validation['status'] == 'error') {
            return response()->json($upload_validation);
        }
        //}
        $data = $request->all();
        $client->fill($data);
        $client->note = clean($request->input('note'), 'custom');
        if ($client->date_of_birth == '') {
            $client->date_of_birth = null;
        }
        if ($client->note == '') {
            $client->note = null;
        }

        $client->save();

        $data = $request->all();
        updateCustomField('client-form', $client->id, $data);

        $this->logActivity(['module' => 'client','module_id' => $client->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.client').' '.trans('messages.updated'), 'status' => 'success']);
    }

    public function destroy(Request $request, Client $client)
    {
        if (!Entrust::can('delete-client')) {
            return response()->json(['message' => trans('messages.permission_denied'),'status' => 'error']);
        }

        deleteUpload2('client', $client->id);

        $this->logActivity(['module' => 'client','module_id' => $client->id,'activity' => 'deleted']);

        deleteCustomField($this->form, $client->id);
        $client->delete();
        return response()->json(['message' => trans('messages.client').' '.trans('messages.deleted'), 'status' => 'success']);
    }
}
