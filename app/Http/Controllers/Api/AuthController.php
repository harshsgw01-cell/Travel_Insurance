<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'password' => Hash::make($request->string('password')),
        ]);

        $role = Role::findOrCreate($request->input('role', 'Customer'));
        $user->assignRole($role);

        return $this->success('User registered successfully', [
            'user' => $user->load('roles'),
            'token' => $user->createToken('api')->plainTextToken,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->validated())) {
            return $this->error('Invalid login credentials', null, 401);
        }

        $user = $request->user();

        return $this->success('Login successful', [
            'user' => $user->load('roles'),
            'token' => $user->createToken('api')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return $this->success('Logout successful');
    }
}
