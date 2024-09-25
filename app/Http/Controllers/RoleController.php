<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $suppliers = Supplier::all();
        return view('userCrud.index', compact('users', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:warehouse_manager,procurement_officer,cashier,supplier',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'supplier') {
            Supplier::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'supplier',
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'User Created Successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'role' => 'required|string|in:warehouse_manager,procurement_officer,cashier,supplier',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('roles.index')->with('success', 'User Updated Successfully.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('roles.index')->with('success', 'User Deleted Successfully.');
    }
}
