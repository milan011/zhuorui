var EditableTable = function () {

    return {

        //main function to initiate the module
        init: function () {
            function restoreRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);

                for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
                    oTable.fnUpdate(aData[i], nRow, i, false);
                }

                oTable.fnDraw();
            }

            function editRow(oTable, nRow) {
                var aData = oTable.fnGetData(nRow);
                var jqTds = $('>td', nRow);
                jqTds[2].innerHTML = '<input type="text" level="0" style="width:100%;" class="form-control small" value="' + aData[2] + '">';
                jqTds[3].innerHTML = '<input type="text" level="1" style="width:100%;" class="form-control small" value="' + aData[3] + '">';
                jqTds[4].innerHTML = '<input type="text" level="2" style="width:100%;" class="form-control small" value="' + aData[4] + '">';
                jqTds[5].innerHTML = '<input type="text" level="3" style="width:100%;" class="form-control small" value="' + aData[5] + '">';
                jqTds[6].innerHTML = '<input type="text" level="4" style="width:100%;" class="form-control small" value="' + aData[6] + '">';
                jqTds[7].innerHTML = '<input type="text" level="-1" style="width:100%;" class="form-control small" value="' + aData[7] + '">';
                jqTds[8].innerHTML = '<a class="btn btn-success edit" href="javascript:void(0);">保存</a><a class="btn btn-danger cancel" href="javascript:void(0);">取消</a>';
                // jqTds[8].innerHTML = '<a class="btn btn-danger cancel" href="">取消</a>';
            }

            function saveRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 3, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 4, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 5, false);
                oTable.fnUpdate(jqInputs[4].value, nRow, 6, false);
                oTable.fnUpdate(jqInputs[5].value, nRow, 7, false);
                oTable.fnUpdate('<a class="btn btn-primary edit" href="">修改价格</a>', nRow, 8, false);
                oTable.fnDraw();
            }

            /*function cancelEditRow(oTable, nRow) {
                var jqInputs = $('input', nRow);
                oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
                oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
                oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
                oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
                oTable.fnUpdate('<a class="edit" href="">修改价格</a>', nRow, 4, false);
                oTable.fnDraw();
            }*/

            var oTable = $('#datatables').dataTable({
                /*"aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],*/
                // set the initial value
                // "iDisplayLength": 5,
                // "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-6'i><'col-lg-6'p>>",
                // "sPaginationType": "bootstrap",
                /*"oLanguage": {
                    "sLengthMenu": "_MENU_ records per page",
                    "oPaginate": {
                        "sPrevious": "Prev",
                        "sNext": "Next"
                    }
                },*/
                /*"aoColumnDefs": [{
                        'bSortable': false,
                        'aTargets': [0]
                    }
                ]*/
                "bPaginate": false, //翻页功能
                "bLengthChange": false, //改变每页显示数据数量
                "bFilter": false, //过滤功能
                "bSort": false, //排序功能
                "bInfo": false,//页脚信息
                "bAutoWidth": true//自动宽度
            });

            // jQuery('#datatables_wrapper .dataTables_filter input').addClass("form-control medium"); // modify table search input
            // jQuery('#datatables_wrapper .dataTables_length select').addClass("form-control xsmall"); // modify table per page dropdown

            var nEditing = null;

            /*$('#datatables_new').click(function (e) {
                e.preventDefault();
                var aiNew = oTable.fnAddData(['', '', '', '',
                        '<a class="edit" href="">修改价格</a>', '<a class="cancel" data-mode="new" href="">Cancel</a>'
                ]);
                var nRow = oTable.fnGetNodes(aiNew[0]);
                editRow(oTable, nRow);
                nEditing = nRow;
            });*/

            $('#datatables a.delete').live('click', function (e) {
                e.preventDefault();

                if (confirm("Are you sure to delete this row ?") == false) {
                    return;
                }

                var nRow = $(this).parents('tr')[0];
                oTable.fnDeleteRow(nRow);
                alert("Deleted! Do not forget to do some ajax to sync with backend :)");
            });

            $('#datatables a.cancel').live('click', function (e) {
                e.preventDefault();
                if ($(this).attr("data-mode") == "new") {
                    var nRow = $(this).parents('tr')[0];
                    oTable.fnDeleteRow(nRow);
                } else {
                    restoreRow(oTable, nEditing);
                    nEditing = null;
                }
            });

            $('#datatables a.edit').live('click', function (e) {
                e.preventDefault();

                /* Get the row as a parent of the link that was clicked on */
                var nRow = $(this).parents('tr')[0];
                /*console.log(nEditing);
                console.log(nRow);
                console.log(this.innerHTML);*/
                if (nEditing !== null && nEditing != nRow) {
                    /* Currently editing - but not this row - restore the old before continuing to edit mode */
                    restoreRow(oTable, nEditing);
                    editRow(oTable, nRow);
                    nEditing = nRow;
                } else if (nEditing == nRow && this.innerHTML == "保存") {
                    /* Editing this row and want to save it */
                    console.log($(this).parents('tr').children().children('input.small'));
                    var post_data   = [];
                    var input_data  = $(this).parents('tr').children().children('input.small');
                    var goods_id    = $(this).parents('tr').children().children("input[name='goods_id']").val();
                    var request_url = $("input[name='request_url']").val();
                    var _token      = $("input[name='_token']").val();
                    // alert(goods_id);

                    input_data.each(function(index, el) {
                        /*console.log($(this).attr('level'));
                        console.log($(this).val());
                        console.log('----');*/
                        post_data[index] = $(this).val();
                    });

                    console.log(post_data);
                    
                    $.ajax({
                        method: 'POST',
                        url: request_url,
                        data:{"0":post_data[0],"1":post_data[1],"2":post_data[2],"3":post_data[3],"4":post_data[4],"-1":post_data[5],"goods_id":goods_id},
                        // data: post_data,
                        dataType: 'json',
                        headers: {      
                            'X-CSRF-TOKEN': _token       
                        },
                        success:function(data){
        
                            // alert(data.msg);
                            saveRow(oTable, nEditing);
                        },
                        error: function(xhr, type){
                            alert('修改失败，请重新修改或联系管理员');
                        }
                    });
                    saveRow(oTable, nEditing);
                    nEditing = null;                    
                } else {
                    /* No edit in progress - let's start one */
                    editRow(oTable, nRow);
                    nEditing = nRow;
                }
            });
        }

    };

}();