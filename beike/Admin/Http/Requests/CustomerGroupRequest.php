<?php


namespace Beike\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerGroupRequest extends FormRequest
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
            'name.*' => 'required|max:64',
            'level'  => 'required|max:16',
        ];
    }

    public function attributes()
    {
        return [
            'descriptions.*.name' => trans('customer_group.name'),
            'level'               => trans('customer_group.level'),
        ];
    }
}
