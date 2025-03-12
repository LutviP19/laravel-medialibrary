<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlbumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->tokenCan("update");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('album')->id;
        return [
            'user_ulid' => 'exists:users,ulid',
            'name' => 'required|unique:albums,name,'.$id.',id',
            'description' => 'required',
            'image' => 'url|ends_with:jpg,jpeg,png',
        ];
    }
}
