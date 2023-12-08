<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Request as UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //register new user
    public function register(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
            'phone_number' => 'nullable|numeric|unique:users',
            'address' => 'nullable|string',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors(['validator' => implode(', ', $validator->errors()->all())]);
        }

        //create new user
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'type' => 'user',
            'email' => $request->email,
            'email_verified_at' => now(),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'remember_token' => Str::random(60),
        ]);

        //authenticate user
        Auth::login($user);

        //return token
        return redirect('/');
    }

    //login user
    public function login(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|',
        ]);

        //return validation errors
        if ($validator->fails()) {
            return back()->withInput()->withErrors(['validator' => implode(', ', $validator->errors()->all())]);
        }

        // Attempt to log the user in
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) 
            // Authentication passed...
            return redirect()->intended('/');
        else  // If authentication fails
            return back()->withInput()->withErrors(['username' => 'The provided credentials do not match our records.']);
    }

    //get user by user_id
    public function find(Request $request){
        //validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);

        //find user
        $user = User::where('id', $request->user_id)->first();

        return $user;
    }
}
?>