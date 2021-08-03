<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\RequiredIf;
class StoreTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required', 'string',
            ],
            'priority' => [
                'required', 'string',
            ],
            'occurrence' => [
                'required', 'string',
            ],
            'daily' => [
                new RequiredIf($this->occurrence == 'Daily'),
                'date_format:H:i',
            ],
            'weekly' => [
                new RequiredIf($this->occurrence == 'Weekly'),
                'date_format:Y-m-d\TH:i',            
            ],
            'img_path' => [
                new RequiredIf($this->image != ''),
                'mimes:jpeg,png,bmp,mp4,mov,ogg,qt,'
            ]
        ];
    }
}
