<?php

namespace App\InterfaceAdapters\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Domain\Aggregates\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mail' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('mail', 'password');

        $driver = Config::get('auth.providers.users.driver');
        if ($driver === 'ldap') {
            // LDAP authentication logic
            if (Auth::attempt($credentials)) {
                $user = Auth::getLastAttempted();
                return [
                    'token' => $user->createToken(time())->plainTextToken
                ];
            }
            return "Invalid Credentials";
        } elseif ($driver === 'eloquent') {
            // Eloquent authentication logic
            $user = User::where('email', $credentials['mail'])->first();
            if (Hash::check($credentials['password'], $user->getAuthPassword())) {
                return [
                    'token' => $user->createToken(time())->plainTextToken
                ];
            }
        } else {
            throw new Exception("Invalid user provider driver specified in configuration.");
        }
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return "logged out";
    }
}
