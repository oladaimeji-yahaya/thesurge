<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use function redirect;
use function setting;
use function view;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $data['settings'] = Setting::userDefined()->get();
        return view('admin.settings', $data);
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach ($request->input() as $key => $value) {
            $rules[$key] = 'required';
        }
        $this->validate($request, $rules);

        if ($request->wallet_address !== setting('wallet_address')) {
            Setting::set('wallet_qr_on', 0);
        }
        foreach ($request->input() as $key => $value) {
            Setting::where('name', $key)->update(['value' => $value]);
        }

        return redirect()->back()
                        ->withInput(['status' => true, 'message' => 'Settings saved']);
    }
}
