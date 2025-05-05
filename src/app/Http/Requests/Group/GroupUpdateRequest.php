<?php

namespace App\Http\Requests\Group;

use App\Http\Requests\FileTrait\FileDTOTrait;
use Illuminate\Foundation\Http\FormRequest;

class GroupUpdateRequest extends FormRequest
{
    use FileDTOTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'image' => 'nullable|image|max:10240',
        ];
    }
}
