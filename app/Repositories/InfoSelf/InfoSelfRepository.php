<?php
namespace App\Repositories\InfoSelf;

use App\InfoSelf;
use App\InfoDianxin;
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
    protected $select_columns = ['code', 'id', 'name', 'user_telephone', 'old_bind', 'manage_name', 'manage_telephone', 'manage_id', 'project_name', 'new_telephone', 'uim_number', 'side_number', 'netin', 'collections', 'balance_month', 'collections_type', 'creater_id', 'package_id', 'status','remark','created_at'];


    // 根据ID获得约车信息
    public function find($id)
    {
        return InfoSelf::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得信息列表
    public function getAllInfos($request)
    {   
        // dd($request->Plan_launch);
        // $query = Plan::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoSelf();       // 返回的是一个Plan实例,两种方法均可
        // dd($request->all());
        $query = $query->addCondition($request->all()); //根据条件组合语句

        if(isset($request->payed)){
            if($request->payed){
                //已经付款
                $query = $query->where('status', '3');
                $query = $query->where('status','!=', '0');
            }else{
                //未付款
                $query = $query->whereIn('status', ['1','2']);
                $query = $query->where('status','!=', '0');
                $query = $query->where('old_bind', '0');
            }
        }
        
        // $query = $query->chacneLaunch($request->Plan_launch);
        // 
        if($request->withNoPage){ //无分页,全部返还

            return $query->select($this->select_columns)
                     ->orderBy('created_at', 'DESC')
                     ->get();
        }else{

            return $query->select($this->select_columns)
                     ->orderBy('created_at', 'DESC')
                     ->paginate(10);
        }

        
    }


    // 获取业务员录入信息
    public function getSalesmanInfo($creater_id, $netin)
    {   
        // dd($request->Plan_launch);
        // $query = Plan::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoSelf();       // 返回的是一个Plan实例,两种方法均可

        return $query->select($this->select_columns)
                     ->where('creater_id', $creater_id)
                     ->where('netin', $netin)
                     ->get();
    }

    // 创建信息
    public function create($requestData)
    {             
            
            $repeated = $this->isRepeat($requestData->new_telephone);

            // dd($repeated);

            if(null !== $repeated){
                Session::flash('sucess', '该号码已经存在');
                return $repeated;
            }

            $requestData['creater_id']     = Auth::id();
    
            $info   = new InfoSelf();

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
            $requestData['user_telephone']   = $requestData['telephone'];
            $requestData['side_number']      = $side_number;
            $requestData['netin']            = $requestData['netin_year'].'-'.$requestData['netin_moth'];
            $requestData['old_bind']         = isset($requestData['old_bind']) ? '1' : '0';

            // dd($requestData->all());

            $input  =  array_replace($requestData->all());
            
            $info->fill($input);

            $info = $info->create($input);
            Session::flash('sucess', '添加信息成功');

            return $info;     
    }

    // 信息更新
    public function update($requestData, $id)
    {   

        $repeated = $this->isRepeat($requestData->new_telephone);

        $info   = InfoSelf::select($this->select_columns)->findorFail($id); //获取信息
        $manager = Manager::findOrFail($requestData['manager']);//获得客户经理信息

        // 处理副卡信息
        // dd($requestData->all());
        if (!empty($requestData['side_numbers'])){
            $side_number = implode("|",  array_unique($requestData['side_numbers']));
        }

        // dd($side_number);
        
        $info->name             = $requestData->name;
        $info->user_telephone   = $requestData->telephone;
        $info->manage_name      = $manager->name;
        $info->manage_telephone = $manager->telephone;
        $info->manage_id        = $requestData->manager;
        $info->project_name     = $requestData->project_name;
        // $info->new_telephone    = $requestData->new_telephone;
        $info->uim_number       = $requestData->uim_number;
        $info->collections      = $requestData->collections;
        $info->side_number      = $side_number;
        $info->collections_type = $requestData->collections_type;
        $info->netin            = $requestData->netin_year.'-'.$requestData->netin_moth;
        $info->old_bind         = isset($requestData->old_bind) ? '1' : '0';


        Session::flash('sucess', '信息修改成功');
        $info->save();

        return $info;                     
    }

    // 删除信息
    public function destroy($id)
    {
        try {
            $info = InfoSelf::findorFail($id);
            $info->status = '0';
            $info->save();
            Session::flash('sucess', '删除约车成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除约车失败');
        }      
    }

    //判断电话号码是否重复
    public function isRepeat($new_telephone){

        $info = InfoSelf::where('new_telephone', $new_telephone)->where('status', '!=', '0')->first();

        return $info;

    }

    //处理信息
    public function infoDeal($requestData){

        $infoSelfs_not_payed = $this->getAllInfos($requestData); //尚未返还完成信息
        $info_chunk          = $infoSelfs_not_payed->chunk(10);
        $info_deal_nums      = 0;

        // dd($info_chunk);

        foreach ($info_chunk as $key => $value) {
            # 匹对所有未返还完成信息,若电信信息有返还,则设置电信信息infi_self_id及status
            foreach ($value as $k => $v) {
                $info_dianx = $this->infoDealed($v->new_telephone);

                if($info_dianx->count() == 0){
                    //没有返款信息
                    // dd($v);
                    if(!empty($v->side_number)){ //副卡是否返款
                        $side_list = explode("|", $v->side_number);
                        /*dd($v);
                        dd($side_list);*/
                        foreach ($side_list as $s_key => $s_value) {
                            $fuka_info = $this->infoDealed($s_value);

                            if($fuka_info->count() != 0){
                                //有返还信息
                                /*p($v->id);
                                dd($fuka_info);*/
                                foreach ($fuka_info as $f_key => $f_value) {
                                    
                                    /*$f_value->status = '2';
                                    $f_value->info_self_id = $v->id;*/
                                    $f_value = $this->dealSelfAndDianxin($f_value, $v);
                                    // $f_value->save();
                                    // dd($f_value);
                                    $info_deal_nums++;
                                }
                            }
                        }
                    }
                }else{
                    // 有返还信息
                    foreach ($info_dianx as $info_key => $info_value) {
                        # code...
                        /*p($v->id);
                        dd($info_value);*/
                        /*$info_value->status = '2';
                        $info_value->info_self_id = $v->id;*/
                        $info_value = $this->dealSelfAndDianxin($info_value, $v);

                        // $info_value->save();
                        $info_deal_nums++;
                    }
                }
            }
        }
        dd($info_deal_nums);
        Session::flash('sucess', '信息处理成功');


        return true;
    }

    //信息是否被匹对
    public function infoDealed($return_telephone){

        // dd($info->side_number);

        $infoDealed = InfoDianxin::select(['id', 'info_self_id', 'status'])
                                   ->where('return_telephone', $return_telephone)
                                   ->where('status', '1')
                                   ->get();

        /*if(!empty($info->side_number)){ //副卡是否返款
            $side_list = explode("|", $info->side_number);
            foreach ($side_list as $key => $value) {
                $infoDealed = InfoDianxin::select(['id', 'info_self_id', 'status'])
                                   ->where('return_telephone', $value)
                                   ->where('status', '1')
                                   ->get();
            }
        }*/
        // dd(lastSql());
        /*dd($infoDealed->count());
        dd(empty($infoDealed->count()));*/
        return $infoDealed;
    }

    // 处理匹对成功信息
    public function dealSelfAndDianxin($infoDianxin, $infoSelf)
    {   
        dd($infoSelf);
        DB::transaction(function() use ($requestData){
            // 添加车源并返回实例,处理跟进(添加车源)
            $requestData['creater_id']    = Auth::id();
            $requestData['status']        = '1';
            $requestData['name']          = $requestData['package_name'];

            // dd($requestData->all());
            
            $package = new Package();
            $input =  array_replace($requestData->all());
            $package->fill($input);
            $package = $package->create($input);

            // dd($requestData->month_price);
           
            foreach ($requestData->month_price as $key => $price) {

                $package_info = new PackageInfo(); //套餐信息对象

                $package_info->pid       = $package->id;
                $package_info->nums      = $package->month_nums;
                $package_info->creater_id  = Auth::id();
                $package_info->return_month = ($key+1);
                $package_info->return_price  = $price;
                $package_info->save();

                // dd($package_info);
            }
            
            return $package;
        });
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
}
