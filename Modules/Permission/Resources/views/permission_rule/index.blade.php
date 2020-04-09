@extends('common.template.default')
@section('title')
    {{trans('common.system_name')}}
@endsection
@section('css')
    <!--修改模态框 出现的位置-->
    <style type="text/css">
        div.modal-content {
            top:200px;
        }
    </style>
@endsection
@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>{{getMenuTitle(Request::path())}} ({{count($datas) ? $datas->total() :0}})</h2>
            <ol class="breadcrumb">
                {!! getPathCate(Request::getRequestUri()) !!}
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="ibox-content">
                <form class="search-form" id="search-form">
                    <div class="row">
                        <div class="col-xs-2">
                            <select name="menu_id"  class="select2 form-control" >
                                <option value="">菜单</option>
                                @foreach($menus as $k=>$v)
                                    @if(!empty($v['cn_name']))
                                    <option value="{{$v['id']}}" @if($v['id']==Input::get('menu_id')) selected="selected" @endif>--{{!empty($v['cn_name'])?$v['cn_name']:'未命名'}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="id" value="{{Input::get('id')}}" class="form-control"
                                   placeholder="id">
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="pid" value="{{Input::get('pid')}}" class="form-control"
                                   placeholder="pid">
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="name" value="{{Input::get('name')}}" class="form-control"
                                   placeholder="权限">
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="cn_name" value="{{Input::get('cn_name')}}" class="form-control"
                                   placeholder="中文名称">
                        </div>
                        <div class="col-xs-1">
                            <select name="type"  class="select2 form-control" >
                                <option value="">类型</option>
                                @foreach(\App\Models\Permission\AuthRule::$allTypes as $k=>$v)
                                    <option value="{{$k}}" @if($k==Input::get('type')) selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <select name="status"  class="select2 form-control" >
                                <option value="">状态</option>
                                @foreach(\App\Models\Permission\AuthRule::$allStatus as $k=>$v)
                                    <option value="{{$k}}" @if($k==Input::get('status')) selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">搜索</button>
                        <button type="button" class="btn btn-danger" onclick="reset_url()">重置</button>
                        @can('createBtn', \App\Models\Permission\AuthRule::class)
                            <button type="button" class="btn btn-warning" style="margin-left: 10px;" onclick="create()">新增</button>
                        @endcan
                    </div>
                </form>
            </div>
            <div class="ibox-content" id="example2_wrapper">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr role="row">
                            {{--<th width="2%"><input type="checkbox" name="checkAll" onclick="checkAll(this)"/></th>--}}
                            <th>id</th>
                            <th>pid</th>
                            <th>权限</th>
                            <th>中文名称</th>
                            <th>类型</th>
                            <th>状态</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th width="150px;">操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($datas)>0)
                            @foreach($datas as $data)
                                <tr role="row" data-id="{{$data->id}}">
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->pid}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->cn_name}}</td>
                                    <td>{{\App\Models\Permission\AuthRule::$allTypes[$data->type]}}</td>
                                    <td>{{\App\Models\Permission\AuthRule::$allStatus[$data->status]}}</td>
                                    <td>{{getUserDateTime($data->created_at)}}</td>
                                    <td>{{getUserDateTime($data->updated_at)}}</td>
                                    <td>
                                        @can('statusBtn', \App\Models\Permission\AuthRule::class)
                                            @if($data->status==\App\Models\Permission\User::ENABLED)
                                                <button type="button" class="btn btn-danger" onclick="changeStatus('{{route('permission.permission_rule.update', ['id'=>$data->id])}}', this.value);" value="{{$data->status}}">禁用</button>
                                            @else
                                                <button type="button" class="btn btn-success" onclick="changeStatus('{{route('permission.permission_rule.update', ['id'=>$data->id])}}', this.value);" value="{{$data->status}}">启用</button>
                                            @endif
                                        @endcan
                                        @can('editBtn', \App\Models\Permission\AuthRule::class)
                                                <button type="button" class="btn btn-info" onclick="edit('{{route('permission.permission_rule.edit',['id'=>$data->id])}}')">编辑</button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                @if(count($datas)>0)
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            {{$datas->appends(Request::all())->links()}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script>
    function create()
    {
        layer.open({
            type: 2,
            skin: 'layui-layer-rim', //加上边框
            area: ['1000px', '700px'],
            fix: false, //不固定
            shade: 0.4,
            shadeClose: true,
            title :'新增',
            content: '{{route('permission.permission_rule.create')}}',
            shift:-1,
            end: function(layero, index){
                console.log('layer层被销毁');
            },
            success: function(layero, index) {
            }
        });
    }

    function edit(url)
    {
        layer.open({
            type: 2,
            skin: 'layui-layer-rim', //加上边框
            area: ['1000px', '700px'],
            fix: false, //不固定
            shade: 0.4,
            shadeClose: true,
            title :'编辑',
            content: url,
            shift:-1,
            end: function(layero, index){
                console.log('layer层被销毁');
            },
            success: function(layero, index) {
            }
        });
    }

    function reset_url()
    {
        location = '{{Request::url()}}';
    }

    function changeStatus(url, status) {
        console.log(url, status);
        let tip = '';
        let new_status = null;
        switch (status) {
            case "1":
                tip = '请确认是否禁用？';
                new_status = 2;
                break;
            case "2":
                tip = '请确认是否启用？';
                new_status = 1;
                break;
            default:
                layer.msg('状态异常，请核实', {icon:2});return;
        }

        let data = {
            _token: "{{Session::token()}}",
            status: new_status,
        };

        layer.confirm(tip, {
            btn: ['确认', '取消'] //按钮
            , btn1: function () {
                layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
                $.ajax({
                    url: url,
                    type:"put",
                    data: data,
                    success: function (response) {
                        console.log(response);
                        // return;
                        if (response.status==200) {
                            layer.msg('操作成功', {icon:1});
                            window.location.reload(); // 刷新
                        } else {
                            layer.msg(response.msg, {icon:2});
                        }
                    },
                });
            }
            , btn2: function (index) {
                layer.close(index);
            }
        });
    }
</script>
@endsection
