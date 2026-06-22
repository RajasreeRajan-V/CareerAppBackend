<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceToken;
use App\Models\PushNotification;

class NotificationController extends Controller
{
//   public function saveToken(Request $request)
//     {
//         try {

//             // FCM key optional
//             if (!$request->fcm_key) {

//                 return response()->json([
//                     "status" => "1",
//                     "status_code" => "200",
//                     "data" => (object)[],
//                     "message" => "success"
//                 ]);
//             }

//             // Validation
//             $request->validate([
//                 'device_id' => 'required',
//             ]);

//             DeviceToken::updateOrCreate(

//                 [
//                     'device_id' => $request->device_id
//                 ],

//                 [
//                     'fcm_token' => $request->fcm_key,
//                     'platform'  => $request->platform
//                 ]
//             );

//             return response()->json([
//                 "status" => "1",
//                 "status_code" => "200",
//                 "data" => (object)[],
//                 "message" => "success"
//             ]);

//         } catch (\Exception $e) {

//             return response()->json([
//                 "status" => "0",
//                 "status_code" => "500",
//                 "data" => (object)[],
//                 "message" => $e->getMessage()
//             ]);
//         }
//     }
public function saveToken(Request $request)
{
    try {
        $request->validate([
            'fcm_token'     => 'nullable|string',
            'app_version'   => 'required|string',
            'model'         => 'required|string',
            'model_name'    => 'required|string',
            'model_version' => 'required|string',
        ]);

        DeviceToken::updateOrCreate(
            [
                'fcm_token' => $request->fcm_token,
            ],
            [
                'app_version'   => $request->app_version,
                'model'         => $request->model,
                'model_name'    => $request->model_name,
                'model_version' => $request->model_version,
            ]
        );

        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => (object)[],
            "message"     => "success"
        ]);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => $e->getMessage()
        ]);
    }
}

public function getNotifications(Request $request)
{
    try {
        // Silently delete old ones every time this API is hit
        PushNotification::where('created_at', '<', now()->subDays(7))->delete();

        $notifications = PushNotification::with('college:id,name,location,rating')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = $notifications->map(function ($n) {
            // Load courses separately to avoid column restriction in eager load
            $college = $n->college;
            $courses = $college ? $college->courses()->pluck('name')->implode(', ') : null;

            return [
                'id'         => $n->id,
                'title'      => $n->title,
                'message'    => $n->message,
                'college_id' => $n->college_id,
                'college'    => $college ? $college->name : null,
                'location'   => $college ? $college->location : null,
                'rating'     => $college ? (string) $college->rating : null,
                'courses'    => $courses,
                'created_at' => $n->created_at->toDateTimeString(),
                'time_ago'   => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            "status"      => "1",
            "status_code" => "200",
            "data"        => [
                'notifications' => $data,
                'current_page'  => $notifications->currentPage(),
                'last_page'     => $notifications->lastPage(),
                'total'         => $notifications->total(),
                'per_page'      => $notifications->perPage(),
            ],
            "message" => "success"
        ]);

    } catch (\Exception $e) {
        return response()->json([
            "status"      => "0",
            "status_code" => "500",
            "data"        => (object)[],
            "message"     => $e->getMessage()
        ]);
    }
}
}