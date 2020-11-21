<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateApplication;
use App\Models\User;
use Illuminate\Http\Request;
use function request;
use function view;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $affiliates = User::where('super_affiliate', true)->latest()->paginate(10);

        return view('admin.affiliates.index', [
            'affiliates' => $affiliates
        ]);
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
            case 'remove':
                User::whereIn('id', $in['id'])->update(['super_affiliate'=>false]);
                return ['status' => true, 'message' => $count . " affiliate$s removed"];
        }

        return ['status' => false, 'message' => 'Invalid Request.'];
    }
    
    public function requestList(Request $request)
    {
        $affiliates = AffiliateApplication::query();

        $query = request('q');
        if ($query) {
            $affiliates->where(function ($q) use ($query) {
                return $q->where('name', 'like', "$query%")
                                ->orWhere('location', 'like', "%$query%")
                                ->orWhere('email', $query);
            });
        }

        return view('admin.affiliates.requests', [
            'affiliates' => $affiliates->latest()->paginate(10)
        ]);
    }
    
    
    public function manageRequestList(Request $request)
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
            case 'delete':
                AffiliateApplication::whereIn('id', $in['id'])->delete();
                return ['status' => true, 'message' => $count . " affiliate request$s deleted"];
        }

        return ['status' => false, 'message' => 'Invalid Request.'];
    }
}
