<?php

namespace Modules\Aminity\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AminityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (Auth::guard('admin')->check() && checkAdminHasPermission('listing.aminity.store')) ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:190',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('admin_validation.The title field is required.'),
            'title.string' => trans('admin_validation.The title must be a string.'),
            'title.max' => trans('admin_validation.The title may not be greater than 255 characters.'),
        ];
    }
}
