@extends('common.template.blank')
@section('title')
    {{trans('common.system_name')}}
@endsection
@section('css')
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
            <form id="my_form" name="my_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="pid" value="0">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">权限：</label>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-xs-4">
                                    <select name="menuFirst" onchange="menuFirstChange(this)" class="select2 form-control" data-required="false">
                                        <option value="0" title=" ">请选择</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <select name="menuSecond" onchange="menuSecondChange(this)" class="select2 form-control" data-required="false">
                                        <option value="" title=" ">请选择</option>
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <select name="menuThird" onchange="menuThirdChange(this)" class="select2 form-control" data-required="false">
                                        <option value="" title=" ">请选择</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">类型：</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="type" readonly="readonly">
                                <option value="" title=" ">请选择</option>
                                @foreach(\App\Models\Permission\AuthRule::$allTypes as $k => $v)
                                    <option value="{{$k}}" title=" " @if($data->type==$k) selected="selected" @endif>{{$v}}</option>
                                @endforeach
                            </select>
                            <p class="help-block">*除菜单之外，均属于按钮类型</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">权限：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="name" value="{{$data->name}}">
                            <p class="help-block">1.控制器权限(模块名称.控制器名.方法名)</p>
                            <p class="help-block">2.Policy权限(模块名称.控制器名.方法名.ability.权限名)</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">中文名称：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="cn_name" value="{{$data->cn_name}}">
                            <p class="help-block">*当类型为菜单时，显示为菜单</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">菜单URL：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="url_path" value="{{$data->url_path}}" data-required="false" placeholder="url" readonly="readonly">
                            <p class="help-block">*当类型为菜单时，须填写url路径,不带host</p>
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
    <script>
        let rule_menus = '{!! json_encode($menus) !!}';// 全部权限数据
        let rule_pid = '{!! $data->pid !!}'; // 初始化权限PID
        let type_menu = '{{ \App\Models\Permission\AuthRule::TYPE_MENU}}';
        let type_controller = '{{\App\Models\Permission\AuthRule::TYPE_CONTROLLER}}';
        let type_policy = '{{\App\Models\Permission\AuthRule::TYPE_POLICY}}';

        $(function () {
            initMenuFirst();
            initSelect();
        });

        function initMenuFirst() {
            let node = $('select[name=menuFirst]');
            // 初始一级菜单
            $.each(JSON.parse(rule_menus), function (i, v) {
                if (v.pid == 0) {
                    $(node).append('<option value="' + v.id + '" title=" ">' + v.cn_name + '</option>');
                }
            });
        }

        function initSelect() {
            let menuFirstNode = $('select[name=menuFirst]');
            let menuSecondNode = $('select[name=menuSecond]');
            let menuThirdNode = $('select[name=menuThird]');
            // 初始选中
            $.each(JSON.parse(rule_menus), function (i, v) {
                // 初始一级菜单
                if (v.id == rule_pid) {
                    $(menuFirstNode).val(rule_pid).select2();
                    menuFirstChange(menuFirstNode);
                } else {
                    $.each(v.children, function (y, z) {
                        if (z.id == rule_pid) {
                            $(menuFirstNode).val(v.id).select2();
                            menuFirstChange(menuFirstNode);
                            $(menuSecondNode).val(rule_pid).select2();
                            menuSecondChange(menuSecondNode);
                        } else {
                            $.each(z.children, function (n, m) {
                                if (m.id == rule_pid) {
                                    $(menuFirstNode).val(v.id).select2();
                                    menuFirstChange(menuFirstNode);
                                    $(menuSecondNode).val(z.id).select2();
                                    menuSecondChange(menuSecondNode);
                                    $(menuThirdNode).val(rule_pid).select2();
                                    menuThirdChange(menuThirdNode);
                                }
                            });
                        }
                    });
                }
            });
        }

        function emptySelectNode(node) {
            $.each($(node).children('option'),function (i,v) {
                if(!isEmpty($(v).val())) $(v).remove();
            });
        }

        function refreshType() {
            let menuFirstNode = $('select[name=menuFirst]');
            let menuSecondNode = $('select[name=menuSecond]');
            let menuThirdNode = $('select[name=menuThird]');
            let urlPathNode = $('input[name=url_path]');
            urlPathNode.attr('readonly',true);
            let node = $('select[name=type]');
            if (!isEmpty($(menuThirdNode).val())) {
                $(node).val(type_policy).select2();
            } else if (!isEmpty($(menuSecondNode).val())) {
                $(node).val(type_policy).select2();
            } else if ($(menuFirstNode).val()!=0) {
                urlPathNode.attr('readonly',false);
                $(node).val(type_controller).select2();
            } else {
                $(node).val(type_menu).select2();
            }
        }

        function menuFirstChange(node) {
            let menuSecondNode = $('select[name=menuSecond]');
            let menuThirdNode = $('select[name=menuThird]');
            emptySelectNode(menuSecondNode);
            emptySelectNode(menuThirdNode);
            let select_val = $(node).val();
            if (select_val.length > 0) {
                $('input[name=pid]').val(select_val);
                $.each(JSON.parse(rule_menus), function (i, v) {
                    if (v.id == select_val) {
                        $.each(v.children, function (y, z) {
                            $(menuSecondNode).append('<option value="' + z.id + '" title=" ">' + z.cn_name + '</option>');
                        });
                    }
                });
            }
            refreshType();
        }

        function menuSecondChange(node) {
            let menuFirstNode = $('select[name=menuFirst]');
            let menuThirdNode = $('select[name=menuThird]');
            emptySelectNode(menuThirdNode);
            let select_val = $(node).val();
            if (select_val.length>0) {
                $('input[name=pid]').val(select_val);
                $.each(JSON.parse(rule_menus), function (i, v) {
                    if (v.id == menuFirstNode.val()) {
                        $.each(v.children, function (y, z) {
                            if(z.id == select_val){
                                console.log(z);
                                $.each(z.children, function (n, m) {
                                    $(menuThirdNode).append('<option value="' + m.id + '" title=" ">' + m.cn_name + '</option>');
                                });
                            }
                        });
                    }
                });
            } else {
                $('input[name=pid]').val(menuFirstNode.val());
            }
            refreshType();
        }

        function menuThirdChange(node) {
            let menuSecondNode = $('select[name=menuSecond]');
            let select_val = $(node).val();
            if (select_val.length>0) {
                $('input[name=pid]').val(select_val);
            } else {
                $('input[name=pid]').val(menuSecondNode.val());
            }
            refreshType();
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
                let params = {};
                let form = $('#my_form').serializeArray();
                $(form).each(function (i, v) {
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
                    if (this.name === 'name') {
                        this.value = this.value.toLowerCase();
                    }
                    if (v.name != 'menuFirst' && v.name != 'menuSecond' && v.name != 'menuThird') {
                        params[v.name] = v.value;
                    }
                });
                // 发起 ajax 请求
                layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
                $.ajax({
                    url: "{{route("permission.permission_rule.update",['id'=>$data->id])}}",
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
    </script>
@endsection
