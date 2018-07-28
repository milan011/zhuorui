<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Session;
use App\Area;
use App\Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\InfoSelf\InfoSelfRepositoryContract;
use App\Repositories\User\UserRepositoryContract;
/*use App\Repositories\Goods\GoodsRepositoryContract;
use App\Repositories\Category\CategoryRepositoryContract;*/
use App\Http\Requests\InfoSelf\UpdateOrderRequest;
use App\Http\Requests\InfoSelf\StoreOrderRequest;

class InfoSelfController extends Controller
{   
    protected $infoSelf;

    public function __construct(

        InfoSelfRepositoryContract $infoSelf,
        UserRepositoryContract $user
    ) {
    
        $this->infoSelf    = $infoSelf;
        $this->user     = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有车源列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->method());
        //$all_top_brands = $this->brands->getChildBrand(0);
        //$request['order_status'] = '1';
        $select_conditions  = $request->all();
        // dd($select_conditions);
        $orders = $this->order->getAllOrders($request);
        // dd(lastSql());
        // dd($orders);
        //$shops = $this->shop->getShopsInProvence('10');

        // dd($shops);
        // dd(lastSql());
        // dd($orders);
        /*foreach ($orders as $key => $value) {
            p($value->id);
            p($value->belongsToUser->nick_name);
        }
        exit;*/
        //$order_status_current = '1';
        
        /*return view('admin.order.index', compact('orders','order_status_current', 'all_top_brands', 'select_conditions','shops'));*/
        return view('admin.order.index', compact('orders', 'select_conditions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.infoSelf.create');
    }

    /**
     * 订单存储
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {

        // dd($request->all());
        $order_goods = [];
        foreach ($request->category_id as $key => $value) {
            $category_name = $this->category->find($value)->name;
            $order_goods[$key]['category_id']   = $value;
            $order_goods[$key]['category_name'] = $category_name;
            $order_goods[$key]['goods_id']      = $request->goods_id[$key];
            $order_goods[$key]['goods_num']     = $request->goods_num[$key];
            $order_goods[$key]['goods_price']   = $request->goods_price[$key];
            $order_goods[$key]['goods_name']    = $request->goods_name[$key];
            $order_goods[$key]['price_level']   = $request->level;
            $order_goods[$key]['total_price']   = ($request->goods_num[$key] * $request->goods_price[$key]);
        }

        $goods_num   = 0;
        $total_price = 0;
        
        foreach ($order_goods as $key => $value) {
            $goods_num   = $goods_num + $value['goods_num'];
            $total_price = $total_price + ($value['goods_price'] * $value['goods_num']);
        }

        $request['type_num']    = count($order_goods);
        $request['goods_num']   = $goods_num;
        $request['total_price'] = $total_price;
        $request['order_goods'] = $order_goods;

        /*p($goods_num);
        p($total_price);
        p($order_goods);
        dd($request->all());*/
        $orders = $this->order->create($request);

        Session::flash('sucess', '添加订单成功');

         return redirect('order/index')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orders      = $this->order->find($id);
        $order_goods = $orders->hasManyOrderGoods;

        // dd($orders);
        // dd($orders->hasManyOrderGoods[0]->belongsToCategory->name);

        return view('admin.order.show', compact('orders', 'order_goods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = $this->order->find($id);
        $order_goods = $order->hasManyOrderGoods;
        // dd($order);
        $hsd = $order_goods[0]->hasManyGoods;
        // dd(lastSql());
        // dd($order_goods[0]->hasManyGoods);

        //所有系列
        $all_series = $this->category->getAllSeries();
        // dd($all_series);
        //商户列表
        $all_merchant  = $this->user->getAllMerchant();
        // dd([$all_series,$all_merchant]);
        return view('admin.order.edit', compact(
            'order', 'order_goods', 'all_series','all_merchant'
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
