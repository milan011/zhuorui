<?php
namespace App\Repositories\InfoSelf;

use App\InfoSelf;
use App\Manager;

use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Planbon;
use PHPZen\LaravelRbac\Traits\Rbac;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;

class InfoSelfRepository implements InfoSelfRepositoryContract
{
    //默认查询数据
    protected $select_columns = ['code', 'name', 'user_telephone', 'old_bind', 'manage_name', 'manage_telephone', 'manage_id', 'project_name', 'new_telephone', 'uim_number', 'side_number', 'netin', 'collections', 'balance_month', 'collections_type', 'creater_id', 'status','remark'];


    // 根据ID获得约车信息
    public function find($id)
    {
        return Plan::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得约车列表
    public function getAllInfos($request)
    {   
        // dd($request->Plan_launch);
        // $query = Plan::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoSelf();       // 返回的是一个Plan实例,两种方法均可
        // dd($request->all());
        $query = $query->addCondition($request->all()); //根据条件组合语句

        // $query = $query->chacneLaunch($request->Plan_launch);

        return $query->select($this->select_columns)
                     ->orderBy('created_at', 'DESC')
                     ->paginate(10);
    }

    // 创建信息
    public function create($requestData)
    {             
            $requestData['creater_id']     = Auth::id();
    
            $info   = new infoSelf();

            //获得客户经理信息
            $manager = Manager::findOrFail($requestData['manager']);
            
            // dd($manager);
            // 处理副卡信息
            // dd($requestData->all());
            if (!empty($requestData['side_numbers'])){

                $side_number = implode("|",  array_unique($requestData['side_numbers']));
            }
            

            // dd($side_number);

            $requestData['code']             = getInfoCode();
            $requestData['manage_name']      = $manager->name;
            $requestData['manage_id']        = $manager->id;
            $requestData['manage_telephone'] = $manager->telephone;
            $requestData['side_number']      = $side_number;
            $requestData['netin']            = $requestData['netin_year'].$requestData['netin_moth'];
            $requestData['old_bind']         = isset($requestData['old_bind']) ? '1' : '0';



            // dd($requestData->all());

            $input  =  array_replace($requestData->all());
            
            $info->fill($input);

            $info = $info->create($input);

            return $info;     
    }

    // 发起约车
    public function planLaunch($requestData)
    {   
        $chance = Chance::findOrFail($requestData->chance_id);

        if($requestData->chance_status == 1){
            // 将对应的销售机会状态设置为待确认状态           
            $chance->status = '2';            
        }elseif($requestData->chance_status == 2){
            // 将对应的销售机会状态设置为约车状态           
            $chance->status = '3';          
        }        

        $chance->save();

        return $chance;    
    }

    // 看车结果反馈
    public function update($requestData, $id)
    {   
        /**
        * 处理逻辑：
        * 1、看车后有购买意向，则转为订车操作，对应车源、求购、销售机会状态设置为订车   
        * 2、看车失败或没有看车，则该约车信息被废弃、对应销售机会也被废弃，对应车源信息和客源信息转为正常状态，
        *   可以再次被匹配并发起约车。    
        */
        // dd($requestData->all());
        /*$plan   = Plan::select($this->select_columns)->findorFail($id); //约车对象

        $plan->status = '0';
        $plan->plan_remark = $requestData->plan_remark;

        Session::flash('sucess', '革命尚未成功，同志仍需努力');
        $plan->save();

        return $plan;*/

        DB::transaction(function() use ($requestData, $id){

            $plan   = Plan::select($this->select_columns)->findorFail($id); //约车对象
            $chance = Chance::findOrFail($plan->chance_id);
            $car    = Cars::findOrFail($chance->car_id);
            $want   = Want::findOrFail($chance->want_id);

            //dd($plan);
            // dd($chance);
            // dd($car);
            // dd($want);

            // 看车失败
            $car->car_status   = '1';
            $want->want_status = '1';
            $chance->status    = '0';
            $plan->status = '0';
            $plan->plan_remark = $requestData->plan_remark;
            Session::flash('sucess', '革命尚未成功，同志仍需努力');

            $car->save();
            $want->save();
            $chance->save();
            $plan->save();

            return $plan;           
        });           
    }

    // 删除约车
    public function destroy($id)
    {
        try {
            $Plan = Plan::findorFail($id);
            $Plan->delete();
            Session::flash('sucess', '删除约车成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除约车失败');
        }      
    }

    //判断约车是否重复
    public function isRepeat($requestData){

        $car_id  = $requestData->car_id;
        $want_id = $requestData->want_id;

        return Plan::select(['id','Plan_code', 'car_id', 'want_id', 'car_customer_id', 'want_customer_id', 'car_creater', 'want_creater', 'creater', 'status'])
                       ->where('car_id', $car_id)
                       ->where('want_id', $want_id)
                       ->where('creater', Auth::id())
                       ->first();
    }

    //约车状态转换，暂时只有激活-废弃转换
    public function statusChange($requestData, $id){

        // dd($requestData->all());
        DB::transaction(function() use ($requestData, $id){

            $Plan         = Plan::select($this->select_columns)->findorFail($id); //约车对象
            $follow_info = new PlanFollow(); //约车跟进对象

            if($requestData->status == 1){

                $update_content = collect([Auth::user()->nick_name.'激活约车'])->toJson();
            }else{

                $update_content = collect([Auth::user()->nick_name.'废弃约车'])->toJson();
            }
            

            // 约车编辑信息
            $Plan->Plan_status = $requestData->status;

            // 约车跟进信息
            $follow_info->Plan_id       = $id;
            $follow_info->user_id      = Auth::id();
            $follow_info->follow_type  = '1';
            $follow_info->operate_type = '2';
            $follow_info->description  = collect($update_content)->toJson();
            $follow_info->prev_update  = $Plan->updated_at;
         
            $follow_info->save();
            $Plan->save(); 

            return $Plan;
        });
    }

    public function quicklyFollow($id){

        DB::transaction(function() use ($id){

            $Plan         = Plan::select($this->select_columns)->findorFail($id); //约车对象
            $follow_info = new PlanFollow(); //约车跟进对象

            $update_content = collect([Auth::user()->nick_name.'例行跟进'])->toJson();
            
            // 约车编辑信息
            $Plan->creater_id = Auth::id();
            $Plan->Plan_status = '1';

            // 约车跟进信息
            $follow_info->Plan_id       = $id;
            $follow_info->user_id      = Auth::id();
            $follow_info->follow_type  = '1';
            $follow_info->operate_type = '2';
            $follow_info->description  = collect($update_content)->toJson();
            $follow_info->prev_update  = $Plan->updated_at;
         
            $follow_info->save();
            $Plan->save();
            $Plan->touch();

            return $Plan;
        });
    }
}
