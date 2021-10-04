<?php

namespace App\Http\Controllers;

use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Validation\ValidationException;
// use Symfony\Component\VarDumper\Cloner\Data;

class UserController extends Controller
{
    //
    public function index()
    {
        return view('welcome');
    }
    public function getAllUser()
    {
        $allUser = DB::table('users')->get();
        return response()->json([
            "message" => "success",
            "data" => $allUser
        ]);
    }
    // get user infor on login in the app
    public function getLoggedIn_Info(Request $request)
    {
        $loggeIn = User::where('username', $request->input('username'))->orWhere('email', $request->input('username'))->get();
        if ($loggeIn->isEmpty()) {
            return response()->json([
                "status" => "error",
                "message" => "We cant find your username or email. Try to use another one."
            ], 404);
        } else {
            foreach ($loggeIn as $userInfo) {
                return response()->json([
                    "status" => "success",
                    "data" => $userInfo
                ], 200);
            }
        }
    }
}
