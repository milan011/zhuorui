<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
/*use App\Tasks;
use App\Shop;*/
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Repositories\Role\RoleRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use App\User;
use App\Role;
use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;

class UserController extends Controller {
	protected $users;
	protected $roles;

	public function __construct(
		UserRepositoryContract $users,
		RoleRepositoryContract $roles
	) {

		$this->users = $users;
		$this->roles = $roles;
		// $this->middleware('user.create', ['only' => ['create']]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		// dd(Auth::user());
		// 获取所有用户
		$users = $this->users->getAllUsers();
		// dd($users);
		// $user_children = $this->users->getChildRecursive(5);
		// dd($user_children);

		return view('admin.user.index', compact(

			'users'
		));

		// return view('admin.user.index');
	}

	/**
	 * Show the form for creating a new resource.
	 * 创建用户
	 * @return Response
	 */
	public function create() {

		// $agents_level = config('yazan.agents_level');
		//获得当前用户角色id
		//$user_role_id = $this->users->getRoleInfoById()->id;
		// dd($user_role_id);

		// 允许当前用户添加的角色列表
		//$role_add_allow = $this->roles->getAllowList($user_role_id);
		// p($role_add_allow);

		//所有总代、一级代理、二级代理
		// $agents_total = $this->users->getAllSuppliers();
		// dd(lastSql());
		// dd($agents_total);

		/*$role_add_allow = array(

			'2' => '管理员',
			'3' => '总代理',
			'4' => '一级代理',
			'5' => '二级代理',
			'6' => '三级代理',
			'7' => '零售客户',
		);*/

		/*$role_add_allow = Role::select('id', 'name', 'level')
							  ->where('level', '>=', '0')
							  ->get();*/
		// dd($role_add_allow);
		return view('admin.user.create');
	}

	//获得用户子代理(非递归)
    public function getChildUser(Request $request){


        $user_id = $request->input('pid');
        
        $junior_users = $this->users->getChildUser($user_id);

        /*p($junior_users[0]->toArray());
        p($junior_users->toJson());exit;*/

        if($junior_users->count() > 0){

            return response()->json(array(
                'status' => 1,
                'data'   => $junior_users,
                'message'   => '获取品牌列表成功'
            ));
        }else{

            return response()->json(array(
                'status' => 0,
                'message'   => '该用户无子代理'
            ));
        }        
    }

    //获得用户角色可属上级
    public function getParentAgents(Request $request){


        $role_level = $request->input('role_level');

        // p($role_level);exit;
        
        $junior_users = $this->users->getAgentsParent($role_level);

        // p($junior_users->toArray());exit;
        /*p($junior_users[0]->toArray());
        p($junior_users->toJson());exit;*/

        if($junior_users->count() > 0){

            return response()->json(array(
                'status' => 1,
                'data'   => $junior_users,
                'message'   => '获取代理成功'
            ));
        }else{

            return response()->json(array(
                'status' => 0,
                'message'   => '获取代理失败'
            ));
        }        
    }

    /**
     * 获得代理链
     */
    public function getUserChain(Request $request){

    	// p($request->all());exit;

    	$user_chains = $this->users->getUserTree($request->user_id);
    	$self = $this->users->find($request->user_id);
    	// p($self->hasManyRoles);exit;

    	$self_user['role_name'] = $self->hasManyRoles[0]->name;
    	$self_user['nick_name'] = $self->nick_name;
    	$self_user['level']     = $self->level;
    	$self_user['telephone'] = $self->telephone;
    	// p($user_chains['parent']);exit;
    	foreach ($user_chains['parent'] as $key => $value) {
    		if($value['level'] == '0'){
    			$self_user['user_top_id'] = $value['id'];
    		}
    	}
    	/*p($user_chains);exit;
        p(collect($user_chains)->toJson());exit;*/

        if(collect($user_chains)->count() > 0){
        	
            return response()->json(array(
                'status' => 1,
                'data'   => $user_chains,
                'self'   => $self_user,
                'message'   => '获取代理列表成功'
            ));
        }else{
        	
            return response()->json(array(
                'status' => 0,
                'message'   => '该代理商无代理用户'
            ));
        }
    }

    public function address(Request $request){

    	dd($request->all());
    }

	/**
	 * Store a newly created resource in storage.
	 * 保存用户
	 * @param User $user
	 * @return Response
	 */
	public function store(StoreUserRequest $userRequest) {
		// dd('hehe');
		// dd($userRequest->all());
		$getInsertedId = $this->users->create($userRequest);
		return redirect()->route('user.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {

		// dd($this->users->find($id));
		return view('admin.user.show')->with('user',$this->users->find($id));
		
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id) {
		//获得当前用户角色id
		/*$user_role_id = $this->users->getRoleInfoById()->id;
		// dd($user_role_id);

		// 允许当前用户添加的角色列表
		$role_add_allow = $this->roles->getAllowList($user_role_id);
		// p($role_add_allow);

		// 允许当前用户添加的门店列表
		$shop_id = Auth::user()->shop_id;
		if ($shop_id != 1) {

			$shop_add_allow = Shop::where('id', $shop_id)->select(['id', 'name'])->get();
		} else {

			$shop_add_allow = Shop::select(['id', 'name'])->get();
		}*/

		$user = $this->users->find($id);
		// dd($user);

		return view('admin.user.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $userRequest) {

		$this->users->update($id, $userRequest);

		return redirect()->route('user.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		
		$this->users->destroy($id);

		return redirect()->route('user.index');
	}

	public function resetPassword() {

		return view('auth.reset');
	}

	public function resetPass(Request $request) {
		// dd($request->user());
		$oldpassword = $request->input('oldpassword');
		$password = $request->input('password');
		$data = $request->all();
		$rules = [
			'oldpassword' => 'required|between:6,20',
			'password' => 'required|between:6,20|confirmed',
		];
		$messages = [
			'required' => '密码不能为空',
			'between' => '密码必须是6~20位之间',
			'confirmed' => '新密码和确认密码不匹配',
		];
		$validator = Validator::make($data, $rules, $messages);
		$user = Auth::user();
		// dd($user);
		$validator->after(function ($validator) use ($oldpassword, $user) {
			if (!\Hash::check($oldpassword, $user->password)) {
				$validator->errors()->add('oldpassword', '原密码错误');
			}
		});
		if ($validator->fails()) {
			return back()->withErrors($validator); //返回一次性错误
		}
		$user->password = bcrypt($password);
		$user->save();

		Auth::logout(); //更改完这次密码后，退出这个用户

		return redirect('login');
	}
}
