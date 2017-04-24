<?php

namespace WebModularity\LaravelCms\Http\Requests;

use DB;
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
        $emailUnique = $this->method() == 'POST'
            ? Rule::unique('people')->where(function ($query) {
                $query->leftJoin('users', 'users.person_id', '=', 'people.id')
                    ->whereNotNull('users.id');
            })
            : Rule::unique('people')->where(function ($query) {
                $query->leftJoin('users', 'users.person_id', '=', 'people.id')
                    ->whereNotNull('users.id');
            })
                ->ignore($this->person->id);

        return [
            'email' => [
                'required',
                'email',
                $emailUnique
            ],
            'role_id' => 'exists:user_roles,id',
            'login_methods.*' => 'loginMethod',
            'first_name' => 'max:255',
            'middle_name' => 'max:255',
            'last_name' => 'max:255',
            'address' => 'nullable|address',
            'phones.*' => 'nullable|phone'
        ];
    }
}
