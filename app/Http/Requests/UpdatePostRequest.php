<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post && $this->user()->id === $post->user_id;
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'You are not authorized to update this post.',
            'data' => null,
        ], 403));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title must not exceed 255 characters.',
            'content.required' => 'The content field is required.',
        ];
    }
}
