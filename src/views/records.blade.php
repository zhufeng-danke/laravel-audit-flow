@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">
                <div class="row">
                    <div class="ibox">
                        <div class="ibox-title"><h2>审核操作记录</h2></div>
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
        </div>
    </div>

@endsection
@section('java-script')
    <script>
        $(document).ready(function(){

        })
    </script>
@endsection