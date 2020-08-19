<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\CurrencyRequest;
use App\Currency;
use Entrust;

Class CurrencyController extends Controller{
    use BasicController;

	public function index(){
	}

	public function show(){
	}

	public function create(){
		return view('currency.create');
	}

	public function lists(){
		$currencies = Currency::all();
		return view('currency.list',compact('currencies'))->render();
	}

	public function edit(Currency $currency){
		return view('currency.edit',compact('currency'));
	}

	public function store(CurrencyRequest $request, Currency $currency){
		$currency->fill($request->all());
		
		if($request->input('is_default')){
			$currency->is_default = 1;
			Currency::whereNotNull('id')->update(['is_default' => 0]);
		}

		$currency->save();

		$this->logActivity(['module' => 'currency','module_id' => $currency->id,'activity' => 'added']);

    	$new_data = array('value' => $currency->detail,'id' => $currency->id,'field' => 'currency_id');
        $response = ['message' => trans('messages.currency').' '.trans('messages.added'), 'status' => 'success','new_data' => $new_data]; 
        $response = $this->getSetupGuide($response,'currency');
        return response()->json($response);
	}

	public function update(CurrencyRequest $request, Currency $currency){
		
		if($request->input('is_default')){
			Currency::where('id','!=',$currency->id)->update(['is_default' => 0]);
			$currency->is_default = 1;
		}

		$currency->fill($request->all())->save();
		
		$this->logActivity(['module' => 'currency','module_id' => $currency->id,'activity' => 'updated']);

        return response()->json(['message' => trans('messages.currency').' '.trans('messages.updated'), 'status' => 'success']);
	}

	public function destroy(Currency $currency,Request $request){

		$this->logActivity(['module' => 'currency','module_id' => $currency->id,'activity' => 'deleted']);

        $currency->delete();

        return response()->json(['message' => trans('messages.currency').' '.trans('messages.deleted'), 'status' => 'success']);
	}
}
?>