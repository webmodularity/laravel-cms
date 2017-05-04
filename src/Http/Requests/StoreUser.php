<?php

namespace WebModularity\LaravelCms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            ? 'userPersonUnique'
            : 'userPersonUnique:' . $this->route('user');

        $rules = [
            'email' => [
                'required',
                'email',
                $emailUnique
            ],
            'role_id' => 'exists:user_roles,id',
            'first_name' => 'max:255',
            'middle_name' => 'max:255',
            'last_name' => 'max:255',
            'avatar_url' => 'url|max:255',
            'address' => 'nullable|address',
            'phones.*' => 'nullable|phone'
        ];

        if ($this->method() == 'POST') {
            $rules['password'] = 'nullable|min:6|confirmed';
        }
        \Log::warning($this);
        return $rules;
    }
}
