<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RefController extends Controller
{

    public function findRef(Request $request, $ref)
    {
        $r = ['id' => '', 'name' => ''];
        $ref = addslashes($ref); //Sanitize
        if (filter_var($ref, FILTER_VALIDATE_EMAIL)) {
            $user = User::findByEmail($ref);
        } else {
            $user = User::findByColumn('username', $ref)->first();
            if (!is_object($user)) {
                $user = User::whereRaw("ref_code = '$ref' COLLATE utf8_bin")->first();
            }
        }

        if (is_object($user)) {
            $r['name'] = $user->name;
            $r['id'] = $user->id;
        }

        $request->session()->put('ref', json_encode($r));
        return redirect()->route('register');
    }

}
