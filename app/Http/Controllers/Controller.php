<?php

namespace App\Http\Controllers;


use App\Models\Entities\ResourceType;
use App\Models\Repositories\BaseRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {  return response()->json(['user_not_found'], 404); }
            return $user;
        } catch (TokenExpiredException $e) {  return response()->json(['token_expired'], $e->getStatusCode());   } 
        catch (TokenInvalidException $e) { return response()->json(['token_invalid'], $e->getStatusCode());  } 
        catch (JWTException $e) {   return response()->json(['token_absent'], $e->getStatusCode());     }
    }

    public function isAccessible($user, $urlPath, $service)
    {
        $usergroups = $user->usergroups->toArray();
        $group_Ids = array_column($usergroups, 'group_id');
        $permissions = $service->getPermission($urlPath, $group_Ids);
        if (is_null($permissions[0]) || is_null($permissions[0]->permissionId))
        {  $permissionId = 3;    }
        else
            $permissionId = intval($permissions[0]->permissionId);
        if ($permissionId > 2) return false;
        return true;
    }

    public function getAssignedResources($user, $userService, $resourceService, $resourceType)
    {   $isRoot = BaseRepository::isRoot($user);// 1) check user role
        $isAdmin = BaseRepository::isAdmin($user);
        if ($isRoot || ($isAdmin && ($resourceType != ResourceType::LOCATION)))
            $aclResources = $resourceService->getAll($user);// get all resources
        else
            $aclResources = $userService->getAclResourceByType($user, $resourceType);// 2) get accessible locations based on user
        foreach($aclResources as $aclResource)
        {  $aclRets[] = $aclResource;  }
        return $aclRets;
    }
    /**
     * Getting the error response from a API call.
     *
     * @param string $errorCode
     * @param string $message
     * @param array  $errors
     *
     * @return mixed
     */
    protected function getErrorResp($errorCode, $message, $errors = array())
    {
        return response([
            'message' => $message,
            'errors' => $errors,
        ], $errorCode);
    }

    protected function handleValidationException($exception, $service, $funcName, $level) {
        if ($exception instanceof ValidationException)
        {
            // validation error
            $errors =  $exception->validator->errors();
            $error_msg = $errors;
            $msg = trans('messages.invalid_request') . $error_msg;
        }
        else
        {
            $msg = $error_msg = $exception->getMessage();
        }
        $service->LogEntity(null, $error_msg, $funcName, $level);
        return response()->json(['error' => $msg], 500);
    }
}
