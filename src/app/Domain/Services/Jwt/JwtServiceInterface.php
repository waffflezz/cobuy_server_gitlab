<?php

namespace App\Domain\Services\Jwt;

interface JwtServiceInterface
{
    public function isValid(string $token, string $id): bool;
    public function getToken(string $id, array $data): string;
    public function getValidatePayload(string $token, string $id, string $payloadKey): string;
}
