<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => 'required|file|mimetypes:application/octet-stream',
        ];
    }
}
