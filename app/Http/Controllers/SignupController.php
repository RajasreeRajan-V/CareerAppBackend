<?php

namespace App\Http\Controllers;
use App\Http\Requests\SignupStoreRequest;
use App\Http\Requests\LoginStoreRequest;
use App\Models\User;
use App\Models\Children;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\SavedCollege;

class SignupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|in:Student,Parent',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|regex:/^[6-9][0-9]{9}$/',
            'password' => 'required|min:8',
            'current_education' => 'nullable|string|max:255',
            'children' => 'required_if:role,Parent|array',
            'children.*.name' => 'required|string|max:255',
            'children.*.education_level' => 'required|string|max:50',  
            
        ]);
    
       if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'status_code' => "422",
                'data' => (object)[],
                'message' => $validator->errors()->first(),
            ], 422);
        }



    $userData = $request->only(['role','name','email','phone','current_education']);
    $userData['password'] = Hash::make($request->password);
    $userData['auth_token'] = Str::random(60);
    $user = User::create($userData);
   
   
    if ($user->role === 'Parent' && $request->filled('children')) {
    foreach ($request->children as $child) {
        $user->children()->create($child);
    }
    }

       return response()->json([
            'status' => "1",
            'status_code' => "200",
            'data' => [
                'user_id' => (string) $user->id,
                'auth_token' => $user->auth_token,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                
                'role' => $user->role,
            ],
            'message' => 'Signup successful'
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => "0",
                'status_code' => "422",
                'message' => 'Invalid email or password format'
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => "0",
                'status_code' => "404",
                'message' => 'Email not found'
            ], 404);
        }
       if (!Hash::check($request->password, $user->password)) 
        {
            return response()->json([
                'status' => "0",
                'status_code' => "401",
                'message' => 'Incorrect password'
            ], 401);
        }
        
        // Generate new token
        $token = Str::random(60);
        
        // Save token in database
        $user->auth_token = $token;
        $user->save();
        
        return response()->json([
            'status' => "1",
            'status_code' => "200",
            'data' => [
                'user_id' => (string) $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'auth_token' => $token,
            ],
            'message' => 'Login successful'
        ], 200);


    }
    public function forgotPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => "0",
            'status_code' => "422",
            'message' => $validator->errors()->first()
        ], 422);
    }

    // Generate secure 6 digit OTP
    $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    PasswordReset::updateOrCreate(
        ['email' => $request->email],
        [
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]
    );

    Mail::raw("Your OTP for password reset is: $otp", function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Password Reset OTP');
    });

    return response()->json([
        'status' => "1",
        'status_code' => "200",
        'message' => 'OTP sent to your email'
    ], 200);
}
public function resetPassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:users,email',
        'otp' => 'required|digits:6',
        'password' => 'required|min:8'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => "0",
            'status_code' => "422",
            'message' => $validator->errors()->first()
        ], 422);
    }

    $record = PasswordReset::where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

    if (!$record) {
        return response()->json([
            'status' => "0",
            'status_code' => "400",
            'message' => 'Invalid OTP'
        ], 400);
    }

    if (Carbon::now()->greaterThan($record->expires_at)) {
        return response()->json([
            'status' => "0",
            'status_code' => "400",
            'message' => 'OTP expired'
        ], 400);
    }

    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete OTP after success
    $record->delete();

    return response()->json([
        'status' => "1",
        'status_code' => "200",
        'message' => 'Password reset successful'
    ], 200);
}

public function deleteAccount(Request $request)
{
    $user = $request->authUser; // <-- change here

    if (!$user) {
        return response()->json([
            'status' => "0",
            'status_code' => "401",
            'message' => 'Unauthorized'
        ], 401);
    }

    $user->savedColleges()->delete();
    $user->delete();

    return response()->json([
        'status' => "1",
        'status_code' => "200",
        'message' => 'Account deleted successfully'
    ], 200);
}
}
