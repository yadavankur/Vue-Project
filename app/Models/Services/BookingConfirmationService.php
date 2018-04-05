<?php

namespace App\Models\Services;


use App\Models\Entities\BookingConfirmation;
use App\Models\Repositories\BookingConfirmationRepository;
use Illuminate\Http\Request;

class BookingConfirmationService extends BaseService
{
    protected $bookingConfirmationRepository;
    public function __construct(BookingConfirmationRepository $bookingConfirmationRepository)
    {
        $this->bookingConfirmationRepository = $bookingConfirmationRepository;
    }
    public function updateRequestedDeliveryDate($request)
    {
        return $this->bookingConfirmationRepository->updateRequestedDeliveryDate($request);
    }
    public function getByPaginate(Request $request, $userService)
    {
        return $this->bookingConfirmationRepository->getByPaginate($request, $userService);
    }
    public function getBookingInformation(Request $request)
    {
        return $this->bookingConfirmationRepository->getBookingInformation($request);
    }
    public function getOrderConfirmationEmailTemplate(Request $request)
    {
        return $this->bookingConfirmationRepository->getOrderConfirmationEmailTemplate($request);
    }
    public function getSingleConfirmationEmailTemplate(Request $request)
    {
        return $this->bookingConfirmationRepository->getSingleConfirmationEmailTemplate($request);
    }
    public function getConfirmationEmailTemplate(Request $request)
    {
        return $this->bookingConfirmationRepository->getConfirmationEmailTemplate($request);
    }
    public function getSingleConfirmationTextTemplate(Request $request)
    {
        return $this->bookingConfirmationRepository->getSingleConfirmationTextTemplate($request);
    }
    public function getExpediteEnquiryTemplate(Request $request)
    {
        return $this->bookingConfirmationRepository->getExpediteEnquiryTemplate($request);
    }
}