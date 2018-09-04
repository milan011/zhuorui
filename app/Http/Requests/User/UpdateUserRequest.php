<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

class UpdateUserRequest extends Request {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		// return auth()->user()->canDo('user.update');
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 * 更新用户验证规则
	 * @return array
	 */
	public function rules() {
		$id = $this->route('user');

		return [
			'name' => 'required',
			'name' => "unique:zr_users,name," . $id,
			'nick_name' => 'required',
			'nick_name' => "unique:zr_users,nick_name," . $id,
			'email' => 'required|email',
			// 'email' => "unique:zr_users,email," . $id,
			'telephone' => 'required|phone_number',
			// 'role_id' => 'required|numeric|min:1',
		];
	}

	/**
	 * Get the error messages for the defined validation rules.
	 * 验证失败信息提示
	 * @return array
	 */
	public function messages() {
		return [
			'name.required' => '请输入用户名',
			'name.unique' => '用户名重复',
			'nick_name.unique' => '实际姓名重复',
			'nick_name.required' => '请输入实际姓名',
			'telephone.required' => '请输入手机号码',
			'telephone.phone_number' => '手机号码无效',
			/*'role_id.required' => '请选择角色',
			'role_id.min' => '请选择角色',*/
			/*'email.required' => '请输入邮箱地址',
			'email.unique' => '邮箱地址已被使用',
			'email.email' => '邮箱格式错误',*/
		];
	}
}
