<?php

namespace App\Http\Controllers;

use App\Models\Entities\Log;
use App\Models\Services\BookingConfirmationService;
use App\Models\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookingConfirmationController extends Controller
{
    protected $bookingConfirmationService;
    protected $userService;

    public function __construct(BookingConfirmationService $bookingConfirmationService, UserService $userService)
    {
        $this->bookingConfirmationService = $bookingConfirmationService;
        $this->userService = $userService;
    }
    public function updateRequestedDeliveryDate(Request $request) {
        $rules = [
            'orderId'  =>  'required',
            'quoteId'  =>  'required',
            'location'  =>  'required',
            'type'  =>  'required',
            'deliveryDate' => 'required',
        ];
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            $this->validate($request, $rules);
            // 2) update delivery date
            $booking = $this->bookingConfirmationService->updateRequestedDeliveryDate($request);
            $this->bookingConfirmationService->LogEntity($booking, 'success', __CLASS__ . '::' .__FUNCTION__);
            return response()->json($booking);
        } catch (Exception $e) {
            return $this->handleValidationException($e, $this->bookingConfirmationService, __CLASS__ . '::' . __FUNCTION__, Log::LOG_LEVEL_ERROR);
        }
    }

    public function getBookingConfirmationList(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get paginated data
            $bookings= $this->bookingConfirmationService->getByPaginate($request, $this->userService);
            return response()->json($bookings);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBookingInformation(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $booking= $this->bookingConfirmationService->getBookingInformation($request);
            return response()->json($booking);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getOrderConfirmationEmailTemplate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $emailTemplate= $this->bookingConfirmationService->getOrderConfirmationEmailTemplate($request);
            return $emailTemplate;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getSingleConfirmationEmailTemplate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $emailTemplate= $this->bookingConfirmationService->getSingleConfirmationEmailTemplate($request);
            return $emailTemplate;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getSingleConfirmationTextTemplate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $textTemplate= $this->bookingConfirmationService->getSingleConfirmationTextTemplate($request);
            return $textTemplate;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getConfirmationEmailTemplate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $emailTemplate= $this->bookingConfirmationService->getConfirmationEmailTemplate($request);
            return $emailTemplate;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getExpediteEnquiryTemplate(Request $request)
    {
        try {
            // 1) first get user from token to check validation
            $user = JWTAuth::parseToken()->authenticate();
            // 2) get data
            $emailTemplate= $this->bookingConfirmationService->getExpediteEnquiryTemplate($request);
            return $emailTemplate;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
