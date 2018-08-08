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
    protected $select_columns = ['id','code', 'name', 'zm_type', 'jiakuan', 'refunds', 'balance_month', 'return_telephone','yongjin', 'netin', 'creater_id', 'status', 'remark'];

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
        // $query = $query->orWhere('car_status', '6');
        // $query = $query->where('car_status', $request->input('car_status', '1'));

        return $query->select($this->select_columns)
                     // ->orderBy('created_at', 'desc')
                     ->paginate(10);
    }

    /**
     * 获得系列所属商品
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public function getChildGoods($category_id){

        $query = new Goods();       // 返回的是一个Goods实例,两种方法均可

        return $query->select($this->select_columns)
                     ->where('category_id', $category_id)
                     ->get();
    }

    /**
     * 获取商品价格
     * @param  [type] $category_id [description]
     * @return [type]              [description]
     */
    public function getGoodsPrice($goods_id){

        $query = new Goods();       // 返回的是一个Goods实例,两种方法均可

        $goods =  $query->select($this->select_columns)->find($goods_id);

        return $goods->hasManyGoodsPrice;
        // p($goods->hasManyGoodsPrice->toArray());exit;
    }

    // 创建车源
    public function create($requestData)
    {   
                 
    }

    // 修改车源
    public function update($requestData, $id)
    {
               
    }

    // 删除车源
    public function destroy($id)
    {
        try {
            $car = Order::findorFail($id);
            $car->delete();
            Session::flash('sucess', '删除车源成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除车源失败');
        }      
    }

    // ajax修改价格
    public function updateAjax($requestData){
        
        // p($requestData->all());exit;
        $need_compare_data = $requestData->except('goods_id');
        $select_fied = ['id', 'goods_id', 'price_level', 'goods_price'];
        foreach ($need_compare_data as $key => $value) {

            $price_obj = GoodsPrice::select($select_fied)
                                   ->where('price_level', $key)
                                   ->where('goods_id', $requestData->goods_id)
                                   ->first();
            // p(lastSql());
            // p($price_obj->goods_price);exit;
            if($value != $price_obj->goods_price){
                $price_obj->goods_price = $value;
                $price_obj->save();
                // p($price_obj);
            }
        }

        return $price_obj;
    }

    

    

    

   

    

    

    
}
