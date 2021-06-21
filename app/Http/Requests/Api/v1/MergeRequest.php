<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;

class MergeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'filename' => 'required|string|regex:/^[\d\w\-\_]+$/u|max:100',
            'files.*' => 'required|file|mimetypes:application/octet-stream',
        ];
    }
}
