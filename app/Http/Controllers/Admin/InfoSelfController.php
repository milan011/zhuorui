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
     * 所有信息列表
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
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function payed(Request $request)
    {
        
        $select_conditions  = $request->all();
        $request['payed']   = true;
        // dd($select_conditions);
        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'select_conditions'));
    }

    /**
     * Display a listing of the resource.
     * 所有信息列表
     * @return \Illuminate\Http\Response
     */
    public function notPayed(Request $request)
    {
        
        $select_conditions  = $request->all();
        $request['payed']   = false;
        $notPayed = true;
        // dd($select_conditions);
        $infoSelfs = $this->infoSelf->getAllInfos($request);

        // dd(lastSql());
        // dd($infoSelfs);
        // dd($infoSelfs[0]->belongsToCreater);
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'select_conditions', 'notPayed'));
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
        $side_number_array = array();
        $info              = $this->infoSelf->find($id);
        
        // dd($info->side_number);

        $netin_date  = explode('-', $info->netin); //入网日期转数组
        $netin_year  = $netin_date[0]; //入网年
        $netin_month = $netin_date[1]; //入网月

        if(!empty($info->side_number)){

            $side_number_array  = explode('|', $info->side_number); //副卡数组
        }
        
        // dd($side_number_array);
        

        $managers = $this->manager->getManagers(); // 电信客户经理
        $packages = $this->package->getPackages(); // 套餐信息


        return view('admin.infoSelf.edit', compact(
            'info', 'managers', 'packages', 'netin_year', 'netin_month', 'side_number_array'
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

        dd($request->all());

        $info = $this->infoSelf->create($request);

        return redirect('infoSelf/index')->withInput();
    }

    /**
     * 信息统计
     * 基本信息--商品信息
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function statistics(Request $request)
    {
        $select_conditions  = $request->all();
        // dd($select_conditions);
        $infoSelfs           = $this->infoSelf->getAllInfos($request);
        $salesmans           = $this->user->getAllUsersByRole('1');  //获取所有业务员
        $salesman_statistics = array();
        $netin               = '2018-04';
        // dd($salesmans);

        foreach ($salesmans as $key => $value) {
            # 每个业务员统计
            $salesman_list[] = $value->id;
        }

        // dd($salesman_list);
        $salesman_list = [
            ['id' => '1', 'nick_name'=>'wcg'],
            ['id' => '2', 'nick_name'=>'mm'],
        ];

        foreach ($salesman_list as $key => $value) {
            # 每个业务员统计
            $salesman_info  = $this->infoSelf->getSalesmanInfo($value['id'], $netin);
            $side_number    = 0;
            // dd(lastSql());
            // dd($value);
            foreach ($salesman_info as $key => $v) {
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

        dd($salesman_statistics);
        
        return view('admin.infoSelf.statistics', compact('salesman_statistics', 'select_conditions'));
    }

}
