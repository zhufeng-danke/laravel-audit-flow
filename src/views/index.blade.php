@extends('flow::basic')

@section('content')
    <div id="wrapper">
        <div class="row">
            <div class="wrapper wrapper-content container">
                <div class="row">
                    <div class="ibox">
                        <div class="ibox-title"><h2>工作流列表</h2></div>
                        <button type="button" class="btn btn-primary btn-lg lego-right-top-buttons pull-right" data-toggle="modal" data-target="#myModal">
                            创建工作流
                        </button>
                        <div class="ibox-content">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>资源ID</th>
                                        <th>工作流名称</th>
                                        <th>工作流描述</th>
                                        <th>创建者</th>
                                        <th>创建时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($list as $v)
                                    <tr class="active">
                                        <th scope="row">{{ $v->id }}</th>
                                        <td>{{ $v->audit_bill_type_id }}</td>
                                        <td>{{ $v->title }}</td>
                                        <td>{{ $v->description }}</td>
                                        <td>{{ $v->creator_id }}</td>
                                        <td>{{ $v->created_at }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary">删除</button>
                                            <button type="button" class="btn btn-success">编辑</button>
                                        </td>
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

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">创建工作流</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>

@endsection
