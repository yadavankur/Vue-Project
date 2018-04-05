<?php

namespace App\Models\Repositories;

use DB;
class V6CustomerContactRepository extends BaseRepository
{
    public function model() {
        return 'App\Models\Entities\V6CustomerContact';
    }
    public function getByPaginate($request)
    {
        $sort = $request->sort;
        $sort = explode('|', $sort);

        $sortBy = $sort[0];
        $sortDirection = $sort[1];
        $perPage = $request->per_page;

        $customerId = $request->customerId;
        $search = $request->filter;
        $query = $this->model->select([
            'V_V6_CUSTOMER_CONTACTS.*',
        ])
            ->where('V_V6_CUSTOMER_CONTACTS.CUST_ID', $customerId)
            ->orderBy($sortBy, $sortDirection);

        if ($search) {
            $like = "%{$search}%";
            $query = $query->where(function ($query) use ($like) {
                $query->where('V_V6_CUSTOMER_CONTACTS.CONTACT_NAME', 'LIKE', $like);
            });
        }
        return $query->paginate($perPage);
    }
    public function changeSupervisor($request)
    {
        $quoteId = $request->quoteId;
        $quoteVers = $request->quoteVers;
        $contactId = $request->contactId;
        // TODO need to test and confirm further between V6 and OMS
        $ret = DB::select("SET NOCOUNT ON; DECLARE @intRet INT; EXEC dbo.CHANGE_V6_Contact_ID ?, ?, ?, @intRet OUTPUT; SELECT @intRet;",
            array($quoteId, $quoteVers, $contactId));
        return $ret;
    }
    public function newSupervisor($request)
    {
        $quoteId = $request->quoteId;
        $quoteVers = $request->quoteVers;
        $customerId = $request->customerId;
        $contactName = $request->contactName;
        $phone = $request->phone;
        $fax = $request->fax;
        $mobile = $request->mobile;
        $emailAddr = $request->emailAddress;
        // TODO need to test and confirm further between V6 and OMS
        $ret = DB::select("SET NOCOUNT ON; DECLARE @intRet INT; EXEC dbo.NEW_V6_Contact_ID ?, ?, ?, ?, ?, @intRet OUTPUT; SELECT @intRet;",
                                array($quoteId, $quoteVers, $customerId, $contactName, $phone));
        return $ret;
    }
}