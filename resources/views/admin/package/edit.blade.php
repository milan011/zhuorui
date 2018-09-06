@extends('layouts.main')

@section('head_content')
    <style type="text/css">
        input.shaddress{
            margin-bottom:5px;
        }
    </style>
@endsection

<!-- 面包屑 -->
@section('BreadcrumbTrail')

<section class="content-header">
    <div class="pull-left">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.index')}}">首页</a></li>
            <li><a href="{{route('package.index')}}">套餐列表</a></li>
            <li class="active">修改套餐</li>
        </ol>
    </div>
</section>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <form id="packageCreate" action="{{route('package.update', ['package'=>$package->id])}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    {{ method_field('PUT') }}
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>套餐</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="套餐" class="form-control" value="{{$package->name}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>套餐价格</label>
                            <div class="col-md-4">
                                <input type="text" name="package_price" required placeholder="套餐价格" class="form-control" value="{{$package->package_price}}"/>
                            </div>
                        </div>
                        <div class="form-group goods_list">
                                <label class="control-label col-md-1">返还期: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" id="month_nums" name="month_nums" style="width:15%;display: inline-block;">
                                        <!-- <option  value="0">=返还期=</option> -->
                                        @foreach($return_month as $key=>$month)
                                        <option @if(($package->month_nums) == ($month)) selected='selected' @endif value="{{$month}}" >{{$month}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <!-- 每期返还金额 -->
                        <div class="form-group" id="monthn_price" style="display:none;">
                            <label class="col-md-1 control-label">月返金额:<font style="color:red;">*</font></label>
                            <div id="month_price_list" class="col-md-1">
                                
                            </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-1">备注:</span></label>
                                <div class="col-md-4">
                                    <textarea id="remark" name="remark" required style="width:400px;">{{$package->remark}}</textarea>
                                </div>
                        </div>

                        <div class="form-group">

                            <div class="col-md-4" style="text-align:center;">
                                <button type="submit" class="btn btn-sm btn-success">修改</button>
                                <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script_content')
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script>
<!-- 引入package模块js -->
<!-- <script src="{{URL::asset('yazan/js/package.js')}}"></script> -->
<script>
    $(document).ready(function(){

        var list              = {!! $package_info !!};
        var month_price_list  = $('#month_price_list');
        var contents_package  = '';
        var month_num_package = $("#month_nums").val(); //还款期数
        
        /*console.log(list[0]['return_price']);
        console.log(list[1]['return_price']);
        console.log(month_num_package);*/

        for (var i=0; i<month_num_package; i++){ 

            contents_package += '<input style="margin-bottom:5px;" type="text" name="month_price[]" required placeholder="第';
            contents_package += i+1;
            contents_package += '个月" ';
            contents_package += 'class="form-control" value="';
            contents_package += list[i]['return_price'];
            contents_package += '" />';
        }
        // console.log(contents_package);
        month_price_list.append(contents_package);
        
        $('#monthn_price').show();


        $("#month_nums").change(function() {

            var month_num = $(this).val(); //还款期数
            var contents = '';
            var month_price_list = $('#month_price_list');
            // alert(month_num);
            month_price_list.empty();

            if(month_num != 0){

                for (var i=0; i<month_num; i++){ 

                    contents += '<input style="margin-bottom:5px;" type="text" name="month_price[]" required placeholder="第';
                    contents += i+1;
                    contents += '个月" ';
                    contents += 'class="form-control" value="" />';

                }

                month_price_list.append(contents);

                $('#monthn_price').show();
            }else{

                $('#monthn_price').hide();
            }

            // console.log(contents);
        }); 

        // $("#month_nums").trigger('change'); //刷新时触发change事件
        
        $('#packageCreate').bootstrapValidator({
            live: 'submitted',
            feedbackIcons: {
                valid: '',
                invalid: '',
                validating: ''
            },
            fields: {
                package_name: {
                    validators: {
                        notEmpty: {
                            message: '请输入套餐名称'
                        }
                    }
                }, 
                package_price: {
                    validators: {
                        notEmpty: {
                            message: '请输入套餐价格'
                        },
                        /*regexp: {
                            regexp: /^[0-9]*$/,

                            message: '价格必须是正整数!'
                        },*/
                    }
                },
                remark: {
                    validators: {
                        notEmpty: {
                            message: '请输入备注'
                        }
                    }
                },             
            }
        }); 
    });
</script>
@endsection