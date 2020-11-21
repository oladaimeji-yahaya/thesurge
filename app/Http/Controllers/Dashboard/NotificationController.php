<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(10);
        //Mark as read
        $request->user()->notifications()
//                ->whereIn('id', $notifications->getCollection()->pluck('id'))
                ->update(['read_at' => Carbon::now()]);
        //Format
        $data['notifications'] = [];
        foreach ($notifications as $notification) {
            $data['notifications'][] = [
                'status' => 'read',
                'link' => $notification->data['link'],
                'msg' => $notification->data['msg'],
                'created_at' => fancyTime($notification->created_at),
                'id' => $notification->id,
            ];
        }
        $data['links'] = $notifications->links();
        $data['dlink'] = route('dashboard.notification.action', ['action' => 'del']);
        $data['mlink'] = route('dashboard.notification.action', ['action' => 'mark']);
        $data['meta']['title'] = 'Notifications';
        return view('dashboard.notifications', $data);
    }

    public function news()
    {
        $data['meta']['title'] = 'News';
        return view('dashboard.news', $data);
    }

    public function react(Request $request, $action)
    {
        switch ($action) {
            case 'del':
                return $this->delete($request);
            case 'mark':
                return $this->mark($request);
        }
        return null;
    }

    public function delete(Request $request)
    {
        $id = $request->input('id');
        $response = [];
        if ($id) {
            $request->user()->notifications()
                    ->where('notifications.id', $id)
                    ->delete();
            $response['status'] = 1;
            $response['count'] = $request->user()->unreadNotifications->count();

            return $response;
        }

        return $response;
    }

    /**
     * Mark notifications with the given id(s)
     * @return type
     */
    public function mark(Request $request)
    {
        $id = $request->input('id');
        $response = [];
        if ($id) {
            $status = $request->input('status', null);
            $nft = $request->user()->notifications();
            if ($status) {
                $ids = (array) $id;
                //Set status
                switch ($status) {
                    case 'unread':
                        $n = $nft->whereIn('notifications.id', $ids)
                                ->update(['read_at' => null]);
                        $response['status'] = 'unread';
                        break;
                    default:
                        $n = $nft->whereIn('notifications.id', $ids)
                                ->update(['read_at' => Carbon::now()]);
                        $response['status'] = 'read';
                        break;
                }
            } else {
                $n = $nft->where('notifications.id', $id)->first();
                abort_unless(is_object($n), 404);
                //Toggle
                if (empty($n->read_at)) {
                    $n->update(['read_at' => Carbon::now()]);
                    $response['status'] = 'read';
                } else {
                    $n->update(['read_at' => null]);
                    $response['status'] = 'unread';
                }
            }
            $response['count'] = fancyMaxCount($request->user()->unreadNotifications->count());
        }

        return $response;
    }
}
