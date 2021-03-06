<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use Excel;
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

        $pay_status = !empty($request['pay_status']) ? $request['pay_status'] : '' ;
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
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'pay_status','action','info_status_now', 'select_conditions', 'netin', 'netin_year', 'netin_month'));
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
        $pay_status = 'payed';
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
        
        return view('admin.infoSelf.index', compact('infoSelfs','pay_status', 'action', 'info_status_now','select_conditions', 'netin', 'netin_year', 'netin_month'));
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
        $pay_status = 'paying';
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
        
        return view('admin.infoSelf.index', compact('infoSelfs','pay_status','action', 'info_status_now', 'select_conditions', 'netin', 'netin_year', 'netin_month'));
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
        $pay_status = 'unpayed';
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
        
        return view('admin.infoSelf.index', compact('infoSelfs', 'pay_status','action', 'info_status_now','select_conditions', 'notPayed', 'netin', 'netin_year', 'netin_month'));
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

        $salesmans = DB::table('zr_users')->where('status', '1')->where('id','!=', '1')->get();
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
            $salesman_info      = $this->infoSelf->getSalesmanInfo($value['id'], $netin);
            $side_number_num    = 0; //未绑老卡信息数
            $side_number_old    = 0; //绑老卡信息数
            $old_bind_number    = 0; //绑老卡信息数
            // dd(lastSql());
            // dd($value);
            // dd($salesman_info );
            foreach ($salesman_info as $k => $v) {
                # 统计业务员副卡数目
                
                if($v->old_bind == 0){
                    //绑老卡
                    if(!empty($v->side_number)){

                        $side_array_old = explode("|", $v->side_number);
                        // p(count($side_array));

                        $side_number_old += count($side_array_old);
                    }
                    $old_bind_number++;
                }else{
                    //不绑老卡
                    if(!empty($v->side_number)){

                        $side_array = explode("|", $v->side_number);
                        // p(count($side_array));

                        $side_number_num += count($side_array);
                    }
                }
                
            }
            /*p($side_number);
            dd($salesman_info->count());*/

            $salesman_statistics[$key]['nick_name']         = $value['nick_name'];
            $salesman_statistics[$key]['info_nums_all']     = $salesman_info->count();
            $salesman_statistics[$key]['info_nums_old']     = $old_bind_number;
            $salesman_statistics[$key]['info_nums_num']     = ($salesman_info->count() - $old_bind_number);
            $salesman_statistics[$key]['side_nums_all']     = $side_number_num + $side_number_old;
            $salesman_statistics[$key]['side_nums_old']     = $side_number_old;
            $salesman_statistics[$key]['side_nums_num']     = $side_number_num;
            $salesman_statistics[$key]['netin']             = $netin;
        }

        // dd($salesman_statistics);

        $total_num = [
            'info_nums_all_total'  => 0,
            'info_nums_old_total'  => 0,
            'info_nums_num_total'  => 0,
            'side_nums_all_total'  => 0,
            'side_nums_old_total'  => 0,
            'side_nums_num_total'  => 0,
            'total_all'  => 0,

        ];

        foreach ($salesman_statistics as $key => $value) {
            $total_num['info_nums_all_total'] += $value['info_nums_old'] + $value['info_nums_num'];
            $total_num['info_nums_old_total'] += $value['info_nums_old'];
            $total_num['info_nums_num_total'] += $value['info_nums_num'];
            $total_num['side_nums_all_total'] += $value['side_nums_old'] + $value['side_nums_num'];
            $total_num['side_nums_old_total'] += $value['side_nums_old'];
            $total_num['side_nums_num_total'] += $value['side_nums_num'];
            $total_num['total_all'] += $value['info_nums_all'] + $value['side_nums_all'];
        }

        // dd($total_num);
        
        return view('admin.infoSelf.statistics', compact('salesman_statistics', 'netin', 'netin_year', 'netin_month', 'total_num'));
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


    /**
     * Show the form for editing the specified resource.
     * 导入文件
     * @param  
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        
        
        $dt = Carbon::now(); //当前日期

        $now_year  = $dt->year;  //当前年
        $now_month = $dt->month; //当前月
        // dd(strlen($netin_month));
        if(strlen($now_month) == 1){
            $now_month = '0'.$now_month;
        }

        /*$netin  = !empty($request->netin_year) ? $request->netin_year : $now_year . '-' . !empty($request->netin_month) ? $request->netin_month : $now_month;*/

        if(empty($request->netin_year)){

            $request['netin_year'] = $now_year;
        }

        if(empty($request->netin_month)){

            $request['netin_month'] = $now_month;
        }

        $request['withNoPage'] = true;

       

        $excel_info = $this->infoSelf->getAllInfos($request);

        

        // dd(lastSql());
        // dd($excel_info[0]->side_number);

        $info_content = [];
        foreach ($excel_info as $key => $value){

            $fuka_info = null;

            /*foreach ($value->hasManyOrderGoods as $key => $goods) {
                $goods_info .= $goods->category_name;
                $goods_info .= $goods->goods_name;
                $goods_info .= $goods->goods_num;
                $goods_info .= "\r\n";
            }

            $goods_info .= '发件人:'.$value->send_name;
            
            if(!empty($value->remark)){
                $goods_info .= "\r\n";
                $goods_info .= '备注:';
                $goods_info .= $value->remark;
            }*/

            if(!empty($value->side_number)){

                $side_number_arr     = explode("|",  $value->side_number);
                $side_uim_number_arr = explode("|",  $value->side_uim_number);

                foreach ($side_number_arr as $k => $v) {
                    $fuka_info .= $v;
                    $fuka_info .= '(';
                    $fuka_info .= $side_uim_number_arr[$k];
                    $fuka_info .= ')';
                    $fuka_info .= "\r\n";
                }

                // dd($fuka_info);
                $fuka_info = substr($fuka_info,0,strlen($fuka_info)-2); 
            }
            
            if($value->is_jituan == 1){
                $jituan_info = '是';
            }else{
                $jituan_info = '否';
            }

            if($value->old_bind == 1){
                $old_bind_info = '是';
            }else{
                $old_bind_info = '否';
            }


            $info_content[] =  array(
                $key+1,
                substr($value->created_at, 0 ,10),
                $value->manage_name, 
                $value->manage_telephone,                 
                $value->project_name,
                $value->name,
                $value->new_telephone,
                $value->uim_number,
                $jituan_info,
                $old_bind_info,
                $fuka_info,
                isset($value->hasOnePackage->name) ? $value->hasOnePackage->name : '',
                $value->user_telephone,
                $value->collections,
                config('zhuorui.collections_type')[$value->collections_type],
                $value->belongsToCreater->nick_name,
            );
        }

        
        $titile_arr = ['序号','日期','客户经理','电话','项目', '客户姓名', '新号码', 'UIM码', '集团卡', '绑老卡','副卡(UIM)','套餐标准', '联系方式', '收款', '收款方式', '销售人'];

        array_unshift($info_content, $titile_arr);

        

        $excels = Excel::create('信息',function($excel) use ($info_content){
            $excel->sheet('score', function($sheet) use ($info_content){
                $sheet->setWidth('A', 5);
                $sheet->setWidth('B', 10);
                $sheet->setWidth('C', 10);
                $sheet->setWidth('D', 15);
                $sheet->setWidth('E', 10); 
                $sheet->setWidth('G', 20); 
                $sheet->setWidth('H', 30); 
                $sheet->setWidth('I', 5); 
                $sheet->setWidth('J', 5); 
                $sheet->setWidth('K', 50); 
                $sheet->setWidth('L', 30); 
                $sheet->setWidth('M', 15); 
                $sheet->setWidth('N', 5); 
                $sheet->setWidth('O', 10); 
                $sheet->setWidth('P', 10); 
                $sheet->setFontSize(15);
                // $sheet->setValignment('middle');      
                $sheet->rows($info_content);
            });
        });

        
        // dd($excels->save());
        $excels->export('xlsx');
    }
}
