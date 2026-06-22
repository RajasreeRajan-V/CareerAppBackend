<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerGuidanceBanner;
use App\Models\CareerGuidanceRegistration;
use Illuminate\Support\Facades\Mail;
use App\Mail\CareerGuidanceRegistrationMail;

class CareerGuidanceBannerController extends Controller
{
    public function index()
    {
        try {

            $banners = CareerGuidanceBanner::latest()->get();

            if ($banners->isEmpty()) {
                return response()->json([
                    "status" => "1",
                    "status_code" => "200",
                    "data" => (object)[],
                    "message" => "No career guidance banners found"
                ], 200);
            }

            $formattedBanners = [];

            foreach ($banners as $banner) {
                $formattedBanners[] = [
                    "id" => (string) $banner->id,
        
                    "name" => $banner->name,
                    "instructor_name" => $banner->instructor_name,
                    "description" => $banner->description,
                    "event_date" => $banner->event_date,
                    "start_time" => $banner->start_time,
                    "end_time" => $banner->end_time,

                    // Full Google Meet URL
                    "google_meet_link" => $banner->google_meet_link
                        ? "https://meet.google.com/" . $banner->google_meet_link
                        : null,

                    // Full Image URL
                    "image" => $banner->image
                        ? asset('storage/' . $banner->image)
                        : null,
                ];
            }

            return response()->json([
                "status" => "1",
                "status_code" => "200",
                "data" => [
                    "banners" => $formattedBanners,
                    "total_banners" => (string) count($formattedBanners)
                ],
                "message" => "Career guidance banners fetched successfully"
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                "status" => "0",
                "status_code" => "500",
                "data" => (object)[],
                "message" => "Something went wrong. Please try again later."
            ], 500);
        }
    }
    
   public function register(Request $request)
{
    try {
        // VALIDATION
        $validator = \Validator::make($request->all(), [
            'banner_id' => 'required|exists:career_guidance_banners,id',
            'name'      => 'required|string',
            'email'     => 'required|email|unique:career_guidance_registrations,email,NULL,id,career_guidance_banner_id,' . $request->banner_id,
            'phone'     => 'nullable'
        ], [
            'email.unique' => 'You have already registered for the event.'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            if ($errors->has('email')) {
                return response()->json([
                    "status"      => "0",
                    "status_code" => "403",
                    "data"        => (object)[],
                    "message"     => "You have already registered for the event."
                ], 403);
            }

            return response()->json([
                "status"      => "0",
                "status_code" => "422",
                "data"        => $errors,
                "message"     => "Validation failed"
            ], 422);
        }

        // FETCH BANNER
        $banner = CareerGuidanceBanner::find($request->banner_id);

        // SLOT CHECK — max 100 registrations per banner
        $totalRegistrations = CareerGuidanceRegistration::where('career_guidance_banner_id', $banner->id)->count();

        if ($totalRegistrations >100) {
            return response()->json([
                "status"      => "0",
                "status_code" => "409",
                "data"        => (object)[],
                "message"     => "Sorry, all 100 slots are full. Registration is closed."
            ], 409);
        }

        // SAVE REGISTRATION
        $registration = CareerGuidanceRegistration::create([
            'career_guidance_banner_id' => $banner->id,
            'name'                      => $request->name,
            'email'                     => $request->email,
            'phone'                     => $request->phone
        ]);

        // SEND MAIL (non-blocking)
        try {
            Mail::to($request->email)
                ->send(new CareerGuidanceRegistrationMail($banner, [
                    'name'  => $request->name,
                    'email' => $request->email
                ]));
        } catch (\Exception $mailError) {
            \Log::error("Mail failed: " . $mailError->getMessage());
        }

        // Remaining slots for info
        $remainingSlots = 100 - ($totalRegistrations + 1);

        // SUCCESS RESPONSE
        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => [
                "registration_id" => (string) $registration->id,
                "slots_remaining" => $remainingSlots
            ],
            "message" => "Registration successful. Meeting link sent to your email."
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => "Something went wrong. Please try again later."
        ], 500);
    }
}
}