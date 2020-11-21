<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\File;
use Illuminate\Http\Request;
use function bcrypt;
use function jsonOrRedirectBack;
use function request;
use function view;

class ProfileController extends Controller
{
    public function index()
    {
        $data['user'] = request()->user();
        $data['countries'] = Country::where('id', '>', 1)->get();
        $data['meta']['title'] = 'Profile';
        return view('dashboard.profile', $data);
    }

    public function save(Request $request)
    {
        $user = request()->user();
        $this->validate($request, [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                    'country' => 'required|exists:countries,id',
            'phone' => 'required|numeric|digits_between:9,14|unique:users,phone,' . $user->id,
        ]);

        $input = $request->input();
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        $user->country_id = $input['country'] ?? 1;
        $user->phone = $input['phone'] ?? null;
        $user->wallet_id = $input['wallet_id'] ?? null;
        $user->address = $input['address'] ?? null;
        $user->save();


        return jsonOrRedirectBack(['profileSaved' => 'Profile updated']);
    }

    public function uploadIdentityPhoto(Request $request)
    {
        $this->validate($request, [
            'identity_photo' => 'required|image',
        ]);

        $file = $request->file("identity_photo");
        $name = uniqid('identity_') . '.' . $file->extension();
        $path = $file->storeAs(File::IMAGE_DIR . date('Y-W'), $name);

        $user = request()->user();
        $user->identity_photo = $path;
        $user->save();

        return jsonOrRedirectBack(['identitySaved' => 'Identity saved']);
    }

    public function uploadProfilePhoto(Request $request)
    {
        $this->validate($request, [
            'photo' => 'required|image',
        ]);
        $file = $request->file("photo");
        $name = uniqid('photo_') . '.' . $file->extension();
        $path = $file->storeAs(File::IMAGE_DIR . date('Y-W'), $name);

        $user = request()->user();
        $user->photo = $path;
        $user->save();

        return jsonOrRedirectBack(['photoSaved' => 'Profile photo saved']);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $user = request()->user();
        $input = $request->input();
        $user->password = bcrypt($input['password']);
        $user->save();

        return jsonOrRedirectBack(['passwordSaved' => 'Password changed']);
    }
}
