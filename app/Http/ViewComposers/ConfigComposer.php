<?php
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;

/**
 * 基于类的实现方式
 */
class ConfigComposer {
	/**
	 * 共享配置数据
	 * @date   2017-01-06
	 * @author wcg
	 * @param  View       $view [description]
	 * @return [type]           [description]
	 */
	public function compose(View $view) {
        $user_role_type   = config('zhuorui.user_role_type'); //获取配置文件角色配置
		$package_year     = config('zhuorui.package_year'); //获取配置文件陶春年配置
		$package_month    = config('zhuorui.package_month'); //获取配置文件套餐月配置
		$return_month     = config('zhuorui.return_month'); //获取配置文件套餐月返还配置
		$package_status   = config('zhuorui.package_status'); //获取配置文件套餐状态配置
		$info_status      = config('zhuorui.info_status'); //获取配置文件付款方式配置
		$collections_type = config('zhuorui.collections_type'); //获取配置文件付款方式配置

        $view->with('user_role_type', $user_role_type);
		$view->with('package_year', $package_year);
		$view->with('package_month', $package_month);
		$view->with('return_month', $return_month);
		$view->with('package_status', $package_status);
		$view->with('collections_type', $collections_type);
		$view->with('info_status', $info_status);
	}
}