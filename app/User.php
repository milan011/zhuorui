<?php
namespace App;

use Auth;
use Fenos\Notifynder\Notifable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPZen\LaravelRbac\Traits\Rbac;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, Rbac, Notifable, SoftDeletes;

	/**
	 * The database table used by the model.
	 * 定义表名及主键
	 * 淘车乐管理员表
	 * @var string
	 */
	// protected $table = 'users';
	protected $table = 'zr_users';
	protected $primaryKey = 'id';

	/**
	 * The attributes that are mass assignable.
	 * 批量赋值属性
	 * @var array
	 */
	// protected $fillable = ['name', 'email', 'password', 'address', 'personal_number', 'work_number', 'image_path'];
	protected $fillable = ['name', 'nick_name', 'password', 'telephone', 'wx_number', 'address', 'creater_id', 'status', 'email',  'created_at',  'remark'];

	/**
	 * The attributes excluded from the model's JSON form.
	 * 隐藏属性
	 * @var array
	 */
	// protected $dates = ['trial_ends_at', 'subscription_ends_at'];
	// protected $hidden = ['password', 'password_confirmation', 'remember_token'];
	protected $hidden = [ //在模型数组或 JSON 显示中隐藏某些属性
		'password', 'remember_token',
	];

	/**
	 * 应该被调整为日期的属性
	 * 定义软删除
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	// 搜索条件处理
	public function addCondition($requestData) {

		$query = $this;

		/*if ($is_self) {

			if (!(Auth::user()->isSuperAdmin())) {

				if (Auth::user()->isMdLeader()) {
					//店长
					$user_shop_id = Auth::user()->shop_id; //用户所属门店id
					// $this->where('shop_id', $user_shop_id);
					$query = $query->where('shop_id', $user_shop_id);
				} else {
					//店员
					// $this->where('creater_id', Auth::id());
					$query = $query->where('creater_id', Auth::id());
				}
			}
		}*/

		if (!empty($requestData['status'])) {
            //有用户状态选项
            $query = $query->where('status', $requestData['status']);

        } else {
            $query = $query->where('status', '1');
        }

		//if(isset($requestData['car_status']) && $requestData['car_status'] != ''){
		/*

		if (!empty($requestData['gearbox'])) {
			// dd($requestData['gearbox']);
			$query = $query->where(function ($query) use ($requestData) {
				foreach ($requestData['gearbox'] as $key => $gear) {
					$query = $query->orWhere('gearbox', $gear);
				}
			});
		}

		if (!empty($requestData['shop_id'])) {

			$query = $query->where('shop_id', $requestData['shop_id']);
		}

		if (!empty($requestData['is_appraiser'])) {

			$query = $query->where('is_appraiser', $requestData['is_appraiser']);
		}

		if (!empty($requestData['sale_number'])) {

			$query = $query->where('sale_number', $requestData['sale_number']);
		}

		if (!empty($requestData['out_color'])) {

			$query = $query->where('out_color', $requestData['out_color']);
		}

		if (!empty($requestData['capacity'])) {

			$query = $query->where('capacity', $requestData['capacity']);
		}

		if (!empty($requestData['category_type'])) {

			$query = $query->where('categorey_type', $requestData['category_type']);
		}

		if (!empty($requestData['category_id'])) {

			$query = $query->where('category_id', $requestData['category_id']);
		} else {

			if (!empty($requestData['car_factory'])) {
				$query = $query->where('car_factory', $requestData['car_factory']);
			} else {

				if (!empty($requestData['brand_id'])) {
					$query = $query->where('brand_id', $requestData['brand_id']);
				}
			}
		}

		if (!empty($requestData['begin_mileage'])) {
			$query = $query->where('mileage', '>=', $requestData['begin_mileage']);
		}

		if (!empty($requestData['end_mileage'])) {
			$query = $query->where('mileage', '<=', $requestData['end_mileage']);
		}

		if (!empty($requestData['top_price'])) {
			$query = $query->where('top_price', '<=', $requestData['top_price']);
		}

		if (!empty($requestData['bottom_price'])) {
			$query = $query->where('top_price', '>=', $requestData['bottom_price']);
		}

		if (!empty($requestData['end_date'])) {
			$query = $query->where('created_at', '<=', $requestData['end_date']);
		}

		if (!empty($requestData['begin_date'])) {
			$query = $query->where('created_at', '>=', $requestData['begin_date']);
		}

		if (!empty($requestData['need_follow'])) {
			$query = $query->where('updated_at', '<=', $requestData['need_follow']);
		}*/

		return $query;
	}

	// 是否超级管理员
	public function isSuperAdmin() {

		// return Auth::id() === 1;
		$user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id

		return ($user_role_id == config('tcl.user_role_type')['超级管理员']) || ($user_role_id == config('tcl.user_role_type')['总部管理员']);
	}

	// 是否店长
	public function isMdLeader() {

		$user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id
		// $user_role_id  = '6';
		return $user_role_id == config('tcl.user_role_type')['门店店长'];
	}

	// 是否贷款主管
	public function isDkLeader() {

		$user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id
		// $user_role_id  = '6';
		return $user_role_id == config('tcl.user_role_type')['贷款主管'];
	}

	// 是否保险主管
	public function isBxLeader() {

		$user_role_id = Auth::user()->hasManyUserRole[0]->role_id; //用户角色id
		// $user_role_id  = '6';
		return $user_role_id == config('tcl.user_role_type')['保险主管'];
	}

	public function tasksAssign() {
		return $this->hasMany('App\Tasks', 'fk_user_id_assign', 'id')
			->where('status', 1)
			->orderBy('deadline', 'asc');
	}
	public function tasksCreated() {
		return $this->hasMany('App\Tasks', 'fk_user_id_created', 'id')->limit(10);
	}

	public function tasksCompleted() {
		return $this->hasMany('App\Tasks', 'fk_user_id_assign', 'id')->where('status', 2);
	}

	public function tasksAll() {
		return $this->hasMany('App\Tasks', 'fk_user_id_assign', 'id')->whereIn('status', [1, 2]);
	}

	// 定义User表与role_user表一对多关系
	public function hasManyUserRole() {
		return $this->hasMany('App\RoleUser', 'user_id', 'id');
	}

	// 定义User表与role表多对多关系
	public function hasManyRoles() {
		return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
	}

	// 定义User表与order表一对多关系
	public function hasManyUser() {

		return $this->hasMany('App\Order', 'user_id', 'id');
	}

	// 定义User表与order表一对多关系
	public function hasManyUserWithTopUser() {

		return $this->hasMany('App\Order', 'user_top_id', 'id');
	}

	// 定义User表与Package表一对多关系
	public function hasManyCreater() {

		return $this->hasMany('App\Package', 'creater_id', 'id');
	}

	// 定义User表与InfoSelf表一对多关系
	public function hasManyCreaterInfoSelf() {

		return $this->hasMany('App\InfoSelf', 'creater_id', 'id');
	}

	// 定义User表与InfoDianxin表一对多关系
	public function hasManyCreaterInfoDianxin() {

		return $this->hasMany('App\InfoDianxin', 'creater_id', 'id');
	}
}
