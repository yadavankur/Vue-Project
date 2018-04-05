<?php
namespace App\Models\Repositories;


use App\Models\Entities\Message;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class MessageRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Message';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $filter = $request->filter;
        $search = $filter['filterText'];
        $status = $filter['status'];
        $read = false;
        $unRead = false;
        if ($status == 'Read')
        {
            $read = true;
        }
        else if ($status == 'UnRead')
        {
            $unRead = true;
        }
        //$read  = $request->read;
        //$unRead = $request->unRead;

        $user = Auth::user();
        $query = $this->model->active()->user($user)
            ->orderBy($sortBy, $sortDirection);
        if (($read) && (!$unRead))
            $query->read();
        else if (($unRead) && (!$read))
            $query->unread();
        if ($search) {
            $like = "%{$search}%";
            $query->where('message', 'LIKE', $like);
        }
        return $query->paginate($perPage);
    }
    public function updateNotificationStatus($request)
    {
        $messageId  = $request->id;
        $message = $this->model->active()->where('id', $messageId)->first();
        if (!$message instanceof Message)
        {
            throw new Exception('Invalid notification!');
        }
        if (($message->read_by_id) && ($message->read_at))
            return $message;

        $user = Auth::user();
        $message->read_by_id = $user->id;
        $message->read_at = Carbon::now();
        $message->save();
        return $message;
    }
    public static function getUnreadNotificationCount()
    {
        $user = Auth::user();
        $query = Message::active()->user($user)->unread()->select();
        $count = $query->count();
        return $count;
    }
    public function getBadgeCount($request)
    {
        $unReadNotifications = self::getUnreadNotificationCount();
        $overDueTasks = CpmOrderMatrixRepository::getOverDueTaskCount();
        return array(
            'unReadNotifications' => $unReadNotifications,
            'overDueTasks' => $overDueTasks,
        );
    }
}