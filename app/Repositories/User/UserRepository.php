<?php
namespace App\Repositories\User;

use App\Role;
use App\RoleUser;
use App\User;
use Auth;
use DB;
use Session;

class UserRepository implements UserRepositoryContract {

	//默认查询数据
	protected $select_columns = ['id', 'name', 'nick_name', 'telephone', 'email', 'wx_number', 'address', 'status', 'pid', 'level', 'created_at','remark'];

	// 获得用户信息
	public function find($id) {
		return User::with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
			->select($this->select_columns)
			->findOrFail($id);
	}

	public function getAllUsers($requestData = []) {
		/*return User::with(['hasOneShop'=>function($query){
			            $query->select('user_id','name','address');
		*/
		$query = new User(); // 返回的是一个User实例

		$query = $query->addCondition($requestData); //根据条件组合语句

		return $query->with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
			         ->select($this->select_columns)
			         ->paginate(10);
		// return User::with('hasOneShop')->paginate(10);
	}

    //根据用户角色获得用户
    public function getAllUsersByRole($role_id){

        /*$users = DB::table('yz_users')
                   // ->leftJoin('role_user', 'yz_users.id', '=', 'role_user.user_id')

                   // ->select('yz_users.*')
                   ->get();*/

        $users = DB::table('zr_users')
                    ->join('role_user', function ($join) use ($role_id){
                        $join->on('zr_users.id', '=', 'role_user.user_id')
                             ->where('role_user.role_id', '=', $role_id);
                        })
                    ->select('zr_users.id', 'zr_users.name', 'zr_users.nick_name')
                    ->get();

        return $users;
    }

	public function getAllUsersWithDepartments() {
		return User::select(array
			('users.name', 'users.id',
				DB::raw('CONCAT(users.name, " (", departments.name, ")") AS full_name')))
			->join('department_user', 'users.id', '=', 'department_user.user_id')
			->join('departments', 'department_user.department_id', '=', 'departments.id')
			->lists('full_name', 'id');
	}

    // 所有商户
    public function getAllMerchant(){

        return User::select($this->select_columns)
                   ->where('status', '1')
                   ->where('level', '>=', '0')
                   ->get();
    }

     // 获取总代、1、2、3级代理
    public function getAllSuppliers(){

        return User::select($this->select_columns)
                   ->whereIn('level', ['0', '1','2', '3',])
                   ->where('status', '=', '1')
                   ->get();
    }

	public function create($requestData) {

		// dd($requestData->all());
		$password = bcrypt($requestData->password);
		$role_id = $requestData->role_id;

		$role_info = Role::findOrFail($role_id);

		/*p($requestData->agents_total);
        p($requestData->agents_frist);
        dd($requestData->agents_secend);*/
        //设置用户pid
        /*if(!empty($requestData->agents_secend)){
            // 有二级代理
            $pid = $requestData->agents_secend;
        }else if(!empty($requestData->agents_frist)){
            // 有一级代理
            $pid = $requestData->agents_frist;
        }else{
            // 总代理
            $pid = $requestData->agents_total;
        }*/

		// 添加用户到用户表
		$input = array_replace($requestData->all(), ['password' => "$password", 'creater_id' => Auth::id(), 'level' => $role_info->level]);

		// dd($input);

		$user = User::create($input);

		// 关联用户表与用户-角色表
		$userRole = new RoleUser;
		$userRole->role_id = $role_id;
		$userRole->user_id = $user->id;
		$userRole->save();

		Session::flash('sucess', '添加用户成功');
		return $user;
	}

	public function update($id, $requestData) {
		// dd($requestData->all());
		$user = User::with(tableUnionDesign('hasManyRoles', ['roles.id', 'name', 'slug']))
			->findorFail($id);

		/*p($requestData->role_id);
        dd($user->hasManyRoles[0]->id);*/

		// $user->name = $requestData->name;
		$user->nick_name = $requestData->nick_name;
		$user->telephone = $requestData->telephone;
		$user->wx_number = $requestData->wx_number;
        $user->email     = $requestData->email;
		$user->remark    = $requestData->remark;

		// 更新用户
		$user->save();

		//如果角色有变化，更新UserRole表
		/*if ($requestData->role_id != $user->hasManyRoles[0]->id) {

			$user_id = $id; //当前用户ID
			$role_id = $user->hasManyRoles[0]->id; //角色ID
			// 获得需要更新的对象
			$user_role = RoleUser::where('user_id', $user_id)
				->where('role_id', $role_id)
				->first();
			// dd($requestData->role_id);
			$user_role->role_id = $requestData->role_id;

			$user_role->save();
		}*/

		Session()->flash('sucess', '更新用户成功');

		return $user;
	}

	public function destroy($id) {
		if ($id == 1) {
			return Session()->flash('faill', '超级管理员不允许删除');
		}
		try {
			$user = User::findorFail($id);
			// $user->delete();
            dd($user);
            // 修改用户状态
            $user->status    = '0';
            $user->save();      // 
			Session()->flash('sucess', '删除管理员成功');

		} catch (\Illuminate\Database\QueryException $e) {
			Session()->flash('faill', '删除管理员失败');
		}

	}

	//获得用户角色信息
	public function getRoleInfoById($id = '') {

		$role_id = '';

		if (empty($id)) {
			//若ID为空，则获得当前用户ID

			// dd(Auth::user()->hasManyRoles[0]->id);
			$role_id = Auth::user()->hasManyRoles[0]->id;
		} else {

			// dd(User::findOrFail($id)->hasManyRoles[0]->id);
			$role_id = User::findOrFail($id)->hasManyRoles[0]->id;
		}

		$role_info = Role::find($role_id);
		// dd($role_info);
		return $role_info;
	}

    //获得子代理
    public function getChildUser($user_id) {

        return User::select(['yz_users.id', 'yz_users.pid', 'yz_users.name', 'yz_users.nick_name', 'yz_users.level', 'roles.name as role_name'])
                   ->join('roles', 'yz_users.level', '=', 'roles.level')
                   ->where('pid', $user_id)
                   ->where('status', '1')
                   ->orderBy('yz_users.level', 'asc')
                   ->get();
    }

    //获得用户父用户
    protected function getParentUser($user_id) {

        $pid = User::select('id', 'pid')
            ->where('id', $user_id)
            ->first();
        // dd($pid->pid);
        return User::select(['yz_users.id', 'yz_users.pid', 'yz_users.name', 'yz_users.nick_name', 'yz_users.level', 'roles.name as role_name'])
            ->join('roles', 'yz_users.level', '=', 'roles.level')
            ->where('yz_users.id', $pid->pid)
            ->where('status', '1')
            ->orderBy('yz_users.level', 'asc')
            ->first();
    }

    //获得指定品牌的品牌树(递归获取该品牌所有子品牌及父品牌)
    public function getUserTree($user_id) {

        // p($user_id);
        $userTree['child']  = $this->getAllChild($user_id);
        $userTree['parent'] = $this->getAllParent($user_id);
        // dd($UserTree);
        return $userTree;
    }

    // 递归获得用户子用户
    public function getChildRecursive($user_id){

        $child = [];

        if ($this->haveChildUser($user_id)) {

            $user_info = $this->getChildUser($user_id)->toArray();
            // dd($user_info);
            foreach ($user_info as $key => $value) {

                $child[$key] = $value;
                $child[$key]['child'] = $this->getChildRecursive($value['id']);
            }

            /*foreach ($user_info as $key => $value) {

                $child = array_merge($child, $this->getChildRecursive($value['id']));
            }*/
        }

        return$child;
    }


    //获得指定品牌下所有子品牌
    protected function getAllChild($user_id, $lev = 1) {

        $child = array();

        if ($this->haveChildUser($user_id)) {

            $user_info = $this->getChildUser($user_id)->toArray();

            foreach ($user_info as $key => $value) {

                $child[$key] = $value;
                $child[$key]['lev'] = $lev;
            }

            foreach ($user_info as $key => $value) {

                $child = array_merge($child, $this->getAllChild($value['id'], $lev + 1));
            }
        }

        return $child;
    }

    //获得指定品牌的所有父品牌
    protected function getAllParent($user_id, $lev = 1) {

        $parent = array();
        // dd($User_id);
        // dd(!$this->isTopUser($User_id));
        // $user_info = $this->getParentUser('98');
        // dd(lastSql());
        // dd($User_info);
        if (!$this->isTopUser($user_id)) {
            // dd('hehe');
            $user_info = $this->getParentUser($user_id)->toArray();
            // $user_info = $this->getParentUser($user_id);
            /*p($user_id);
                p($user_info);
*/
            $user_info['lev'] = $lev;
            $parent[] = $user_info;

            $parent = array_merge($parent, $this->getAllParent($user_info['id'], $lev + 1));

        }
        // dd($parent);
        return $parent;
    }

    //判断该品牌是否有下级品牌
    protected function haveChildUser($user_id) {

        $child = $this->getChildUser($user_id);
        /*p(lastSql());
        dd($child);*/
        if ($child->count() != 0) {

            return true;
        } else {

            return false;
        }
    }

    //判断该品牌是否为顶级品牌
    protected function isTopUser($user_id) {

        $pid = User::select('id','pid')->find($user_id);
        /*p(lastSql());
        dd($pid);*/
        if ($pid->pid == 0) {
            return true;
        } else {
            return false;
        }
    }

    //获得用户父用户
    public function getAgentsParent($role_level) {

        // p($role_level);exit;

        return User::select('id','nick_name', 'pid')
            ->where('level', '<', $role_level)
            ->where('level', '>=', '0')
            ->get();
    }
}
