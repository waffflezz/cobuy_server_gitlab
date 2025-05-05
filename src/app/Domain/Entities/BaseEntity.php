<?php

namespace App\Domain\Entities;

abstract class BaseEntity
{
    public function toArray(): array
    {
        $result = [];
        foreach (get_object_vars($this) as $key => $value) {
            $result[$this->camelToSnake($key)] = $this->convertValue($value);
        }
        return $result;
    }

    private function convertValue($value): mixed
    {
        if (is_array($value)) {
            return array_map([$this, 'convertValue'], $value);
        }

        if (is_object($value)) {
            if (method_exists($value, 'toArray')) {
                return $value->toArray();
            }
        }

        return $value;
    }

    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $input));
    }
}
