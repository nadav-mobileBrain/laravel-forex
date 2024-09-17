<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Asset;
use App\Models\Frame;
use App\Models\Signal;
use App\Models\Status;
use App\Models\Symbol;
use App\Models\SignalPlan;
use App\Models\UserSignal;
use Illuminate\Http\Request;

class ManufactureController extends Controller {
    public function __construct() {
        $this->middleware('auth:admin');
    }

    public function create() {
        $data['page_title'] = 'Manage Signal Asset';
        $data['asset'] = Asset::orderBy('id', 'desc')->paginate(15);

        return view('dashboard.asset', $data);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name'   => 'required|unique:assets,name',
            'status' => 'required'
        ]);

        $data = new Asset();
        $data->name = $request->name;
        $data->slug = str_slug($request->name);
        $data->status = $request->status;
        $data->save();

        return response()->json($data);

    }

    /**
     * @param $product_id
     */
    public function edit($product_id) {
        $product = Asset::find($product_id);

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @param $product_id
     */
    public function update(Request $request, $product_id) {
        $product = Asset::find($product_id);
        $request->validate([
            'name'   => 'required|unique:assets,name,' . $product->id,
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->slug = str_slug($request->name);
        $product->status = $request->status;
        $product->save();

        return response()->json($product);
    }

    /**
     * @param $id
     */
    public function delete($id) {
        $d = Asset::findOrFail($id);
        $sig = Signal::whereAsset_id($id)->pluck('id')->toArray();
        SignalPlan::whereIn('signal_id', $sig)->delete();
        UserSignal::whereIn('signal_id', $sig)->delete();
        Signal::whereAsset_id($id)->delete();
        $d->delete();

        return response()->json($d);
    }

    public function createSymbol() {
        $data['page_title'] = 'Manage Symbol';
        $data['asset'] = Symbol::orderBy('id', 'desc')->paginate(15);

        return view('dashboard.symbol', $data);
    }

    /**
     * @param Request $request
     */
    public function storeSymbol(Request $request) {
        $this->validate($request, [
            'name'   => 'required|unique:symbols,name',
            'status' => 'required'
        ]);

        $data = new Symbol();
        $data->name = $request->name;
        $data->slug = str_slug($request->name);
        $data->status = $request->status;
        $data->save();

        return response()->json($data);

    }

    /**
     * @param $product_id
     */
    public function editSymbol($product_id) {
        $product = Symbol::find($product_id);

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @param $product_id
     */
    public function updateSymbol(Request $request, $product_id) {
        $product = Symbol::find($product_id);
        $request->validate([
            'name'   => 'required|unique:symbols,name,' . $product->id,
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->slug = str_slug($request->name);
        $product->status = $request->status;
        $product->save();

        return response()->json($product);
    }

    /**
     * @param $id
     */
    public function deleteSymbol($id) {
        $d = Symbol::find($id)->delete();
        $sig = Signal::whereSymbol_id($id)->pluck('id')->toArray();
        SignalPlan::whereIn('signal_id', $sig)->delete();
        UserSignal::whereIn('signal_id', $sig)->delete();
        Signal::whereSymbol_id($id)->delete();

        return response()->json($d);
    }

    public function createType() {
        $data['page_title'] = 'Manage Type';
        $data['asset'] = Type::orderBy('id', 'desc')->paginate(15);

        return view('dashboard.type', $data);
    }

    /**
     * @param Request $request
     */
    public function storeType(Request $request) {
        $this->validate($request, [
            'name'   => 'required|unique:types,name',
            'status' => 'required'
        ]);

        $data = new Type();
        $data->name = $request->name;
        $data->slug = str_slug($request->name);
        $data->status = $request->status;
        $data->save();

        return response()->json($data);

    }

    /**
     * @param $product_id
     */
    public function editType($product_id) {
        $product = Type::find($product_id);

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @param $product_id
     */
    public function updateType(Request $request, $product_id) {
        $product = Type::find($product_id);
        $request->validate([
            'name'   => 'required|unique:types,name,' . $product->id,
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->slug = str_slug($request->name);
        $product->status = $request->status;
        $product->save();

        return response()->json($product);
    }

    /**
     * @param $id
     */
    public function deleteType($id) {
        $d = Type::find($id)->delete();
        $sig = Signal::whereType_id($id)->pluck('id')->toArray();
        SignalPlan::whereIn('signal_id', $sig)->delete();
        UserSignal::whereIn('signal_id', $sig)->delete();
        Signal::whereType_id($id)->delete();

        return response()->json($d);
    }

    public function createFrame() {
        $data['page_title'] = 'Manage Frame';
        $data['asset'] = Frame::orderBy('id', 'desc')->paginate(15);

        return view('dashboard.frame', $data);
    }

    /**
     * @param Request $request
     */
    public function storeFrame(Request $request) {
        $this->validate($request, [
            'name'   => 'required|unique:frames,name',
            'status' => 'required'
        ]);

        $data = new Frame();
        $data->name = $request->name;
        $data->slug = str_slug($request->name);
        $data->status = $request->status;
        $data->save();

        return response()->json($data);

    }

    /**
     * @param $product_id
     */
    public function editFrame($product_id) {
        $product = Frame::find($product_id);

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @param $product_id
     */
    public function updateFrame(Request $request, $product_id) {
        $product = Frame::find($product_id);
        $request->validate([
            'name'   => 'required|unique:frames,name,' . $product->id,
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->slug = str_slug($request->name);
        $product->status = $request->status;
        $product->save();

        return response()->json($product);
    }

    /**
     * @param $id
     */
    public function deleteFrame($id) {
        $d = Frame::find($id)->delete();
        $sig = Signal::whereFrame_id($id)->pluck('id')->toArray();
        SignalPlan::whereIn('signal_id', $sig)->delete();
        UserSignal::whereIn('signal_id', $sig)->delete();
        Signal::whereFrame_id($id)->delete();

        return response()->json($d);
    }

    public function createStatus() {
        $data['page_title'] = 'Manage Status';
        $data['asset'] = Status::orderBy('id', 'desc')->paginate(15);

        return view('dashboard.status', $data);
    }

    /**
     * @param Request $request
     */
    public function storeStatus(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:statuses,name'
        ]);

        $data = new Status();
        $data->name = $request->name;
        $data->slug = str_slug($request->name);
        $data->status = $request->status;
        $data->save();

        return response()->json($data);

    }

    /**
     * @param $product_id
     */
    public function editStatus($product_id) {
        $product = Status::find($product_id);

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @param $product_id
     */
    public function updateStatus(Request $request, $product_id) {
        $product = Status::find($product_id);
        $request->validate([
            'name'   => 'required|unique:statuses,name,' . $product->id,
            'status' => 'required'
        ]);

        $product->name = $request->name;
        $product->slug = str_slug($request->name);
        $product->status = $request->status;
        $product->save();

        return response()->json($product);
    }

    /**
     * @param $id
     */
    public function deleteStatus($id) {
        $d = Status::find($id)->delete();
        $sig = Signal::whereStatus_id($id)->pluck('id')->toArray();
        SignalPlan::whereIn('signal_id', $sig)->delete();
        UserSignal::whereIn('signal_id', $sig)->delete();
        Signal::whereStatus_id($id)->delete();

        return response()->json($d);
    }

}
