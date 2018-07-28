<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
 */

Route::group(['middleware' => 'web'], function () {
	Route::get('login', 'Auth\AuthController@showLoginForm');
	Route::post('login', 'Auth\AuthController@login');
	Route::get('logout', 'Auth\AuthController@logout');
	Route::get('resetPassword', 'Admin\UserController@resetPassword')->name('admin.user.resetPass');
	Route::post('resetPass', 'Admin\UserController@resetPass')->name('admin.user.reset');
	Route::auth();
});

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'Admin'], function () {

	Route::get('/', 'HomeController@index')->name('admin.index');
	//商户管理index
	Route::match(['get', 'post'], 'user/index', 'UserController@index')->name('user.index');
	//客户经理管理index
	Route::match(['get', 'post'], 'manager/index', 'ManagerController@index')->name('manager.index');
	//自输入管理index
	Route::match(['get', 'post'], 'infoSelf/index', 'InfoSelfController@index')->name('infoSelf.index');
	//套餐管理index
	Route::match(['get', 'post'], 'package/index', 'PackageController@index')->name('package.index');
	//电信导入信息管理index
	Route::match(['get', 'post'], 'infoDianxin/index', 'InfoDianxinController@index')->name('infoDianxin.index');
	Route::post('infoDianxin/checkRepeat', 'InfoDianxinController@checkRepeat')->name('infoDianxin.checkRepeat');
	
	//ajax删除订单商品
	/*Route::post('infoSelfGoods/ajaxDelete', 'infoSelfPackageController@ajaxDelete')->name('infoSelfGoods.ajaxDelete');
	Route::post('goodsPrice/ajaxUpdatePrice', 'GoodsPriceController@ajaxUpdatePrice')->name('goodsPrice.ajaxUpdatePrice');*/
	//导出订单
	Route::post('infoDianxin/import', 'infoDianxinController@import')->name('infoDianxin.import'); //Excel导入
	// Route::post('excel/export','ExcelController@export')->name('infoSelf.export'); //Excel路由

	Route::get('role/{id}/editPermission', 'RoleController@editPermission')->name('role.editPermission');    
    Route::put('role/updatePermission', 'RoleController@updatePermission')->name('role.updatePermission');

	Route::resource('user', 'UserController'); //用户管理资源路由
	Route::resource('infoDianxin', 'InfoDianxinController'); //电信导入管理资源路由
	Route::resource('infoSelf', 'InfoSelfController'); //自录入管理资源路由
	Route::resource('package', 'PackageController'); //套餐管理资源路由
	Route::resource('manager', 'ManagerController'); //客户经理管理资源路由
	Route::resource('role', 'RoleController');  //角色管理资源路由
    Route::resource('permission', 'PermissionController'); //权限管理资源路由

});
