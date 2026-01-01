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
        'phone' => 'required|regex:/^[6-9][0-9]{9}$/',
        'password' => 'required|min:8|confirmed',
        'current_education' => 'nullable|string|max:255',
        'subject_group' => 'nullable|string|max:255',
        'children' => 'required_if:role,Parent|array',
        'children.*.name' => 'required|string|max:255',
        'children.*.age' => 'required|integer',
        'children.*.class' => 'required|string|max:50',
    ]);
    
    $userData = $request->only(['role','name','email','phone','current_education','subject_group']);
    $userData['password'] = Hash::make($request->password);
    $userData['authtoken'] = Str::random(60);
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
                'auth_token' => $user->authtoken,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'password' => $user->password,
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
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
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
        
        return response()->json([
            'status' => "1",
            'status_code' => "200",
            'data' => [
                'email' => $user->email,
                'password' => $user->password,   
                'authtoken' =>  Str::random(60),          
            ],
            'message' => 'Login successful'
    ], 200);

    }
}
