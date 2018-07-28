<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
//use Auth;
//use Gate;
//use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Order;
use App\OrderGoods;
use App\User;
use App\Repositories\Order\OrderRepositoryContract;
use App\Repositories\User\UserRepositoryContract;

use Excel;

class ExcelController extends Controller
{   
    protected $order;
    protected $user;

    public function __construct(

        OrderRepositoryContract $order,
        UserRepositoryContract $user
    ) {
    
        $this->order    = $order;
        $this->user     = $user;
        // $this->middleware('brand.create', ['only' => ['create']]);
    }

    //Excel文件导入功能 By Laravel学院
    public function import(){
        $filePath = 'storage/exports/'.iconv('UTF-8', 'GBK', '学生成绩').'.xls';
        Excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }

    //Excel文件导出功能 By Laravel学院
    public function export(Request $request){

        // dd($request->all());

        $orders = $this->order->getAllOrdersWithNotPage($request);

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
}
