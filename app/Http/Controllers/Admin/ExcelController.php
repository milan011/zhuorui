<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Gate;
use DB;
use Session;
//use App\Http\Requests;
use App\Http\Controllers\Controller;

/*use App\Order;
use App\OrderGoods;
use App\User;*/

// use App\Repositories\InfoSelf\InfoSelfRepositoryContract;
use App\Repositories\InfoDianxin\InfoDianxinRepositoryContract;
use App\Repositories\User\UserRepositoryContract;

use Excel;

class ExcelController extends Controller
{   
    protected $infoDianxin;
    protected $user;

    public function __construct(

        InfoDianxinRepositoryContract $infoDianxin,
        UserRepositoryContract $user
    ) {
    
        $this->infoDianxin = $infoDianxin;
        $this->user        = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    //Excel文件导入功能 By Laravel学院
    public function import(){

        // dd(session::get('file_name'));
        // dd(public_path('uploads/dianxinExcel'));
        $filePath = public_path('uploads/dianxinExcel/').session::get('file_name');
        $time_star = time();//得到当前时间戳，用来在最后计算文件导入完毕后的用时
        /*dd($filePath);
        dd('hehe');*/
        // $filePath = public_path('uploads\dianxinExcel/').'test'.'.xls';
        
        // $fielPath = "F:\phpStudy\WWW\www.zhuorui.net\public\uploads\dianxinExcel".'\\'.'test.xls';
        // dd($filePath);
        
        Excel::load($filePath, function($reader) {
                
            $tables = $reader->all();
            // $table = $reader->getSheet(0)->toArray(); //只获取第一个sheet
            // $table = $reader->toArray();
            $table = $tables[0];
            
            // dd($table->isEmpty()); //表是否为空

            if($table->isEmpty()){
                // p('hehe');exit;
                throw new \App\Exceptions\ExcelException('您导入的表是空表');
            }

            $table = $table->unique(); //清除重复数据

            //表title信息
            $table_key = array("套餐名称","返款号码","返款金额","价款","结算月","佣金方案","返还日期");

            foreach ($table_key as $key => $value) {
                # 判断传入的表数据是否符合title
                // dd($table[0]);
                if(!$table[0]->has($value)){
                    throw new \App\Exceptions\ExcelException('您导入的表第一行不符合要求,请下载标准表格');
                }
            }           

            foreach ($table as $key => $value) {
                // 表每一行都必须有数据
                foreach ($value as $k => $v) {
                   if(empty($v)){
                        throw new \App\Exceptions\ExcelException('您导入的表有空数据,请填写数据后导入');
                   }
                }
            }

            $table = $table->chunk(10); //循环处理数据每次处理10条
            /*$num = (string)2.0;
            dd($num);*/
            // dd($table);
            
            $success_count = 0;         
            
            
            // dd($data);
            try {

                foreach ($table as $key => $value) {
                    $data = [];

                    foreach ($value as $k => $v) {

                        $row["name"]             = trim($v['套餐名称']);//套餐名称
                        $row["return_telephone"] = (string)trim($v['返款号码']);//返款号码
                        $row["refunds"]          = trim($v['返款金额']);//返款金额
                        $row["yongjin"]          = trim($v['佣金方案']);//佣金方案
                        $row["balance_month"]    = (string)trim($v['结算月']);//结算月
                        $row["netin"]            = (string)trim($v['返还日期']);//返还日期
                        $row["jiakuan"]          = trim($v['价款']);//价款

                    array_push($data, $row);
                    }

                    //插入
                    foreach($data as $d){
                        if(!$d)continue;
    
                        $infoDianxin = $this->infoDianxin->create($d);
    
                        /*DB::transaction(function ()use($d) {//一些导入操作
                            $insert_id = DB::table("zr_info_dianx")->insertGetId($d);
                            //一些数据库操作
                        });*/
                        $success_count++;
                    }
                }

                $time_end = time();

                /*return Response::json(["success" => true, "message" => "本次共导入 ".($success_count+$err_count).' 条数据 , 其中失败 '.$err_count.' 条 。 ','download'=>$download,'time'=>($time_end-$time_star)]);*/
                $message = "本次共导入 ".($success_count+$err_count).' 条数据';
                // return view('admin.infoDianxin.index')->with('success', $message);
                return redirect('/infoDianxin/index')->with('message', $message);

            } catch (\Exception $e) {
                return redirect()->route('infoDianxin.error');
            }
        });

        /*try{
            //导入执行代码              
            
        }catch(Exception $e){
                //自定义处理异常
        }*/     
    }

    //Excel文件导出功能 By Laravel学院
    public function export(Request $request){

        // dd($request->all());

        // $orders = $this->order->getAllOrdersWithNotPage($request);

        /*dd(lastSql());
        dd($orders);
        dd($orders[0]->hasManyOrderGoods);
        dd($orders[0]->hasManyOrderGoods[0]->belongsToCategory);*/

        // dd($cars_info_content);

        $orders_info_content = [];
        foreach ($orders as $key => $value){

            $goods_info = null;

            foreach ($value->hasManyOrderGoods as $key => $goods) {
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
            }
            


            $orders_info_content[] =  array(
                $value->order_code,
                $value->sh_name, 
                $value->sh_telephone,                 
                $value->address,
                $goods_info,
                $value->total_price,
                $value->belongsToUserWithTopUser->nick_name,
                substr($value->created_at, 0 ,10),
                $value->user_name,
            );
        }

        // dd($orders_info_content[0][3]);
        /*$goods_info = 'F1订单（2件）：';
        $goods_info .= "\r\n";
        $goods_info .= "卡通鹿迷你被1";
        $goods_info .= "\r\n";
        $goods_info .= "卡通鹿包脚睡袍1";
        $goods_info .= "\r\n";
        $goods_info .= "发件人：赵景  13315969112";

        $orders_info_content = array(
            ['詹丽花',
                        '13599800785',
                        '广东省惠州市龙门县龙城街道古楼村路口（联塑管道）锦宁建材有限公司',
                        $goods_info,
                        '192.0',
                        '元芳',]
        );*/

        array_unshift($orders_info_content, ['编号','收件人','手机/电话','地址','物品名称', '总价', '总代', '下单时间','下单商户']);

        // dd($orders_info_content);

        $excels = Excel::create('订单',function($excel) use ($orders_info_content){
            $excel->sheet('score', function($sheet) use ($orders_info_content){
                $sheet->setWidth('A', 15);
                $sheet->setWidth('B', 10);
                $sheet->setWidth('C', 20);
                $sheet->setWidth('D', 50);
                $sheet->setWidth('E', 50); 
                $sheet->setFontSize(15);
                // $sheet->setValignment('middle');      
                $sheet->rows($orders_info_content);
            });
        });
        // dd($excels->save());
        $excels->export('xls');
    }

    /*public function import(Request $request)
{   
    $time_star = time();//得到当前时间戳，用来在最后计算文件导入完毕后的用时
//set_time_limit — 设置脚本最大执行时间。默认值为30秒，或者是在php.ini的max_execution_time被定义的值，如果此值存在。如果设置为0（零），没有时间方面的限制。
    $rlt = $this->upload_data($request);//调用上面的方法，上传文件得到文件名

    if ($rlt["success"] == false) {
        return Response::json($rlt);
    } else {
        try {
            $data = [];
            $table = $this->load_excel($rlt["message"]);//调用load_excel方法导入文件
            if ($table[0][0] == "姓名" && $table[0][1] == "就职单位" && $table[0][2] == "联系电话" ) {//Excel第一行
                $title = [
                    0   => '姓名',
                    1   => '就职单位',
                    2   => '联系电话',
                ];
                foreach ($table as $v) {
                    try{
                        if ($v[0] == "姓名" && $v[1] == "就职单位" && $v[2] == "联系电话") {
                            continue;
                        }
                        if ($v[0] == "" && $v[1] == "" && $v[2] == "") {

                        } else {

                            $row["name"] = trim($v[0]);//姓名
                            $row["address"] = trim($v[1]);//就职单位
                            $row["phone"] = trim($v[2]);//联系电话

                            array_push($data, $row);
                        }
                    }catch(\Exception $e){
                        $err_count++;
                        array_push($error,$v);//失败数据存起来后面将把失败数据导出
                        Log::info($e);
                        continue;
                    }
                }
                //插入
                foreach($data as $d){
                    if(!$d)continue;
                    try{
                        DB::transaction(function ()use($d,$process_template) {//一些导入操作
                            $insert_id = DB::table("student")->insertGetId($d);
                            //一些数据库操作
                        });
                        $success_count++;
                    }catch (\Exception $e){
                        $err_count++;
                        array_push($error,$d);//失败数据存起来后面将把失败数据导出
                        continue;
                    }
                }
                if($error){
                    array_unshift($error,$title);//将标题插入失败数据的第一行，后面导出
                    session(['error'=>$error]);//将要导出的内容存入session 键值为error
                    $download = true;//向前台返回一个标识，true说明有失败数据
                }else{
                    $download = false;
                }
                $time_end = time();
                return Response::json(["success" => true, "message" => "本次共导入 ".($success_count+$err_count).' 条数据 , 其中失败 '.$err_count.' 条 。 ','download'=>$download,'time'=>($time_end-$time_star)]);

            } else {
                return Response::json(["success" => false, "message" => "数据格式错误"]);
            }
        } catch (\Exception $e) {
            Log::info($e);
            return Response::json(["success" => false, "message" => "数据导入失败"]);
        }
    }*/
}
