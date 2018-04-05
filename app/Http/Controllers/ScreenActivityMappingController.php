<?php

namespace App\Http\Controllers;

use App\Models\Services\ScreenActivityMappingService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ScreenActivityMappingController extends Controller
{
    protected $screenActivityMappingService;
    public function __construct(ScreenActivityMappingService $screenActivityMappingService)
    {
        $this->screenActivityMappingService = $screenActivityMappingService;
    }
}
