<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{trans('common.system_name')}} </title>
    <link href="{{asset('/favicon.ico')}}" rel="shortcut icon" type="image/ico"/>
    <style type="text/css">
        html {
            position: relative;
            min-height: 100%;
        }
        body {
            /* Margin bottom by footer height */
            margin-bottom: 60px;
        }
        footer {
            position: absolute;
            bottom: 0;
            text-align: center;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            background-color: rgba(68, 65, 66, 0);
        }
    </style>
</head>
<body>
<div class="container" style="text-align: center;margin-top: 50px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <h2>{{trans('common.system_name')}}</h2>
        </div>
    </div>
</div>
<div class="container">
    @include('errors.flash')
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">登录</div>
                <div class="panel-body" style="margin-top: 20px; margin-bottom: 20px;">
                    @include('errors.show')
                    <form class="form-horizontal" id="form" role="form" method="post">
                        <input name="_token" type="hidden" value="{!! csrf_token() !!}" />

                        <div class="form-group">
                            <label class="col-sm-4 control-label">用户名</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control" name="account" required oninvalid="this.setCustomValidity('用户名必填')" placeholder="请输入邮箱账号">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">密码</label>
                            <div class="col-sm-5">
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">验证码</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="captcha" required>
                            </div>
                            <div class="col-sm-2" style="margin-left: -10px;cursor: pointer;"><img  title="点击刷新" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random();"/></div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5 col-sm-offset-4">
                                <div class="checkbox">
                                    <label> <input type="checkbox" name="remember"> 记住密码 </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 20px">
                            <div class="col-sm-5 col-sm-offset-4">
                                <button type="submit" class="btn btn-primary form-control">登录</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 底部 copyright -->
<footer>
    <div class="container">
        <strong>Copyright © 2020 1000shores</strong> All rights reserved.
    </div>
</footer>
<link href="{{asset("/assets/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
<link href="{{elixir('assets/css/application.min.css')}}" rel="stylesheet" />
<link href="{{ asset("/assets/adminlte/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
<script src="{{elixir('assets/js/application.min.js')}}"></script>
<script>
    let user = $.cookie('key_u');
    let pwd = $.cookie('key_p');
    $(function () {
        // 判断是否存在cookie
        if (user) {
            $("input[name=account]").val(user);
            $("input[name=password]").val(pwd);
            // $('input[name=remember]').attr('checked', true);
        }
        $("#form").submit(function (data) {
            if ($('input[name=remember]').is(':checked')) {
                $.cookie("key_u", $("input[name=account]").val(), {expires: 7}); //存储一个带7天期限的cookie
                $.cookie("key_p", $("input[name=password]").val(), {expires: 7});
            } else {
                $.cookie('key_u', '');
                $.cookie('key_p', '');
            }
        });
    });
</script>
</body>
</html>