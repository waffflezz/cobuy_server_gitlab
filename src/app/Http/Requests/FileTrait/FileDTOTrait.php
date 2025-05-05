<?php

namespace App\Http\Requests\FileTrait;

use App\Domain\DTO\FileDTO;

trait FileDTOTrait
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
