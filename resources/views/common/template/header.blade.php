<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="javascript:void(0)"><i class="fa fa-bars"></i> </a>
            {{--<form role="search" class="navbar-form-custom" action="">--}}
                {{--<div class="form-group">--}}
                    {{--<input type="text" placeholder="请输入您要查找的内容..." class="form-control" name="top-search" id="top-search">--}}
                {{--</div>--}}
            {{--</form>--}}
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">欢迎进入{{trans('common.system_name')}}</span>
            </li>
            {{--<li class="dropdown notifications-menu">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                  {{--<i class="fa fa-language"> @if (App::isLocale('en')){{'English'}}@else{{'简体中文'}}@endif</i>--}}
                {{--</a>--}}
                {{--<ul class="dropdown-menu">--}}
                    {{--<li>--}}
                        {{--<a href="/dashboard/change_local?lang=chs">--}}
                            {{--<div>--}}
                                {{--<i class="fa fa-flag text-aqua"></i> 简体中文--}}
                            {{--</div>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                        {{--<a href="/dashboard/change_local?lang=en">--}}
                            {{--<div>--}}
                                {{--<i class="fa fa-flag text-aqua"></i> English--}}
                            {{--</div>--}}
                        {{--</a>--}}
                    {{--</li>--}}
                {{--</ul>--}}
            {{--</li>--}}

            <li>
                <a href="/auth/logout">
                    <i class="fa fa-sign-out"></i> Log out
                </a>
            </li>
            {{--  <li>
                <a class="right-sidebar-toggle">
                    <i class="fa fa-tasks"></i>
                </a>
            </li>  --}}
        </ul>
    </nav>
</div>
<script type="text/javascript">

  //编辑用户信息
  function editUserInfo(email)
  {
    layer.open({
      type: 2,
      area: ['600px','600px'],
      fix: false, //不固定
      maxmin: false,
      shade:0.4,
      title: '{{trans('编辑个人信息')}}',
      content: '{{url('/user/edit')}}?email='+email,
    });
  }

</script>