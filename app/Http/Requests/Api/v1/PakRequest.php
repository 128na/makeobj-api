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
            'size' => 'required|integer|min:16|max:1024',
            'debug' => 'nullable|bool',
            'images.*' => 'required|file|mimetypes:image/png',
            'imageUrls.*.filename' => 'required|string|regex:/^[\d\w\-\_]+\.png$/u|max:100',
            'imageUrls.*.url' => 'required|url',
        ];
    }
}
