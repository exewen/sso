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
                <input type="hidden" name="rule_ids" id="rule_ids" data-required="false">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">角色中文名：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="cn_name" value="{{$data->cn_name}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">角色英文名：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="name" value="{{$data->name}}" placeholder="英文字母或_组合">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">客户：</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="customer_id" onchange="changeHandle(this);">
                                <option value="" title=" ">请选择</option>
                                @foreach($customers as $v)
                                    <option value="{{$v['id']}}" title=" " @if($data->customer_id==$v['id']) selected="selected" @endif>{{$v['name']}}</option>
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
        let customer_id = '{!! $data->customer_id !!}';
        $(function () {
            changeHandle($("select[name=customer_id]"),true);
            // 展示菜单权限列表
            $('#menus_rule_tree').jstree({
                "core": {
                    "animation": 200,
                    "check_callback": false,
                    "themes": {"stripes": true, "variant": "large"},
                    "data": {
                        'url': '{{route('permission.permission.get_menus_rule_tree',['group_id'=>$data->id])}}',
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
        });

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
                let selected_menu_ids = $('#menus_rule_tree').jstree(true).get_selected(true);
                let rule_ids = [];
                selected_menu_ids.forEach(function (menu) {
                    if (!isNaN(menu.id) && rule_ids.indexOf(menu.id) < 0) {
                        rule_ids.push(menu.id);
                    }
                    $(menu.parents).each(function (i, v) {
                        if (!isNaN(v) && rule_ids.indexOf(v) < 0) {
                            rule_ids.push(v);
                        }
                    });
                });
                rule_ids.sort();
                $('#rule_ids').val(rule_ids.join(","));

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
                    if (this.name === 'cn_name') {
                        let checkCnName = /^[\s\S]{1,20}$/;
                        if (checkCnName.test(this.value) == false || /[\"|\']/.test(this.value) == true) {
                            $(this_node).focus();
                            throw new Error(label_name + '的格式不正确');
                        }
                    }
                    if (this.name === 'name') {
                        let checkName = /^[a-zA-Z_\/ ]{2,20}$/;
                        if (checkName.test(this.value) == false) {
                            $(this_node).focus();
                            throw new Error(label_name + '的格式不正确');
                        }
                    }
                });
                // 发起 ajax 请求
                layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
                $.ajax({
                    url: "{{route("permission.group_system.update",['id'=>$data->id])}}",
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

        // 平台用户才能修改英文名称
        function changeHandle(node,history)
        {
            history = history || false;
            let internal_customer_id = '{{\Permission::getInternalCustomerId()}}';
            let name_system_root = '{{\App\Models\Permission\AuthGroup::NAME_SYSTEM_ROOT}}';
            let name_node = $('input[name=name]');
            name_node.attr('readonly', true);
            // $('select[name=customer_id]').attr('disabled', true);
            if (internal_customer_id === customer_id) {
                name_node.attr('readonly', false);
                if (name_system_root === name_node.val()) {
                    name_node.attr('readonly', true);
                    $('input[name=cn_name]').attr('readonly', true);
                }
            }
        }
    </script>
@endsection
