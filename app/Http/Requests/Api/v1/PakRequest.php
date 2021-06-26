<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class PakRequest extends FormRequest
{
    public function rules()
    {
        return [
            'filename' => 'required|string|regex:/^[\d\w\-\_]+$/u|max:100',
            'dat' => 'required|string|max:65535',
            'images' => 'array|min:1',
            'images.*' => 'required|file|mimetypes:image/png',
            'size' => 'required|integer|min:16|max:1024',
            'debug' => 'nullable|bool',
        ];
    }
}
