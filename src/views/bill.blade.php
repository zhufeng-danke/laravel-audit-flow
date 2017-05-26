@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">
                <div class="row">
                    <div class="ibox">
                        <div class="ibox-title"><h2>审核单据列表</h2></div>
                        <div class="ibox-content">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>单据ID</th>
                                    <th>审核流</th>
                                    <th>资源</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($list as $v)
                                    <tr class="active">
                                        <th scope="row">{{ $v->id }}</th>
                                        <td>{{ $v->bill_id }}</td>
                                        <td>{{ $v->flow_title }}</td>
                                        <td>{{ $v->type_title }}</td>
                                        <td>{{ $v->created_at }}</td>
                                        <td>
                                            @if($v->status == 1)
                                                <button type="button" class="btn btn-success flow_close" status="0" id="{{ $v->id }}">关闭</button>
                                            @else
                                                <button type="button" class="btn btn-success flow_close" status="1" id="{{ $v->id }}">开启</button>
                                            @endif
                                            <a class="btn btn-default" href="{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@getRecords',['bill_id' => $v->bill_id]) }}" role="button">查看</a>
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

@endsection
@section('java-script')
    <script>
        $(document).ready(function(){

            $(".flow_close").click(function () {

                var id = $(this).prop("id");
                var status = $(this).attr("status");

                if(status == 0) {
                    var msg = '确定要关闭此单据审核流吗!';
                }else {
                    var msg = '确定要开启此单据审核流吗!';
                }

                if(confirm(msg)){
                    $.ajax({
                        type: "POST",
                        url: "{{ action('\WuTongWan\Flow\Http\Controllers\FlowController@billClose') }}",
                        data: {'id':id,'status':status},
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