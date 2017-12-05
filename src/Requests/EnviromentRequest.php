<?php

namespace RachidLaasri\LaravelInstaller\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnviromentRequest extends FormRequest
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
        return config('installer.environment.form.rules');
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];
    }
}
