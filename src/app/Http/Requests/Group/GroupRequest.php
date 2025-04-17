<?php

namespace App\Http\Requests\Group;

use App\Domain\DTO\FileDTO;
use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
{
    public function getFileDTO(): ?FileDTO
    {
        if (!$this->hasFile('image')) {
            return null;
        }

        $file = $this->file('image');

        return new FileDTO(
            $file->getClientOriginalName(),
            $file->getClientOriginalExtension(),
            $file->getRealPath(),
        );
    }
}
