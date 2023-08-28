<?php

namespace App\InterfaceAdapters\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Domain\Aggregates\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

/**
 * @group Authentication Endpoints
 *
 * API to Authenticate User
 */
class LoginController extends Controller
{
    /**
     * Login
     *
     * Logs in with mail and password and returns an API Bearer token
     *
     * @response 200 scenario="Success" {
     *     "token": "22|Vs1ZekychMxKmQsNlUEGW6pjFTwLCzQ1SxwxEzP1"
     * }
     * @response 401 scenario="Wrong Credentials" {"error": "Invalid Credentials"}
     * @responseField token The Bearer token of API
     */
    public function login(Request $request)
    {
        $request->validate([
            'mail' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('mail', 'password');

        // Gets driver type set in file config/auth.php
        $driver = Config::get('auth.providers.users.driver');

        // Uses a diferent authentication logic depending on if using LDAP driver or not
        if ($driver === 'ldap') {
            // LDAP authentication logic
            if (Auth::attempt($credentials)) {
                $user = Auth::getLastAttempted();
                return [
                    'token' => $user->createToken(time())->plainTextToken,
                ];
            }
            return response()->json(['error' => "Invalid Credentials"], 401);
        } elseif ($driver === 'eloquent') {
            // Eloquent authentication logic
            $user = User::where('email', $credentials['mail'])->first();
            if (Hash::check($credentials['password'], $user->getAuthPassword())) {
                return [
                    'token' => $user->createToken(time())->plainTextToken
                ];
            }
            return response()->json(['error' => "Invalid Credentials"], 401);
        } else {
            throw new Exception("Invalid user provider driver specified in configuration.");
        }
    }

    /**
     * Logout
     *
     * Logs out user by destroying it's token
     *
     * @unauthenticated
     * @response 200 scenario="Success" "logged out"
     */
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return "logged out";
    }

    /**
     * GetLoggedUser
     *
     * Gets data from current logged user
     *
     */
    public function getLoggedUser()
    {
        $user = Auth::user();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'admin' => $user->admin
        ];
    }
}
