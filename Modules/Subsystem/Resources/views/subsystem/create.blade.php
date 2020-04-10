@extends('common.template.blank')
@section('title')
    {{trans('common.system_name')}}
@endsection
@section('css')
@endsection
@section('content')
    <section class="content">
        <div class="box box-primary" style="padding:20px 0;">
            <form id="my_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">客户名称：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="name" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">客户简称：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="nickname" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">客户代码：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="code" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">客户联系人：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="contact" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-offset-1 control-label">联系人电话：</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="contact_phone" value="">
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
                var name = $('input[name=name]').val().trim();
                if (name.length==0) throw new Error('名称不能为空');

                var nickname = $('input[name=nickname]').val().trim();
                if (nickname.length==0) throw new Error('简称不能为空');

                var code = $('input[name=code]').val();
                if (code.length==0) throw new Error('客户代码不能为空');
                if (/^[A-Z]+$/.test(code)==false) throw new Error('客户代码只能由大写字母组成');
                if (code.length<2 || code.length>10) throw new Error('客户代码的长度为2-10');

                var contact = $('input[name=contact]').val().trim();
                if (contact.length==0) throw new Error('联系人不能为空');

                var contact_phone = $('input[name=contact_phone]').val().trim();
                if (contact_phone.length==0) throw new Error('联系人电话不能为空');
                if (/^[0-9]+$/.test(contact_phone)==false) throw new Error('联系人电话只能由数字组成');
                if (contact_phone.length<11 || contact_phone.length>14) throw new Error('联系人电话的长度为11-14');

                let params = {
                    _token: "{{Session::token()}}",
                    name: name,
                    nickname: nickname,
                    code: code,
                    contact: contact,
                    contact_phone: contact_phone
                };

                console.log(params);
                // return;

                // 发起 ajax 请求
                layer.msg('正在处理...', {icon: 16,shade: [0.2,'#000'], time:5000});
                $.ajax({
                    url: "{{route("customer.customer.store")}}",
                    // traditional: true,
                    method: "post",
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
