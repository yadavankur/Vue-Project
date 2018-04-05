<?php

namespace App\Models\Repositories;

use App\Models\Entities\Comment;
use App\Models\Entities\Location;
use App\Models\Entities\NoteType;
use App\Models\Entities\NoteTypeAction;
use Exception;
use Illuminate\Http\Request;
use DB;

class CommentRepository extends BaseRepository
{
    public function model()
    {
        return 'App\Models\Entities\Comment';
    }
    public function saveNotes(Request $request, $type) {

        $comments = $request->notes;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        if ($type == Comment::TYPE_CUSTOMER)
        {
            $noteTypeId = NoteType::TYPE_ID_CUSTOMER;
        }
        else
        {
            $noteTypeId = $request->noteTypeId;
        }

        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $notes = new Comment();
        $notes->comments = $comments;
        $notes->order_id = $orderId;
        $notes->quote_id = $quoteId;
        $notes->location_id = $locationId;
        $notes->note_type_id = $noteTypeId;
        //$notes->type = $type;
        $notes->save();
        // check whether the note type has action
        // if yes execute the action
        $noteTypeAction = NoteTypeAction::where('location_id', $locationId)
            ->where('note_type_id', $noteTypeId)->active()->first();
        if ($noteTypeAction instanceof NoteTypeAction)
        {
            // execute stored procedure
            $sqlStatement = $noteTypeAction->action;
            if ($sqlStatement != '')
            {
                // need to tweak further
                // TODO
                $rets = DB::select(DB::raw($sqlStatement));
            }
        }
        return $notes;
    }

    public function getByPaginate($request, $type)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];

        $perPage = $request->per_page;
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;

        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;

        $search = $request->filter;

        $query = $this->model->select(['comments.*'])
            ->join('users', 'comments.created_by', '=', 'users.id')
            ->where('comments.order_id', $orderId)
            ->where('comments.quote_id', $quoteId)
            ->where('comments.location_id', $locationId)
            ->where('comments.active', 1)
            ->with('note_type')
            ->orderBy($sortBy, $sortDirection);

        if ($type == Comment::TYPE_CUSTOMER)
        {
            $query->where('comments.note_type_id', NoteType::TYPE_ID_CUSTOMER);
        }
        else
        {
            $query->where('comments.note_type_id', '<>', NoteType::TYPE_ID_CUSTOMER);
        }

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('users.name', 'LIKE', $like)
                    ->orWhere('comments.comments','LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }

    public function getLatestNotes($request)
    {
        $orderId = $request->orderId;
        $quoteId = $request->quoteId;
        $locationAbbr = $request->location;
        $location = Location::where('abbreviation', $locationAbbr)->where('active' ,1)->first();
        if (!$location instanceof Location)
            throw new Exception('Invalid location!');
        $locationId = $location->id;
        $type = $request->type;
        if (!$orderId)
            throw new Exception('Invalid order!');
        if (!$type)
            throw new Exception('Invalid note type!');

        $query = $this->model->latest('updated_at')
            ->where('comments.order_id', $orderId)
            ->where('comments.quote_id', $quoteId)
            ->where('comments.location_id', $locationId)
            ->where('comments.active', 1);

        if ($type == Comment::TYPE_CUSTOMER)
        {
            $query->where('comments.note_type_id', NoteType::TYPE_ID_CUSTOMER);
        }
        else
        {
            $query->where('comments.note_type_id', '<>', NoteType::TYPE_ID_CUSTOMER);
        }
        $comment = $query->first();

        return $comment;
    }
}