<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class UserManagementController extends Controller
{

    public function index1()
    {
        $users = User::all();
        return view('admin.userrole.index', ['users' => $users]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search input

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%"); // Assuming there's a role field, adjust if needed
                // Add more fields if necessary, like filtering by other user-related attributes
            })
            ->get(); // Fetch filtered users

        return view('admin.userrole.index', [
            'users' => $users,
            'search' => $search, // Pass the search term back to the view
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
            'password' => 'required|min:5|max:15',
            // 'confirm_password' => 'required|same:password'
        ]);


        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
            'password' => Hash::make($request['password'])
        ]);

        session()->flash('success', 'User added successfully.');
        return redirect()->route('admin.userrole.index');

       // return redirect()->route('admin.userrole.index')->with('success', 'User added successfully.');
    }

    public function Update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required',
            'password' => 'nullable|min:5|max:15',
            'confirm_password' => 'nullable|same:password'
        ]);

        $user = User::findOrFail($id);

        // Prevent the logged-in admin from changing their own role
        if (auth()->user()->id === $user->id && $request->role !== 'admin') {
            session()->flash('error', 'You cannot change your own role.');
            return redirect()->back();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        session()->flash('success', 'User updated successfully.');
        return redirect()->route('admin.userrole.index');
    }




    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting their own account
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.userrole.index')->with('error', 'You cannot delete your own account.');

        }

        $user->delete();
        session()->flash('success', 'User deleted successfully.');
        return redirect()->route('admin.userrole.index');
    }
}
