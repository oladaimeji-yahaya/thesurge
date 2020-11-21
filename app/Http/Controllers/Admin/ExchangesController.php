<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use App\Models\File;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function array_pull;
use function jsonOrRedirectBack;
use function normalizeUrl;
use function request;
use function view;

class ExchangesController extends Controller
{
    public function index(Request $request)
    {
        $exchanges = Exchange::query();
        $query = request('q');
        if ($query) {
            $exchanges->where('key', 'like', "$query%");
        }

        return view('admin.exchanges', [
            'exchanges' => $exchanges->orderBy('rank')->paginate(10)
        ]);
    }
    
    public function update(Request $request)
    {
        $input = $request->all();
        array_pull($input, '_token');
//        $rules = [];
//        $e_msg = [];
//        foreach ($input as $key => $value) {
//            $rules[$key] = 'required|numeric';
//            $name = ucfirst(explode('-', $key)[1]);
//            $e_msg[$key . '.required'] = "$name is required";
//            $e_msg[$key . '.numeric'] = "$name must be a number";
//        }
//        $this->validate($request, $rules, $e_msg);

        //Group
        $plans = [];
        foreach ($input as $key => $value) {
            $splits = explode('-', $key);
            $id = $splits[0];
            $field = $splits[1];
            $plans[$id][$field] = $value;
        }

        //Update
        foreach ($plans as $key => $values) {
            //Enabled only when admin sets it enabled and a wallet address exists
            $values['enabled'] = isset($values['enabled']) && !empty($values['pay_to']) ? true : false;
            Exchange::where('id', $key)->update($values);
        }

        return jsonOrRedirectBack(['status' => true, 'message' => 'Exchanges saved']);
    }
    
    public function manageList(Request $request)
    {
        $this->validate($request, [
            'action' => 'required',
            'id' => 'required|array'
                ], [
            'id.required' => 'Select 1 or more items'
        ]);

        $in = $request->input();
        $count = count($in['id']);
        $s = $count > 1 ? 's' : '';

        switch ($in['action']) {
            case 'enablle':
                Exchange::whereIn('id', $in['id'])->update(['enabled' => true]);
                return ['status' => true, 'message' => $count . " exchange$s enabled"];
            case 'disable':
                Exchange::whereIn('id', $in['id'])->update(['enabled' => false]);
                return ['status' => true, 'message' => $count . " exchange$s disabled"];
        }

        return ['status' => false, 'message' => 'Invalid Request.'];
    }
    
    public function uploadQR(Request $request)
    {
        $this->validate($request, [
            'qr_file' => 'required|image',
            'id' => 'required|exists:exchanges,id',
        ]);
        $exchange = Exchange::find($request->id);
        
        $file = $request->file("qr_file");
        $user_id = $request->user()->id;
        $name = uniqid("qr_{$user_id}_{$exchange->key}_") . '.' . $file->extension();
        $path = $file->storeAs(trim(normalizeUrl(File::QR_IMAGE_DIR), '/'), $name);
        $oldqr = $exchange->qr;
        $exchange->qr = $path;
        $exchange->save();
        Setting::set('wallet_qr_on', 0);
        
        if ($oldqr) {
            Storage::delete($oldqr);
        }

        return jsonOrRedirectBack(['status' => true, 'message' => "QR Code updated for $exchange->name"]);
    }

    public function deleteQR(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:exchanges,id',
        ]);
        $exchange = Exchange::find($request->id);
        Storage::delete($exchange->qr);
        $exchange->qr = null;
        $exchange->save();
        return jsonOrRedirectBack(['status' => true, 'message' => "QR Code for $exchange->name has been deleted"]);
    }
}
