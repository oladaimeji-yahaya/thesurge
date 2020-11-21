<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use function array_pull;
use function jsonOrRedirectBack;
use function view;

class PlansController extends Controller
{
    public function index(Request $request)
    {
        $data['plans'] = Plan::all();
        return view('admin.plans', $data);
    }

    public function update(Request $request)
    {
        $input = $request->all();
        array_pull($input, '_token');
        $rules = [];
        $e_msg = [];
        foreach ($input as $key => $value) {
            $name = ucfirst(explode('::', $key)[1]);
            $rules[$key] = 'required' . ($name == 'Name' ? '' : '|numeric');
            $e_msg[$key . '.required'] = "$name is required";
            $e_msg[$key . '.numeric'] = "$name must be a number";
        }
        $this->validate($request, $rules, $e_msg);

        //Group
        $plans = [];
        foreach ($input as $key => $value) {
            $splits = explode('::', $key);
            $plans[$splits[0]][$splits[1]] = $value;
        }

        //Update
        foreach ($plans as $key => $values) {
            Plan::where('id', $key)->update($values);
        }

        return jsonOrRedirectBack(['status' => true, 'message' => 'Plans saved']);
    }
}
