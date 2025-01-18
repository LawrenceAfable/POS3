<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|max:15',
            'confirm_password' => 'required|same:password'
        ]);


        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        return view('auth.login');
    }

    public function loginUser(Request $request)
    {
        // Validate the login form input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:15',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();  // Correct: Auth::user() returns the logged-in user

            // Role-based redirection
            if ($user->role === 'admin') {
                return redirect('/dashboard')->with('success', 'Welcome, Admin!');
            } elseif ($user->role === 'cashier') {
                return redirect('/pos')->with('success', 'Welcome to the POS System!');
            }

            // Default redirect if the role doesn't match
            return redirect('/')->with('success', 'Logged in successfully!');
        } else {
            // Authentication failed
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }

    }

    public function update(Request $request, $id)
    {
        // Validate the data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Ensures the email is unique except the user being updated
        ]);

        // Find the user by the given ID
        $user = User::findOrFail($id);

        // Update the user's details
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save(); // Save changes to the database

        // Redirect back with success message
        return redirect()->route('users.profile')->with('success', 'Profile updated successfully!');
    }


}
