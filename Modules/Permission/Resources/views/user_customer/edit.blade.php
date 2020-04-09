@extends('common.template.blank')
@section('title')
    {{trans('common.system_name')}}
@endsection
@section('css')
    <link href="{{asset('/assets/inspinia/css/plugins/jsTree/style.min.css')}}" rel="stylesheet" />
    <style>
        .margin-left-80 {
            margin-left: -80px;
        }
        .padding-top-7 {
            padding-top: 7px;
        }
        .width-110 {
            width:110%;
        }
        .inline-block {
            display: inline-block;
        }
        .margin-left-20 {
            margin-left: 20px;
        }
    </style>
@endsection
@section('content')
    <section class="content">
        <div class="box box-primary" style="padding:20px 0;">
            <form id="my_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">用户名：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="name" value="{{$data->name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">登录邮箱：</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" name="email" value="{{$data->email}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">密码：</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="password" value="" data-required="false">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">确认密码：</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="password_2" value="" data-required="false">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">时区：</label>
                        <div class="col-sm-6">
                            {!! Timezone::selectForm($data->timezone,'时区',['class'=>'form-control select2','name'=>'timezone'],['title'=>' ']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">角色：</label>
                        <div class="col-sm-6">
                            {{--<select class="form-control select2" name="group_id" multiple="multiple">多选--}}
                            <select class="form-control select2" name="group_id" onchange="changeHandle(this)">
                                <option value="" title=" ">请选择</option>
                                @foreach($authGroups as $v)
                                    <option value="{{$v['id']}}" title=" " @if(isset($data->authUserGroup) && in_array($v['id'],array_pluck($data->authUserGroup,'group_id'))) selected="selected" @endif>{{$v['cn_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">菜单及权限：</label>
                        <div class="col-sm-6">
                            <div id="menus_rule_tree">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-sm-offset-10">
                        <button type="button" class="btn btn-warning" onclick="confirmCreate()">确认</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{asset('assets/inspinia/js/plugins/jsTree/jstree.min.js')}}"></script>
    <script>
        let menusUrl = '{{route('permission.permission.get_menus_rule_tree_disabled')}}';
        let groupId = '{{isset($data->authUserGroup[0])?$data->authUserGroup[0]->group_id:0}}';

        $(function () {
            menusRuleTree(groupId);
        });

        /**
         * 展示菜单权限列表
         */
        function menusRuleTree(groupId) {
            $('#menus_rule_tree').jstree('destroy');
            $('#menus_rule_tree').jstree({
                "core": {
                    "animation": 200,
                    "check_callback": false,
                    "themes": {"stripes": true, "variant": "large"},
                    "data": {
                        'url': menusUrl + '?group_id=' + groupId,
                        'data': function (node) {
                            return node;
                        }
                    },
                    "expand_selected_onload": false //关闭子节点有选中父节点自动打开
                },
                "checkbox": {
                    "keep_selected_style": false
                },
                "plugins": ["search", "checkbox"]
            });
        }

        function isEmpty(data) {
            return (data == "" || data == undefined || data == null) ? true : false;
        }

        /**
         * 关闭当前的 iframe 页面
         */
        function closeWin()
        {
            var index = parent.layer.getFrameIndex(window.name); // 先得到当前 iframe 层的索引
            parent.layer.close(index);  // 再执行关闭
        }

        /**
         * 确认创建
         */
        function confirmCreate()
        {
            try {
                let params = $('#my_form').serializeArray();
                $(params).each(function () {
                    let this_node;
                    this.value = this.value.trim();
                    if ($('input[name=' + this.name + ']').length) {
                        this_node = $('input[name=' + this.name + ']')
                    } else if ($('select[name=' + this.name + ']').length) {
                        this_node = $('select[name=' + this.name + ']');
                    } else {
                        throw new Error('输入框获取失败');
                    }
                    let label_name = $.trim(this_node.parent("div").prevAll('label:eq(0)').text());
                    label_name = label_name.substr(0, label_name.length - 1);
                    let required = $(this_node).attr('data-required') || true;
                    // 非空校验
                    if (required !== 'false' && isEmpty(this.value)) {
                        $(this_node).focus();
                        throw new Error(label_name + '不能为空');
                    }
                    // 格式校验
                    if (this.name === 'email') {
                        let checkEmail = /^([A-Za-z0-9])+([A-Za-z0-9]|[-]|[_]|[.])*([A-Za-z0-9])+@([-A-Za-z0-9])+\..+$/;
                        if (checkEmail.test(this.value) == false) {
                            $(this_node).focus();
                            throw new Error(label_name + '的格式不正确');
                        }
                    }
                    // if (!isEmpty(this.value) && (this.name === 'password' || this.name === 'password_2')) {
                    //     if (/^[a-zA-Z]\w{3,15}$/.test(this.value) == false) {
                    //         $(this_node).focus();
                    //         throw new Error(label_name + '的格式不正确(最少6位，包括至少1个大写字母，1个小写字母，1个数字，1个特殊字符)');
                    //     }
                    // }
                });
                if ($('input[name=password]').val() !== $('input[name=password_2]').val()) {
                    $('input[name=password_2]').focus();
                    throw new Error('两次密码不匹配！');
                }
                // 发起 ajax 请求
                layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
                $.ajax({
                    url: "{{route("permission.user_customer.update",['id'=>$data->id])}}",
                    // traditional: true,
                    method: "put",
                    data: params,
                    success: function (response) {
                        layer.closeAll();
                        console.log(response);
                        if (response.status == 200) {
                            toastr.success('操作成功');
                            setTimeout(function () {
                                window.parent.location.reload(); // 刷新父页面
                                closeWin();  // 关闭当前的 iframe
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
            } catch (e) {
                layer.closeAll();
                toastr.warning(e.message);
            }
        }

        function changeHandle(node) {
            let select_val = $(node).val();
            if (select_val.length > 0) {
                menusRuleTree(select_val);
            }
        }
    </script>
@endsection
