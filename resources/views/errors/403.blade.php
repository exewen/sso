<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{env('SYSTEM_NAME', 'WMS')}}</title>
    <link href="{{asset('/favicon.ico')}}" rel="shortcut icon" type="image/ico"/>
    <link href="{{asset("/assets/css/bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{elixir('assets/css/application.min.css')}}" rel="stylesheet" />
    <link href="{{ asset("/assets/adminlte/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
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
            <div class="panel panel-default">
                <style type="text/css">
                    .error404-wrap h3 {
                        font-size: 60px;
                        margin: 80px 0 40px;
                    }
                    .error404-wrap a {
                        display: inline-block;
                        margin: 25px 15px 50px;
                        color: #333;
                        text-decoration: underline;
                    }
                </style>
                <div class="error404-wrap">
                    <h3>无访问权限</h3>
                    <p>3秒后带你回到首页</p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- 底部 copyright -->
<footer>
    <div class="container">
        <strong>Copyright © 2016 <a href="http://www.patpat.com" target="_blank">Interfocus Inc</a>.</strong> All rights reserved.
    </div>
</footer>
<script src="{{elixir('assets/js/application.min.js')}}"></script>
<script>
    window.setTimeout("window.location='/'",3000);
</script>
</body>
</html>
