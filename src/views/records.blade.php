@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">

                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#records" data-toggle="tab">审核操作记录</a></li>
                    <li role="presentation"><a href="#node" data-toggle="tab">审核流程</a></li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="records">
                        <div class="row">
                            <div class="ibox">
                                <div class="ibox-content">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>单据ID</th>
                                            <th>审核流ID</th>
                                            <th>节点ID</th>
                                            <th>审核人ID</th>
                                            <th>审核人评论</th>
                                            <th>审核时间</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($list as $v)
                                            <tr class="active">
                                                <th scope="row">{{ $v->id }}</th>
                                                <td>{{ $v->bill_id }}</td>
                                                <td>{{ $v->flow_title }}</td>
                                                <td>{{ $v->node_title }}</td>
                                                <td>{{ $v->name }}</td>
                                                <td>{{ $v->comment }}</td>
                                                <td>{{ $v->created_at }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="node" class="tab-pane">
                        @foreach ($node_list as $v)
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
                                            @foreach ($v->users as $s)
                                                <p>审核人:{{ $s->name }}({{ $s->email }})
                                            @endforeach
                                        </div>
                                        <div class="panel-footer">

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

@endsection
@section('java-script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection