<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\Request;

class UpdateOrderRequest extends Request
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
     * 验证规则
     * @return array
     */
    public function rules()
    {

        return [
            'nick_name'      => 'required',
            'sh_name'        => 'required',
            'sh_telephone'   => 'required',
            'address'        => 'required',
            'user_id'        => 'required|min:1',
            'user_top_id'    => 'required|min:1',
            /*'description'    => 'required',
            'top_price'      => 'required',
            'bottom_price'   => 'required',
            'vin_code'       => 'required|alpha_num|size:17',*/
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * 验证失败信息提示
     * @return array
     */
    public function messages(){
        return [
            'nick_name.required'     => '请输入发件人',
            'sh_name.required'       => '请输入收件人',
            'sh_telephone.required'  => '请输入收件人电话',
            'address.required'       => '请输入收件人地址',
            'user_id.required'       => '请确认发件人',
            'user_top_id.required'   => '下单用户无CEO',
            'user_id.min'            => '请确认发件人',
            'user_top_id.min'        => '请确认总代',

        ];
    }
}
