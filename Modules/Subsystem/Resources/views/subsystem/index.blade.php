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
                            <input type="text" name="name" value="{{Input::get('name')}}" class="form-control" placeholder="客户名称">
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="nickname" value="{{Input::get('nickname')}}" class="form-control" placeholder="客户简称">
                        </div>
                        <div class="col-xs-1">
                            <input type="text" name="code" value="{{Input::get('code')}}" class="form-control" placeholder="客户代码">
                        </div>
                        <div class="col-xs-1">
                            <select name="status"  class="select2 form-control" >
                                <option value="">状态</option>
                                @foreach(\App\Models\Subsystem\Subsystem::$allStatus as $k=>$v)
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
                            <th>ID</th>
                            <th>客户名称</th>
                            <th>客户简称</th>
                            <th>客户代码</th>
                            <th>密钥</th>
                            <th>客户联系人</th>
                            <th>联系人电话</th>
                            <th>物流产品配置</th>
                            <th>发货规则配置</th>
                            <th>状态</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>操作</th>
                            {{--<th width="5%">操作</th>--}}
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($datas)>0)
                            @foreach($datas as $data)
                                <tr role="row" data-id="{{$data->id}}">
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->nickname}}</td>
                                    <td>{{$data->code}}</td>
                                    <td>
                                        @if($data->customerAuthorization)
                                            <div>app_id：{{$data->customerAuthorization->app_id}}</div>
                                            <div>app_secret：{{$data->customerAuthorization->app_secret}}</div>
                                            <div>access_token：{{$data->customerAuthorization->access_token}}</div>
                                        @endif
                                    </td>
                                    <td>{{$data->contact}}</td>
                                    <td>{{$data->contact_phone}}</td>
                                    <td><a href="{{route('customer.logistics_product.index', ['customer_id'=>$data->id])}}">物流产品</a></td>
                                    <td><a href="{{route('customer.shipping_rule.index', ['customer_id'=>$data->id])}}">发货规则</a></td>
                                    <td>{{\App\Models\Subsystem\Subsystem::$allStatus[$data->status]}}</td>
                                    <td>{{getUserDateTime($data->created_at)}}</td>
                                    <td>{{getUserDateTime($data->updated_at)}}</td>
                                    <td>
                                        @can('statusBtn', \App\Models\Permission\AuthRule::class)
                                            @if($data->status==\App\Models\Subsystem\Subsystem::ENABLED)
                                                <button type="button" class="btn btn-danger" onclick="showModal('{{route('customer.customer.update', ['id'=>$data->id])}}', this.value, '{{$data->name}}');" value="{{\App\Models\Subsystem\Subsystem::DISABLED}}">禁用</button>
                                            @else
                                                <button type="button" class="btn btn-success" onclick="showModal('{{route('customer.customer.update', ['id'=>$data->id])}}', this.value, '{{$data->name}}');" value="{{\App\Models\Subsystem\Subsystem::ENABLED}}">启用</button>
                                            @endif
                                        @endcan

                                        <button type="button" class="btn btn-info" onclick="generateAuthorization({{$data->id}})" @if($data->customerAuthorization) disabled @endif>生成密钥</button>
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

    {{--模态框--}}
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel" id="myModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="gridSystemModalLabel"></h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="url" value=""/>
                    <input type="hidden" id="status" value=""/>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-8 text-left">
                            <h4>客户名称：<span id="customer_name"></span></h4>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-8 text-left" id="modal_info"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="changeStatus();">确认</button>
                </div>
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
            content: '{{route('customer.customer.create')}}?id=1',
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

    /**
     * 打开模态框
     * @param url
     * @param status
     */
    function showModal(url, status, name) {
        let modal_title='';
        let modal_info='';

        if(status==1){
            modal_title = '启用客户';
            modal_info = '启用后，客户可以开始使用 SMS 系统，请确认是否启用客户账号？';
        }else if(status==2){
            modal_title = '禁用客户';
            modal_info = '停用后，客户将无法再继续使用 SMS 系统，请确认是否停用客户账号？';
        }else {
            toastr.error('status 异常！');return;
        }

        $('#gridSystemModalLabel').html(modal_title);
        $('#customer_name').html(name);
        $('#modal_info').html(modal_info);

        $('#url').val(url);
        $('#status').val(status);

        // 显示模态框
        $('#myModal').modal('show');
    }

    function changeStatus()
    {
        var params = {
            _token: "{{Session::token()}}",
            status: $('#status').val(),
        };

        console.log(params);
        // return;

        // 发起 ajax 请求
        $('#myModal').modal('hide');
        layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
        $.ajax({
            url: $('#url').val(),
            // traditional: true,
            method: "put",
            data: params,
            success: function (response) {
                layer.closeAll();
                // console.log(response);
                if (response.status == 200) {
                    toastr.success('操作成功');
                    setTimeout(function () {
                        window.location.reload(); // 刷新页面
                        // closeWin();  // 关闭当前的 iframe
                    },2000);
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function (xhr, errorText, errorType) {
                layer.closeAll();
                toastr.error('处理失败，错误码： ' + xhr.status + ' ' + xhr.statusText);
            }
        });
    }

    function generateAuthorization(id)
    {
        var params = {
            _token: "{{Session::token()}}",
            id: id,
        };

        // console.log(params);
        // return;

        // 发起 ajax 请求
        layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
        $.ajax({
            url: "{{route('customer.customer.generateAuthorization')}}",
            // traditional: true,
            method: "post",
            data: params,
            success: function (response) {
                layer.closeAll();
                // console.log(response);
                if (response.status == 200) {
                    toastr.success('操作成功');
                    setTimeout(function () {
                        window.location.reload(); // 刷新页面
                        // closeWin();  // 关闭当前的 iframe
                    },2000);
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function (xhr, errorText, errorType) {
                layer.closeAll();
                toastr.error('处理失败，错误码： ' + xhr.status + ' ' + xhr.statusText);
            }
        });
    }
</script>
@endsection
