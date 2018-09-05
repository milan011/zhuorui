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
use App\Repositories\InfoDianxin\InfoDianxinRepositoryContract;
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
        InfoDianxinRepositoryContract $infoDianxin,
        ManagerRepositoryContract $manager,
        PackageRepositoryContract $package,
        UserRepositoryContract $user
    ) {
    
        $this->infoSelf    = $infoSelf;
        $this->infoDianxin = $infoDianxin;
        $this->manager     = $manager;
        $this->package     = $package;
        $this->user        = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request = $this->netin_date($request);
        $select_conditions  = $request->all();
        $request['payed']   = false;
        $notPayed = true;
        $info_status_now = '';
        $action = route('infoSelf.index').'/index';
        // dd($select_conditions);
        
        // dd(route('infoSelf.index'));
        /*if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月

            $netin  = $netin_year . '-' . $netin_month;
            $request->nettin_year = $netin_year;
            $request->netin_month = $netin_month;
            // dd($netin_year);
        }*/

        // $request->netin = $netin;

        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd($infoSelfs[1]->hasManyInfoDianxin()->count());
        
        return view('admin.infoSelf.index', compact('infoSelfs','action','info_status_now', 'select_conditions', 'netin', 'netin_year', 'netin_month'));
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function payed(Request $request)
    {
        $request = $this->netin_date($request);
        $info_status_now = '已付款';
        $select_conditions  = $request->all();
        $request['pay_status']   = 'payed';
        // dd($select_conditions);
        $action = route('infoSelf.payed');

        /*if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月

            $netin  = $netin_year . '-' . $netin_month;
            $request->nettin_year = $netin_year;
            $request->netin_month = $netin_month;
            // dd($netin_year);
        }*/

        // $request->netin = $netin;

        $infoSelfs = $this->infoSelf->getAllInfos($request);
        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'action', 'info_status_now','select_conditions', 'netin', 'netin_year', 'netin_month'));
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function paying(Request $request)
    {
        $request = $this->netin_date($request);
        $select_conditions  = $request->all();
        $request['pay_status']   = 'paying';
        $info_status_now = '付款中';
        $action = route('infoSelf.paying');
        // dd($select_conditions);
        

        /*if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月

            $netin  = $netin_year . '-' . $netin_month;
            $request->nettin_year = $netin_year;
            $request->netin_month = $netin_month;
            // dd($netin_year);
        }*/

        // $request->netin = $netin;

        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs','action', 'info_status_now', 'select_conditions', 'netin', 'netin_year', 'netin_month'));
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function notPayed(Request $request)
    {
        $request = $this->netin_date($request);
        $select_conditions       = $request->all();
        $request['pay_status']   = 'unpayed';
        $info_status_now = '未付款';
        $notPayed = true;
        $action = route('infoSelf.notPayed');
        // dd($select_conditions);
        // dd($action);

        // dd($request->all());
        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd(lastSql());
        // dd($infoSelfs);
        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs','action', 'info_status_now','select_conditions', 'notPayed', 'netin', 'netin_year', 'netin_month'));
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

        if(!empty($info->side_number)){

            $side_number_array     = explode('|', $info->side_number); //副卡数组
            $side_uim_number_array = explode('|', $info->side_uim_number); //副卡uim数组
        }
        
         // dd($info->hasManyInfoDianxin);

        return view('admin.infoSelf.show', compact('info', 'package_info', 'side_number_array','side_uim_number_array'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $side_number_array = array();
        $info              = $this->infoSelf->find($id);
        
        // dd($info->side_number);

        $netin_date  = explode('-', $info->netin); //入网日期转数组
        $netin_year  = $netin_date[0]; //入网年
        $netin_month = $netin_date[1]; //入网月

        if(!empty($info->side_number)){

            $side_number_array     = explode('|', $info->side_number); //副卡数组
            $side_uim_number_array = explode('|', $info->side_uim_number); //副卡uim数组
        }
        
        // dd($side_number_array);
        

        $managers = $this->manager->getManagers(); // 电信客户经理
        $packages = $this->package->getPackages(); // 套餐信息


        return view('admin.infoSelf.edit', compact(
            'info', 'managers', 'packages', 'netin_year', 'netin_month', 'side_number_array','side_uim_number_array'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        // dd($request->all());

        $this->infoSelf->update($request, $id);
        
        return redirect('infoSelf/index')->withInput();
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
        $this->infoSelf->destroy($id);

        return redirect('infoSelf/index');
        // return redirect('order/index')->route('order.index');
    }

    /**
     * 信息处理
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dealWith(Request $request)
    {
        /**
        * 获取所有未返还完成信息,与电信导入表数据对比并处理
        * 处理结果反馈至返还记录
        * 若返还完成,则将信息状态设置为完成状态
        * 
        */
   
        // 获取全部尚未返还完成信息
        $request['payed']        = false;
        $request['withNoPage']   = true; //获取全部数据
        $notPayed = true;
        // dd($select_conditions);
        $infoSelfs_not_payed = $this->infoSelf->infoDeal($request); //尚未返还完成信息

        // dd($infoSelfs_not_payed);

        //处理已经返还完成信息
        $infoSelfs_payed = $this->infoSelf->infopayed($request); //尚未返还完成信息

        return redirect('infoSelf/notPayed')->withInput();
    }

    /**
     * 信息统计
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        
        // dd($request->isMethod('post'));
        $infoSelfs           = $this->infoSelf->getAllInfos($request);
        $salesmans           = $this->user->getAllUsersByRole('1');  //获取所有业务员

        $salesmans = DB::table('zr_users')->where('status', '1')->get();
        $salesman_statistics = array();
        
        // dd($salesmans);

        if($request->isMethod('post')){

            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{

            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月
            if(strlen($netin_month) == 1){
                $netin_month = '0'.$netin_month;
            }
            $netin  = $netin_year . '-' . $netin_month;


            // dd($netin_year);
        }
        
        
        

        // dd($netin);
        foreach ($salesmans as $key => $value) {
            # 每个业务员统计
            $salesman_list[$key]['id'] = $value->id;
            $salesman_list[$key]['nick_name'] = $value->nick_name;
        }

        // dd($salesman_list);
        /*$salesman_list = [
            ['id' => '1', 'nick_name'=>'wcg'],
            ['id' => '2', 'nick_name'=>'mm'],
        ];*/

        foreach ($salesman_list as $key => $value) {
            # 每个业务员统计
            $salesman_info  = $this->infoSelf->getSalesmanInfo($value['id'], $netin);
            $side_number    = 0;
            // dd(lastSql());
            // dd($value);
            foreach ($salesman_info as $k => $v) {
                # 统计业务员副卡数目
                
                if(!empty($v->side_number)){

                    $side_array = explode("|", $v->side_number);
                    // p(count($side_array));

                    $side_number += count($side_array);
                }
            }
            /*p($side_number);
            dd($salesman_info->count());*/

            $salesman_statistics[$key]['nick_name'] = $value['nick_name'];
            $salesman_statistics[$key]['info_nums'] = $salesman_info->count();
            $salesman_statistics[$key]['side_nums'] = $side_number;
            $salesman_statistics[$key]['netin']     = $netin;
        }

        // dd($salesman_statistics);
        
        return view('admin.infoSelf.statistics', compact('salesman_statistics', 'netin', 'netin_year', 'netin_month'));
    }

    protected function netin_date($request){

        if($request->isMethod('post')){
            // p('post');
            $netin_year  = $request->netin_year; //入网年
            $netin_month = $request->netin_month; //入网月
            $netin  = $request->netin_year . '-' . $request->netin_month;
        }else{
            // p('hehe');
            $dt = Carbon::now(); //当前日期

            $netin_year  = $dt->year;  //当前年
            $netin_month = $dt->month; //当前月
            // dd(strlen($netin_month));
            if(strlen($netin_month) == 1){
                $netin_month = '0'.$netin_month;
            }
            $netin  = $netin_year . '-' . $netin_month;
            $request['netin_year']  = '';
            $request['netin_month'] = '';
            // dd($netin_year);
            // dd($request->all());
        }
        // dd($request->all());
        return $request;
    }
}
