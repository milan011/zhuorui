<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Session;
use Carbon;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\InfoSelf\InfoSelfRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
use App\Repositories\Manager\ManagerRepositoryContract;
use App\Repositories\Package\PackageRepositoryContract;
use App\Http\Requests\InfoSelf\UpdateOrderRequest;
use App\Http\Requests\InfoSelf\StoreOrderRequest;

class InfoSelfController extends Controller
{   
    protected $infoSelf;

    public function __construct(

        InfoSelfRepositoryContract $infoSelf,
        ManagerRepositoryContract $manager,
        PackageRepositoryContract $package,
        UserRepositoryContract $user
    ) {
    
        $this->infoSelf    = $infoSelf;
        $this->manager     = $manager;
        $this->package     = $package;
        $this->user        = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有车源列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $select_conditions  = $request->all();
        // dd($select_conditions);
        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'select_conditions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dt = Carbon::now(); //当前日期

        $dt_year  = $dt->year;  //当前年
        $dt_month = $dt->month; //当前月

        $managers = $this->manager->getManagers(); // 电信客户经理
        $packages = $this->package->getPackages(); // 电信客户经理

        return view('admin.infoSelf.create', compact('dt_year', 'dt_month', 'managers', 'packages'));
    }

    /**
     * 订单存储
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        // dd($request->all());

        $info = $this->infoSelf->create($request);

        Session::flash('sucess', '添加信息成功');

         return redirect('infoSelf/index')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info         = array();
        $package_info = array();

        $info         = $this->infoSelf->find($id);
        $package_info = $info->hasOnePackage;

         // dd($package_info);

        return view('admin.infoSelf.show', compact('info', 'package_info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info         = $this->infoSelf->find($id);
        
        dd($info);

        $managers = $this->manager->getManagers(); // 电信客户经理
        $packages = $this->package->getPackages(); // 套餐信息


        return view('admin.infoSelf.edit', compact(
            'info', 'managers', 'packages'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $orderRequest, $id)
    {   
        // dd($orderRequest->all());

        $order_goods = []; //需更新订单商品
        foreach ($orderRequest->category_id as $key => $value) {
            $order_goods[$key]['category_id']    = $value;
            $order_goods[$key]['order_goods_id'] = $orderRequest->order_goods_id[$key];
            $order_goods[$key]['goods_id']       = $orderRequest->goods_id[$key];
            $order_goods[$key]['goods_num']      = $orderRequest->goods_num[$key];
            $order_goods[$key]['goods_price']    = $orderRequest->goods_price[$key];
            $order_goods[$key]['goods_name']     = $orderRequest->goods_name[$key];
            $order_goods[$key]['price_level']    = $orderRequest->level;
            $order_goods[$key]['total_price']    = ($orderRequest->goods_num[$key] * $orderRequest->goods_price[$key]);
        }

        $goods_num   = 0;
        $total_price = 0;
        
        foreach ($order_goods as $key => $value) {
            $goods_num   = $goods_num + $value['goods_num'];
            $total_price = $total_price + ($value['goods_price'] * $value['goods_num']);
        }

        // p($order_goods);

        $order_goods_insert = []; //需增加订单商品
        $goods_num_i   = 0;
        $total_price_i = 0; 

        if(!empty($orderRequest->goods_category_i)){
            foreach ($orderRequest->goods_category_i as $key => $value) {
                $order_goods_insert[$key]['category_id']    = $value;
                $order_goods_insert[$key]['goods_id']       = $orderRequest->goods_id_i[$key];
                $order_goods_insert[$key]['goods_num']      = $orderRequest->goods_num_i[$key];
                $order_goods_insert[$key]['goods_price']    = $orderRequest->goods_price_i[$key];
                $order_goods_insert[$key]['goods_name']     = $orderRequest->goods_name_i[$key];
                $order_goods_insert[$key]['price_level']    = $orderRequest->level;
                $order_goods_insert[$key]['total_price']    = ($orderRequest->goods_num_i[$key] * $orderRequest->goods_price_i[$key]);
            }  
            // dd('hahah');
            foreach ($order_goods_insert as $key => $value) {
                $goods_num_i   = $goods_num_i + $value['goods_num'];
                $total_price_i = $total_price_i + ($value['goods_price'] * $value['goods_num']);
            }        
        }
        // dd($goods_num_i);
        // p($order_goods_insert);
        // p(count($order_goods));
        // dd($total_price_i);
        // 

        $orderRequest['type_num']           = count($order_goods) + count($order_goods_insert); //订单商品种类数
        $orderRequest['goods_num']          = $goods_num + $goods_num_i ;          //订单商品总数
        $orderRequest['total_price']        = $total_price + $total_price_i;        //订单总价
        $orderRequest['order_goods_update'] = $order_goods;               //需更新订单商品
        $orderRequest['order_goods_insert'] = $order_goods_insert;        //需插入订单商品

        $this->order->update($orderRequest, $id);
        // return redirect()->route('order.index')->withInput();
        return redirect('order/index')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * 删除订单
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($id);
        $this->order->destroy($id);

        return redirect('order/index');
        // return redirect('order/index')->route('order.index');
    }

    /**
     * 修改车源状态
     * 暂时只有激活-废弃转换
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {    
        /*if($request->ajax()){
            echo "zhen de shi AJAX";
        }*/
        /*p($request->input('id'));
        p($request->input('status'));
        p($request->method());exit;*/

        $order = $this->order->find($request->id);

        // $is_repeat = $this->order->isRepeat($order->vin_code);

        if($request->input('status') == '0'){
            //激活车源
            if($this->order->repeatorderNum($order->vin_code) > 0){

                $msg = '已存在该车架号,无法激活';
            }else{
                $this->order->statusChange($request, $request->input('id'));
                $msg = '车源已经激活';
            }
           
        }else{
            //废弃车源
            $this->order->statusChange($request, $request->input('id'));
            $msg = '车源已经废弃';

        }
        
        return response()->json(array(
            'status' => 1,
            'msg' => $msg,
        ));      
    }

}
