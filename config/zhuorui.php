<?php

return [//淘车乐配置
    
    //管理员角色对照表
    'user_role_type' =>[

        '超级管理员' => '1',
        '管理员' => '2',
        '业务员'   => '3',
    ],

    //套餐返还期
    'return_month' =>[

        '1',
        '2',
        '3',
        '4',
        '5',
    ],

    //套餐年
    'package_year' =>[

        '2018',
        '2019',
        '2020',
        '2021',
        '2022',
        '2023',
        '2024',
        '2025',
        '2026',
    ],

    //套餐月
    'package_month' =>[

        '1'=>'01',
        '2'=>'02',
        '3'=>'03',
        '4'=>'04',
        '5'=>'05',
        '6'=>'06',
        '7'=>'07',
        '8'=>'08',
        '9'=>'09',
        '10'=>'10',
        '11'=>'11',
        '12'=>'12',
    ],

    //套餐状态
    'package_status' =>[

        '0'=>'废弃',
        '1'=>'正常',
    ],

    //收款方式
    'collections_type' =>[

        '1'=>'微信',
        '2'=>'支付宝',
        '3'=>'刷卡',
        '4'=>'现金',
        '5'=>'其他',
    ],
    //信息状态
    'info_status' =>[

        '0'=>'废弃',
        '1'=>'未返还',
        '2'=>'返还中',
        '3'=>'已返还',
    ],
];