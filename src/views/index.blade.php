@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">
                <div class="row">
                    <div class="ibox">
                        <div class="ibox-title"><h2>审核流列表</h2></div>
                        <button type="button" class="btn btn-primary btn-lg lego-right-top-buttons pull-right" data-toggle="modal" data-target="#myModal">
                            创建审核流
                        </button>
                        <div class="ibox-content">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>资源标识</th>
                                        <th>审核流名称</th>
                                        <th>审核流描述</th>
                                        <th>创建者</th>
                                        <th>状态</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($list as $v)
                                    <tr class="active">
                                        <th scope="row">{{ $v->id }}</th>
                                        <td>{{ $v->type_title }}</td>
                                        <td>{{ $v->title }}</td>
                                        <td>{{ $v->description }}</td>
                                        <td>{{ $v->name }}</td>
                                        <td>{{ \WuTongWan\Flow\Models\AuditFlow::$status[$v->status] }}</td>
                                        <td>{{ $v->created_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary delete" id="{{ $v->id }}">删除</button>
                                            <button type="button" class="btn btn-success edit" id="{{ $v->id }}">编辑</button>
                                            <a class="btn btn-default" href="{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@getNode',['flow_id' => $v->id]) }}" role="button">设置节点</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">{!! $list->links() !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">创建审核流</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <input type="hidden" name="id" id="id" value="0" />
                        <div class="form-group">
                            <label for="audit_bill_type_id" class="col-sm-2 control-label">资源标识</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="audit_bill_type_id" name="audit_bill_type_id">
                                    <option value="0">请选择资源标识</option>
                                    @foreach ($type_list as $v)
                                        <option value="{{ $v->id }}">{{ $v->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">名称</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" placeholder="审核流名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">描述</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="creator_id" class="col-sm-2 control-label">创建者</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="creator_id" name="creator_id">
                                    <option value="0">请选择创建者</option>
                                    @foreach ($user_list as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}({{ $v->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-2 control-label">状态</label>
                            <div class="col-sm-10">
                                <input type="radio" name="status"  value="0" checked> 关闭
                                <input type="radio" name="status"  value="1"> 开启
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" id="save">保存</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('java-script')
    <script>
        $(document).ready(function(){

            //保存
            $("#save").click(function () {

                var id = $("#id").val();
                var audit_bill_type_id = $("#audit_bill_type_id").val();
                var title = $("#title").val();
                var description = $("#description").val();
                var creator_id = $("#creator_id").val();
                var status = $('input:radio[name="status"]:checked').val();

                if(audit_bill_type_id <= 0) {
                    $("#audit_bill_type_id").focus();
                    return false;
                }

                if(title == '') {
                    $("#title").prop("placeholder","审核流名称").focus();
                    return false;
                }

                if(creator_id <= 0) {
                    $("#creator_id").focus();
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@createFlow') }}",
                    data: {'id':id,'audit_bill_type_id':audit_bill_type_id,'title':title,'description':description,'creator_id':creator_id,'status':status},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1) {
                            $("#audit_bill_type_id").find("option[value='0']").prop("selected",true);
                            $("#title").val('');
                            $("#description").val('');
                            $("#creator_id").find("option[value='0']").prop("selected",true);
                            window.location.reload();
                        }else {
                            alert(data.message);
                        }
                    }
                });

            });

            //编辑
            $(".edit").click(function () {
                var id = $(this).prop("id");

                $.ajax({
                    type: "GET",
                    url: "{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@createFlow') }}",
                    data: {'id':id},
                    dataType: 'json',
                    success: function(data){
                        $("#id").val(data.id);
                        $("#audit_bill_type_id").find("option[value='"+data.audit_bill_type_id+"']").prop("selected",true);
                        $("#title").val(data.title);
                        $("#description").val(data.description);
                        $("#creator_id").find("option[value='"+data.creator_id+"']").prop("selected",true);
                        $(":radio[name='status'][value='" + data.status + "']").prop("checked", "checked");

                        $('#myModal').modal('show');
                    }
                });

            });

            //删除
            $(".delete").click(function () {
                var id = $(this).prop("id");
                if(confirm("确定要删除此审核流吗!")){
                    $.ajax({
                        type: "POST",
                        url: "{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@delFlow') }}",
                        data: {'id':id},
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 1) {
                                window.location.reload();
                            }else {
                                alert(data.message);
                            }
                        }
                    });
                }
            });


        })
    </script>
@endsection