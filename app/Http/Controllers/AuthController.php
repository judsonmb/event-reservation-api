<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(CreateUserRequest $request)
    {
        $response = $this->authService->register($request->validated());

        return response()->json($response, 201);
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request->validated());

        return response()->json($response);
    }
}
