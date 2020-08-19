<?php
namespace App\Http\Controllers;
use App\CustomField;
use Auth;
use Entrust;
use Illuminate\Http\Request;
use App\Http\Requests\CustomFieldRequest;

Class CustomFieldController extends Controller{
    use BasicController;

	public function __construct()
	{
		$this->middleware('feature_available:enable_custom_field');
	}

	public function index(CustomField $custom_field){

		$table_data['custom-field-table'] = array(
			'source' => 'custom-field',
			'title' => trans('messages.custom').' '.trans('messages.field').' '.trans('messages.list'),
			'id' => 'custom_field_table',
			'data' => array(
        		trans('messages.option'),
        		trans('messages.form'),
        		trans('messages.title'),
        		trans('messages.type'),
        		trans('messages.option'),
        		trans('messages.required')
        		)
			);

		$assets = ['datatable','tags'];

		$form_names = config('custom-field');
		$forms = array();
		foreach($form_names as $name){
			$form_name = array();
			$titles = explode('-',$name);
			foreach($titles as $title)
				$form_name[] = trans('messages.'.$title);
			$forms[$name] = implode(' ',$form_name);
			unset($form_name);
		}

		return view('custom_field.index',compact('table_data','assets','forms'));
	}

	public function lists(Request $request){
		$custom_fields = CustomField::all();

		$rows = array();
		foreach($custom_fields as $custom_field){
			$rows[] = array(
				delete_form(['custom-field.destroy',$custom_field->id]),
				toWord($custom_field->form),
				$custom_field->title,
				ucfirst($custom_field->type),
				implode('<br />',explode(',',$custom_field->options)),
				($custom_field->is_required) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'
				);
		}
        $list['aaData'] = $rows;
        return json_encode($list);
	}

	public function store(CustomFieldRequest $request, CustomField $custom_field){

		$data = $request->all();
        $custom_field->fill($data);

		$options = explode(',',$request->input('options'));
		$options = array_unique($options);
		$custom_field->options = implode(',',$options);
		$custom_field->name = createSlug($request->input('title'));
		$custom_field->save();

		$this->logActivity(['module' => 'custom_field','module_id' => $custom_field->id,'activity' => 'added']);

        return response()->json(['message' => trans('messages.custom').' '.trans('messages.field').' '.trans('messages.added'), 'status' => 'success']);
	}

	public function destroy(CustomField $custom_field,Request $request){

		$this->logActivity(['module' => 'custom_field','module_id' => $custom_field->id,'activity' => 'deleted']);

        $custom_field->delete();

        return response()->json(['message' => trans('messages.custom').' '.trans('messages.field').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>