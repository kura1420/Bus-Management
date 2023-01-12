<?php

namespace App\Http\Requests;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->segment(3);

        $rules = Admin::$rules;

        $rules['email'] = $rules['email'] . ',' . $id;
        $rules['username'] = $rules['username'] . ',' . $id;
        $rules['password'] = $rules['password'] . '|nullable';

        return $rules;
    }
}
