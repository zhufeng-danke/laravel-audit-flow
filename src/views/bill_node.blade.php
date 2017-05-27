@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">
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

@endsection