<?php

namespace WebModularity\LaravelCms\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use WebModularity\LaravelUser\User;

class StoreUserSocialLogin extends FormRequest
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
        return [
            'email' => 'required|email',
            'social_provider_id' => 'required|exists:user_social_providers,id',
            'uid' => 'required|max:255',
            'avatar_url' => 'nullable|url|max:255'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::find(request('user_id'))->with('socialProviders');
            if (is_null($user) || $user->socialProviders->where('id', request('social_provider_id'))->count() > 0) {
                $validator->errors()->add('social_provider_id', 'A record already exists for this User/Social combo.');
            }
        });
    }
}
