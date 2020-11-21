<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BonusesController extends Controller
{
    public function index(Request $request)
    {
        /* @var $user User */
        $data['user'] = $user = $request->user();
        $data['bonuses'] = $user->bonuses()->paginate();

        $data['meta']['title'] = 'Bonuses';

        return view('dashboard.bonuses', $data);
    }
}
