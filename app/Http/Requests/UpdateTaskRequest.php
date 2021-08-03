<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required', 'string',
            ],
            'creator' => [
                'required', 'string',
            ],
            'description' => [
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
                'mimes:jpeg,png,bmp, mov, mp4, video/x-flv, video/mp4, application/x-mpegURL, video/MP2T, video/3gpp, video/quicktime, video/x-msvideo, video/x-ms-wmv',            
            ]
        ];
    }

    public function authorize()
    {
        return Gate::allows('task_access');
    }
}