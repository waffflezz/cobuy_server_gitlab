<?php

namespace App\Http\Controllers\API\Auth;

use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UserNotFoundException;
use App\Domain\UseCase\Auth\RegisterUseCaseInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\LoginUserResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterUseCaseInterface $registerUseCase,
    ) {}

    public function register(RegisterRequest $request): UserResource
    {
        $request->validated();

        $user = $this->registerUseCase->register(
            $request->name,
            $request->email,
            $request->password
        );

        return new UserResource($user);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request): LoginUserResource
    {
        $request->validated();

        try {
            $loginResult = $this->registerUseCase->login(
                $request->email,
                $request->password,
            );

            return new LoginUserResource($loginResult);
        } catch (UserNotFoundException $exception) {
            throw ValidationException::withMessages([
                'email' => $exception->getMessage(),
            ]);
        } catch (InvalidCredentialsException $exception) {
            throw ValidationException::withMessages([
                'password' => $exception->getMessage(),
            ]);
        }
    }

    public function logout(): JsonResponse
    {
        $this->registerUseCase->logout(Auth::id());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function loginCheck(): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }
}
