<?php

namespace App\Infrastructure\Services;

use App\Domain\Exceptions\InvalidJwtTokenException;
use App\Domain\Exceptions\InvalidTokenException;
use App\Domain\Services\Jwt\JwtServiceInterface;
use STS\JWT\Facades\JWT;

class JwtService implements JwtServiceInterface
{
    public function isValid(string $token, string $id): bool
    {
        return JWT::parse($token)->isValid($id);
    }

    public function getToken(string $id, array $data): string
    {
        return JWT::get($id, $data);
    }

    /**
     * @throws InvalidJwtTokenException
     */
    public function getValidatePayload(string $token, string $id, string $payloadKey): string
    {
        try {
            $token = JWT::parse($token)->validate($id);
        } catch (\Exception $e) {
            throw new InvalidJwtTokenException('Token is invalid');
        }

        return $token->getPayload()[$payloadKey];
    }
}
