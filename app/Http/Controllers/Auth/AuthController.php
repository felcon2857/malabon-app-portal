<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function index()
    {
        return response()->json([
            "name" => "malabon-app-api",
            "author" => "unknown",
            "version" => "v1.0.0",
            "createdAt" => "09.27.2021",
        ]);
    }
    // login function
    public function loginAuth(Request $request)
    {
        $userInfo = $request->validate([
            'username' => 'required',
            'password' => 'required|min:8'
        ]);
        $userType = User::where("email", $userInfo["username"])->orwhere("contact_no", $userInfo["username"])->first();
        if (!$userType || !Hash::check($userInfo["password"], $userType->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ], 401);
        } else {
            $response = [
                "status" => "success",
                "data" => $userType,
            ];
            return response()->json($response, 200);
        }
    }

    // register function
    public function registerAuth(Request $request)
    {
        $userInfo = $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'contact' => 'required'
        ]);
        $data = User::create([
            "username" => $userInfo['username'],
            "email" => $userInfo['email'],
            "password" => Hash::make($userInfo['password']),
            "first_name" => $userInfo['firstname'],
            "last_name" => $userInfo['lastname'],
            "address" => $userInfo['address'],
            "contact_no" => $userInfo['contact'],
            "login_with" => "login_with_email"
        ]);
        $response = [
            "status" => "success",
            "data" => $data,
        ];
        return response($response, 201);
    }

    // function to save google sign in data
    public function registerGoogle(Request $request)
    {
        $userInfo = $request->validate([
            'email' => 'required|email|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        $chars =  substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
        $data = User::create([
            "username" => "@user" . $chars,
            "email" => $userInfo['email'],
            "password" => Hash::make('default_google_login'),
            "first_name" => $userInfo['firstname'],
            "last_name" => $userInfo['lastname'],
            "address" => "No address added",
            "contact_no" => "No contact number added",
            "login_with" => "login_with_google"
        ]);
        $response = [
            "status" => "success",
            "data" => $data,
        ];
        return response($response, 201);
    }

    // function to save facebook sign in data
    public function registerFacebook(Request $request)
    {
        $userInfo = $request->validate([
            'email' => 'required|email|unique:users',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);
        $chars =  substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
        $data = User::create([
            "username" => "@user" . $chars,
            "email" => $userInfo['email'],
            "password" => Hash::make('default_google_facebook'),
            "first_name" => $userInfo['firstname'],
            "last_name" => $userInfo['lastname'],
            "address" => "No address added",
            "contact_no" => "No contact number added",
            "login_with" => "login_with_facebook"
        ]);
        $response = [
            "message" => "success",
            "data" => $data,
        ];
        return response($response, 201);
    }

    // function to save apple sign in data
    public function registerApple(Request $request)
    {
        return response()->json(
            [
                "message" => "success",
                "data" => "data apple sign in data token",
            ],
            201
        );
    }
    // password update for forget and change password function
    public function passwordUpdate(Request $request, $user)
    {
        $userInfo = $request->validate([
            "password" => "required|min:8"
        ]);

        try {
            $userUpdate = DB::update('update users set password = ?  where email = ?', [Hash::make($userInfo["password"]), $user]);
            if ($userUpdate) {
                return response()->json(["status" => 200, "message" => "password successfully reset"], 200);
            } else {
                return response()->json(["status" => 401, "message" => "something went wrong"], 401);
            }
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    public function verifyAccount(Request $request)
    {
        $otpValidation = $request->validate([
            "otp-code" => "required|min6"
        ]);
        return response()->json(
            [
                "status" => "success",
                "data" => $otpValidation
            ]
        );
    }
}
