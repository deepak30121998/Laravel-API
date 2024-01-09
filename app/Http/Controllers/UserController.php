<?php

namespace App\Http\Controllers;

use App\Http\Requests\updateInfoRequest;
use App\Http\Requests\updatePasswordRequest;
use App\Http\Requests\updateUserRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class UserController extends Controller
{
    public function index()
    {
        Gate::authorize('view', 'users');
        $users = User::paginate(10);

        return UserResource::collection($users);
    }

    public function show($id)
    {
        Gate::authorize('view', 'users');
        $user = User::find($id);
        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        Gate::authorize('edit', 'users');
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->input('role_id'),
        ]);

        return response(['user' => new UserResource($user), 'message' => 'User Created Successfully!!'], 201);
    }

    public function update(updateUserRequest $request, $id)
    {
        Gate::authorize('edit', 'users');
        $user = User::find($id);
        
        if (!$user) {
            return response(['message' => 'User not found'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id')
        ]);

        return response(['user' => new UserResource($user), 'message' => 'User Updated Successfully!!'], HttpFoundationResponse::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'users');
        $user = User::find($id);

        $user->delete();

        return response(['message' => 'User deleted successfully!!'], HttpFoundationResponse::HTTP_NO_CONTENT);
    }

    public function user()
    {
        $user = Auth::user();
        return (new UserResource($user))->additional([
            'data' => [
                'permissions' => $user->permission()
            ]
        ]);
    }

    public function updateInfo(updateInfoRequest $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response(['message' => 'User not found'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(['user' => new UserResource($user), 'message' => 'User Updated Successfully!!'], HttpFoundationResponse::HTTP_ACCEPTED);
    }

    public function updatePassword(updatePasswordRequest $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response(['message' => 'User not found'], HttpFoundationResponse::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response(['user' => new UserResource($user), 'message' => 'User Password Updated Successfully!!'], HttpFoundationResponse::HTTP_ACCEPTED);
    }
}
