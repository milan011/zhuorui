<?php
namespace App\Repositories\InfoDianxin;

use App\InfoDianxin;
use App\Area;
use Session;
use Illuminate\Http\Request;
use Gate;
use Datatables;
use Carbon;
use PHPZen\LaravelRbac\Traits\Rbac;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Debugbar;

class InfoDianxinRepository implements InfoDianxinRepositoryContract
{
    //默认查询数据
    protected $select_columns = ['id','code', 'name', 'zm_type', 'jiakuan', 'refunds', 'balance_month', 'return_telephone','yongjin', 'netin', 'creater_id', 'status', 'remark','created_at'];

    // 根据ID获得车源信息
    public function find($id)
    {
        return InfoDianxin::select($this->select_columns)
                   ->findOrFail($id);
    }

    // 根据不同参数获得商品列表
    public function getAllDianXinInfos($request)
    {   
        // dd($request->all());
        // $query = Order::query();  // 返回的是一个 QueryBuilder 实例
        $query = new InfoDianxin();       // 返回的是一个Order实例,两种方法均可

        $query = $query->addCondition($request->all()); //根据条件组合语句
     
        // dd($query);
        // $query = $query->where('is_show', '1');
        $query = $query->orWhere('status', '1');
        // $query = $query->where('car_status', $request->input('car_status', '1'));

        return $query->select($this->select_columns)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    // 创建信息
    public function create($requestData)
    {   
        $repeated = $this->isRepeat($requestData->return_telephone,$requestData->balance_month);

        // dd($repeated);

        if(null !== $repeated){
            Session::flash('sucess', '该号码已经存在');
            return $repeated;
        }

        $infoDianxin = new InfoDianxin();
        // $input =  array_replace($requestData->all());
        
        $requestData['creater_id'] = Auth::id();
        $requestData['netin']      = $requestData['netin_year'].'-'.$requestData['netin_moth'];
        unset($requestData['_token']);
        unset($requestData['netin_year']);
        unset($requestData['netin_moth']);

        $infoDianxin = $infoDianxin->insertIgnore($requestData->all());

        // Session::flash('sucess', '添加成功');
        return $infoDianxin;     
    }

    // 信息更新
    public function update($requestData, $id)
    {   

        $info   = InfoDianxin::select($this->select_columns)->findorFail($id); //获取信息
        
        $info->name            = $requestData->name;
        $info->yongjin         = $requestData->yongjin;
        $info->refunds         = $requestData->refunds;
        $info->jiakuan         = $requestData->jiakuan;
        $info->balance_month   = $requestData->balance_month;
        $info->netin           = $requestData->netin_year.'-'.$requestData->netin_moth;

        Session::flash('sucess', '信息修改成功');
        $info->save();

        return $info;                     
    }

    // 删除信息
    public function destroy($id)
    {
        try {
            $info = InfoDianxin::findorFail($id);
            $info->status = '0';
            $info->save();
            Session::flash('sucess', '删除信息成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除信息失败');
        }      
    } 

    //判断电话号码是否重复
    public function isRepeat($return_telephone, $balance_month){

        $info = InfoDianxin::where('return_telephone', $return_telephone)
                        ->where('status', '!=', '0')
                        ->where('balance_month', $balance_month)
                        ->first();

        return $info;

    }
}
