<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class UsersController extends Controller
{
    private UsersService $UsersService;

    public function __construct() {
        $this->UsersService = new UsersService();
    }

    public function login(LoginRequest $request): JsonResponse {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = Auth::guard('api')->user();

        // Отримати роль користувача
        $role = $user->role;

        return $this->respondWithToken($token, $role);
    }

    public function createUser(RegisterRequest $request): JsonResponse {
        $data = $request->all();
        try {
            $this->UsersService->register($data);
        }
        catch (\Exception) {
            return response()->json(['error' => 'Failed created user'], 422);
        }

        return response()->json(['success' => 'User registered successfully'], 200);
    }

    public function createUserForAdmin(RegisterRequest $request): JsonResponse {
        $data = $request->all();
        try {
            $this->UsersService->register($data);
        }
        catch (\Exception) {
            return response()->json(['error' => 'Failed created admin'], 422);
        }

        return response()->json(['message' => 'User registered successfully']);
    }


    public function logout(): JsonResponse {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out']);
        }
        catch (JWTException) {
            return response()->json(['error' => 'Failed to logout, please try again.'], 500);
        }
    }

    protected function respondWithToken($token, $role = null): JsonResponse {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'role'         => $role,
        ]);
    }

    public function refreshToken(): JsonResponse {
        try {
            $newToken = Auth::guard('api')->refresh();

            return $this->respondWithToken($newToken);
        }
        catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Could not refresh token'], 500);
        }
    }

    public function getUsers(Request $request): JsonResponse {
        $page = $request->query('page', 1);

        $users = $this->UsersService->getUsers($page);

        return response()->json($users);
    }

    public function getUser(int $id): JsonResponse {
        $response = [];
        try {
            $user = $this->UsersService->getUserById($id);
        }
        catch (\Exception) {
            $response['error'] = 'Product not found';

            return response()->json($response, 404);
        }

        return response()->json($user);
    }

    public function updateUser(UpdateUserRequest $request): JsonResponse {
        $data     = $request->all();
        $response = [];
        try {
            $this->UsersService->updateUser($data);
        }
        catch (\Exception) {
            $response['error'] = 'User already exists';

            return response()->json($response, 422);
        }
        $response['message'] = 'User updated';

        return response()->json($response);
    }

    public function deleteUser(Request $request): JsonResponse {
        $response = [];
        $data     = $request->all();
        try {
            $this->UsersService->deleteUser($data['id']);
        }
        catch (\Exception) {
            $response['error'] = 'User not found';

            return response()->json($response, 422);
        }
        $response['message'] = 'User deleted';

        return response()->json($response);
    }
}
