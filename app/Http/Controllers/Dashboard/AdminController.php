<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller {

    private $admins = ['roivic', 'lego993', 'kelechikay', 'tony'];

    public function index(Request $request)
    {
        $slug = $request->user()->slug;
        if (array_search($slug, $this->admins) !== FALSE) {
            return view('dashboard.admin.index');
        } else {
            abort(404);
        }
    }

    public function viewReceipts(Request $request)
    {
        $slug = $request->user()->slug;
        if (array_search($slug, $this->admins) !== FALSE) {
            $match = \App\Models\Match::findByReference($request->input('id'));
            if (is_object($match)) {
                $data = ['status' => true, 'images' => []];
                foreach ($match->files as $file) {
                    $data['images'][] = $file->getURL();
                }
            } else {
                $data = ['status' => false,
                    'message' => 'We couldn\'t find this match'];
            }

            return iResponse('dashboard.receipts', $data);
        } else {
            abort(404);
        }
    }

    public function purgeMatch(Request $request)
    {
        $slug = $request->user()->slug;
        if (array_search($slug, $this->admins) !== FALSE) {
            $match = \App\Models\Match::findByReference($request->input('id'));
            if (is_object($match)) {
                $fraudster = $match->provideHelpRequest->user;
                $match->purge();
                $fraudster->delete();
                $data = ['status' => true,
                    'message' => 'Match has been reversed and one idiot deleted.'];
            } else {
                $data = ['status' => false,
                    'message' => 'Match does not exist'];
            }

            return iResponse('dashboard.receipts', $data);
        } else {
            abort(404);
        }
    }

    public function unblock(Request $request)
    {
        $email = $request->input('email');
        $user = \App\Models\User::findByEmail($email);
        if (is_object($user)) {
            $user->activate();
            $data = ['status' => true,
                'message' => 'Account activated'];
        } else {
            $data = ['status' => true,
                'message' => 'User not found'];
        }
        return iResponse('dashboard.admin.index', $data);
    }

}
