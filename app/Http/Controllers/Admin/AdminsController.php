<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class AdminsController extends Controller {

    public function index(Request $request)
    {
        $users = User::withTrashed()
                ->where('admin', true);

        $query = request('q');
        if ($query) {
            $users = $users->where(function($q) use ($query) {
                return $q->where('first_name', 'like', "%$query%")
                                ->orWhere('last_name', 'like', "%$query%")
                                ->orWhere('email', $query);
            });
        }

        $users = $users->paginate(10);

        return view('admin.admins', ['users' => $users]);
    }

}
