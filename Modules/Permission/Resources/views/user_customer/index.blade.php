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
                        <div class="col-xs-1">
                            <input type="text" name="name" value="{{Input::get('name')}}" class="form-control"
                                   placeholder="用户名">
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="email" value="{{Input::get('email')}}" class="form-control"
                                   placeholder="邮箱">
                        </div>
                        <div class="col-xs-1">
                            <select name="status"  class="select2 form-control" >
                                <option value="">状态</option>
                                @foreach(\App\Models\Permission\User::$allStatus as $k=>$v)
                                    <option value="{{$k}}" @if($k==Input::get('status')) selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-1">
                            <select name="group_id"  class="select2 form-control" >
                                <option value="">角色</option>
                                @foreach($authGroups as $k=>$v)
                                    <option value="{{$v['id']}}" @if($v['id']==Input::get('group_id')) selected @endif>{{$v['cn_name']}}</option>
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
                            <th>用户id</th>
                            <th>客户</th>
                            <th>用户名</th>
                            <th>邮箱</th>
                            <th>角色</th>
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
                                    <td>{{isset($data->customer->name)?$data->customer->name:''}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->email}}</td>
                                    <td>
                                        @foreach($data->authUserGroup as $authUserGroup)
                                            <span class="auth_user_group" is-customer-admin="{{$data->customer_id!=\Permission::getInternalCustomerId() && $authUserGroup->authGroup->name==\App\Models\Permission\AuthGroup::NAME_CUSTOMER_ADMIN?1:0}}">{{$authUserGroup->authGroup->cn_name}}</span>
                                        @endforeach
                                    </td>
                                    <td>{{\App\Models\Permission\User::$allStatus[$data->status]}}</td>
                                    <td>{{getUserDateTime($data->created_at)}}</td>
                                    <td>{{getUserDateTime($data->updated_at)}}</td>
                                    <td>
                                        @can('statusBtn', \App\Models\Permission\AuthRule::class)
                                            @if($data->status==\App\Models\Permission\User::ENABLED)
                                                <button type="button" class="btn btn-danger" data-json='{!! $data !!}' onclick="changeStatus('{{route('permission.user_customer.update', ['id'=>$data->id])}}', this.value, this);" value="{{$data->status}}">禁用</button>
                                            @else
                                                <button type="button" class="btn btn-success" data-json='{!! $data !!}' onclick="changeStatus('{{route('permission.user_customer.update', ['id'=>$data->id])}}', this.value, this);" value="{{$data->status}}">启用</button>
                                            @endif
                                        @endcan
                                        @can('editBtn', \App\Models\Permission\AuthRule::class)
                                                <button type="button" class="btn btn-info" onclick="edit('{{route('permission.user_customer.edit',['id'=>$data->id])}}')">编辑</button>
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
            content: '{{route('permission.user_customer.create')}}',
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

    function changeStatus(url, status, obj) {
        console.log(url, status);
        let data_json = $.parseJSON($(obj).attr('data-json'));
        let tip = '';
        let new_status = null;
        let userName = data_json.name;
        let customerName = data_json.customer.name;
        let user_group = '';
        let forbiddenTip = '停用后，该用户无法再登录系统，是否停用？';
        $.each($(obj).parents('tr').find('.auth_user_group'), function (i, v) {
            user_group = $(v).text();
            if ($(v).attr('is-customer-admin') == 1) {
                user_group += $(v).text();
            }
        });
        switch (status) {
            case "1":
                tip = '用户名：' + userName + '<br>用户角色：' + customerName + '-' + user_group + '<br>' + forbiddenTip;
                title='停用用户';
                new_status = 2;
                break;
            case "2":
                tip = '用户名：' + userName + '<br>用户角色：' + customerName + '-' + user_group + '<br>启用后，用户可登录系统？';
                new_status = 1;
                title='启用用户';
                break;
            default:
                layer.msg('状态异常，请核实', {icon:2});return;
        }

        let data = {
            _token: "{{Session::token()}}",
            status: new_status,
        };

        layer.confirm(tip, {
            btn: ['确认', '取消'], title: title //按钮
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
