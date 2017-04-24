<?php

namespace WebModularity\LaravelCms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //$emailUnique = $this->method() == 'POST'
        //    ? Rule::unique('people')
        //    : Rule::unique('people')->ignore($this->person->id);

        return [
            'email' => 'required|email',
            'role_id' => 'exists:user_roles,id',
            'login_methods.*' => 'login-method',
            'first_name' => 'max:255',
            'middle_name' => 'max:255',
            'last_name' => 'max:255',
            'address' => 'nullable|address',
            'phones.*' => 'nullable|phone'
        ];
    }
}
