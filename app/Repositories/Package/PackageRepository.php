<?php
namespace App\Repositories\Package;

use App\Package;
use App\PackageInfo;
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

class PackageRepository implements PackageRepositoryContract
{

    //默认查询数据
    protected $select_columns = ['id', 'bloc', 'name', 'month_nums','package_price', 'netin', 'status', 'remark', 'creater_id', 'created_at', 'updated_at'];

    // 根据ID获得车型信息
    public function find($id)
    {
        return Package::select($this->select_columns)
                       ->findOrFail($id);
    }

    // 获得车型列表
    public function getAllPackage()
    {   
        return Package::where('status', '1')->orderBy('created_at', 'DESC')->paginate(10);
    }

    // 获得商品系列列表
    public function getAllSeries()
    {   
        return Package::select($this->select_columns)
                       ->where('status', '1')
                       ->where('pid', '0')
                       ->get();
    }

    // 创建车型
    public function create($requestData)
    {   

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

    // 修改车型
    public function update($requestData, $id)
    {
        DB::transaction(function() use ($requestData,$id){


            // dd($requestData->all());
            $package  = Package::findorFail($id);
            $input    =  array_replace($requestData->all());

            // dd($package);
            // dd($package->hasManyPackageInfo);
            $package->fill($input)->save();
            
            foreach ($package->hasManyPackageInfo as $key => $value) {
                //删除原有套餐月返还信息
                $value->status = '0';
                $value->save();
            }
            
            foreach ($requestData->month_price as $key => $price) {
                //新建套餐月返还信息
                $package_info = new PackageInfo(); //套餐信息对象

                $package_info->pid          = $package->id;
                $package_info->nums         = $package->month_nums;
                $package_info->creater_id   = Auth::id();
                $package_info->return_month = ($key+1);
                $package_info->return_price = $price;
                $package_info->save();
            }

            Session::flash('sucess', '修改套餐成功');
            return $package;
        });
    }

    // 删除套餐
    public function destroy($id)
    {
        try {
            $package = Package::findorFail($id);
            $package->status = '0';
            $package->save();
            Session::flash('sucess', '删除成功');
           
        } catch (\Illuminate\Database\QueryException $e) {
            Session()->flash('faill', '删除失败');
        }      
    }

    //判断车型是否重复
    public function isRepeat($requestData){

        $cate = Package::select('id', 'name')
                        ->where('name', $requestData->name)
                        ->first();
        // dd(isset($cate));
        return isset($cate);
    }
}
