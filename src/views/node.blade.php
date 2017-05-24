@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">
                <div class="row">
                    <div class="ibox">
                        <div class="ibox-title"><h2>{{ $flow_info->title }}--设置节点</h2></div>
                        @if (count($list) == 0)
                            <button type="button" class="btn btn-primary btn-lg lego-right-top-buttons pull-right" data-toggle="modal" data-target="#myModal">
                                创建节点
                            </button>
                        @endif

                        @foreach ($list as $v)
                        <div class="row">
                                <div class="col-xs-6 col-md-4">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">{{ $v->title }}</h3>
                                        </div>
                                        <div class="panel-body">
                                            <p>节点描述:{{ $v->description }}</p>
                                            <p>节点步骤:{{ $v->step }}</p>
                                            <p>审核类型:{{ \WuTongWan\Flow\Models\AuditNode::$audit_type[$v->audit_type] }}</p>
                                        </div>
                                        <div class="panel-footer">
                                            <button type="button" class="btn btn-primary">下级节点</button>
                                            <button type="button" class="btn btn-success">编辑</button>
                                            <button type="button" class="btn btn-info">审核人</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endforeach

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
                    <h4 class="modal-title" id="myModalLabel">创建节点</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <input type="hidden" name="id" id="id" value="0" />
                        <input type="hidden" name="parent_audit_node_id" id="parent_audit_node_id" value="0" />
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">节点标题</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" placeholder="节点标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-2 control-label">节点描述</label>
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
                            <label for="audit_type" class="col-sm-2 control-label">审核类型</label>
                            <div class="col-sm-10">
                                <input type="radio" name="audit_type"  value="1" checked> 全通过
                                <input type="radio" name="audit_type"  value="2"> 至少一人通过
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_end_flow" class="col-sm-2 control-label">是否终点</label>
                            <div class="col-sm-10">
                                <input type="radio" name="is_end_flow"  value="0" checked> 否
                                <input type="radio" name="is_end_flow"  value="1"> 是
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

                var id= $("#id").val();
                var parent_audit_node_id = $("#parent_audit_node_id").val();
                var title = $("#title").val();
                var description = $("#description").val();
                var audit_type = $('input:radio[name="audit_type"]:checked').val();
                var  is_end_flow = $('input:radio[name="is_end_flow"]:checked').val();
                var creator_id = $("#creator_id").val();

                if(title == '')
                {
                    $("#title").prop("placeholder","请填写资源名称!").focus();
                    return false;
                }
                if(creator_id == 0)
                {
                    $("#creator_id").focus();
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@createNode') }}",
                    data: {'id':id,'parent_audit_node_id':parent_audit_node_id,'audit_flow_id':{{ $flow_info->id }},'title':title,'description':description,'creator_id':creator_id,'audit_type':audit_type,'is_end_flow':is_end_flow},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 1) {
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
                id = $(this).prop("id");

                $.ajax({
                    type: "GET",
                    url: "{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@createNode') }}",
                    data: {'id':id},
                    dataType: 'json',
                    success: function(data){
                        $("#id").val(data.id);
                        $("#title").val(data.title);
                        $("#description").val(data.description);
                        $("#creator_id").find("option[value='"+data.creator_id+"']").prop("selected",true);

                        $('#myModal').modal('show');
                    }
                });

            });
        })
    </script>
@endsection