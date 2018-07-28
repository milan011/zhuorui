<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Manager extends Model
{
    /**
     * The database table used by the model.
     * 定义模型对应数据表及主键
     * @var string
     */
    // protected $table = 'users';
    protected $table = 'zr_manager';
    protected $primaryKey ='id';

    /**
     * The attributes that are mass assignable.
     * 定义可批量赋值字段
     * @var array
     */
    protected $fillable = ['id', 'name', 'telephone', 'wx_number', 'remark', 'email','address','creater_id','status','created_at','updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     * //在模型数组或 JSON 显示中隐藏某些属性
     * @var array
     */
    protected $hidden = [];

    /**
     * 应该被调整为日期的属性
     * 定义软删除
     * @var array
     */
    protected $dates = ['deleted_at'];

    // 插入数据时忽略唯一索引
    public static function insertIgnore($array){
        $a = new static();
        if($a->timestamps){
            $now = \Carbon\Carbon::now();
            $array['created_at'] = $now;
            $array['updated_at'] = $now;
        }
        DB::insert('INSERT IGNORE INTO '.$a->table.' ('.implode(',',array_keys($array)).
            ') values (?'.str_repeat(',?',count($array) - 1).')',array_values($array));
    }

    // 搜索条件处理
    public function addCondition($requestData){

        $query = $this;


            if(!(Auth::user()->isSuperAdmin())){

               if(Auth::user()->isMdLeader()){
                    //店长
                    $user_shop_id = Auth::user()->shop_id; //用户所属门店id
                    // $this->where('shop_id', $user_shop_id);
                    $query = $query->where('shop_id', $user_shop_id);    
                }else{
                    //店员
                    // $this->where('creater_id', Auth::id());
                    $query = $query->where('creater_id', Auth::id());  
                } 
            }           
        if(!empty($requestData['car_code'])){  //有车源编码选择

            $query = $query->where('car_code', $requestData['car_code']);

            return $query;
        }

        //if(isset($requestData['car_status']) && $requestData['car_status'] != ''){
        if(!empty($requestData['car_status'])){
            //有车源状态选项
            if($requestData['car_status'] == '1'){

                $query = $query->where(function($query) use ($requestData){

                    $query = $query->where('car_status', $requestData['car_status']);
                    $query = $query->orWhere('car_status', '6');
                });
            }else{

                $query = $query->where('car_status', $requestData['car_status']);
            }
        }  

        if(!empty($requestData['gearbox'])){
            // dd($requestData['gearbox']);
            $query = $query->where(function($query) use ($requestData){
                foreach ($requestData['gearbox'] as $key => $gear) {
                    $query = $query->orWhere('gearbox', $gear);
                }                       
            });
        }

        if(!empty($requestData['shop_id'])){

            $query = $query->where('shop_id', $requestData['shop_id']);
        }

        if(!empty($requestData['is_appraiser'])){

            $query = $query->where('is_appraiser', $requestData['is_appraiser']);
        }

        if(!empty($requestData['sale_number'])){

            $query = $query->where('sale_number', $requestData['sale_number']);
        }

        if(!empty($requestData['out_color'])){

            $query = $query->where('out_color', $requestData['out_color']);
        }

        if(!empty($requestData['capacity'])){

            $query = $query->where('capacity', $requestData['capacity']);
        }

        if(!empty($requestData['category_type'])){

            $query = $query->where('categorey_type', $requestData['category_type']);
        } 

        if(!empty($requestData['category_id'])){

            $query = $query->where('category_id', $requestData['category_id']);
        }else{

            if(!empty($requestData['car_factory'])){
               $query = $query->where('car_factory', $requestData['car_factory']); 
            }else{

                if(!empty($requestData['brand_id'])){
                    $query = $query->where('brand_id', $requestData['brand_id']);
                }
           }
        } 

        if(!empty($requestData['begin_mileage'])){
            $query = $query->where('mileage', '>=', $requestData['begin_mileage']);
        }
        
        if(!empty($requestData['end_mileage'])){
            $query = $query->where('mileage', '<=', $requestData['end_mileage']);
        }

        if(!empty($requestData['top_price'])){
            $query = $query->where('top_price', '<=', $requestData['top_price']);
        }
        
        if(!empty($requestData['bottom_price'])){
            $query = $query->where('top_price', '>=', $requestData['bottom_price']);
        }

        if(!empty($requestData['end_date'])){
            $query = $query->where('created_at', '<=', $requestData['end_date']);
        }
        
        if(!empty($requestData['begin_date'])){
            $query = $query->where('created_at', '>=', $requestData['begin_date']);
        } 

        if(!empty($requestData['need_follow'])){
            $query = $query->where('updated_at', '<=', $requestData['need_follow']);
        }   

        return $query;
    }

     /**
     * 推荐车型信息的查询作用域
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOsRecommend($query, $requestData)
    {
        if(isset($requestData['top_price'])){
            $query = $query->where('top_price', '<=', $requestData['top_price']);
        }
        
        if(isset($requestData['bottom_price'])){
            $query = $query->where('bottom_price', '>=', $requestData['bottom_price']);
        }
        
        $query = $query->where('car_status', '1');
        return $query;
    }

    // 定义goods_price表与goods表一对多关系
    public function belongsToGoods(){

      return $this->belongsTo('App\Goods', 'goods_id', 'id')->select('id', 'name AS category_name');
    }

}
