<style type="text/css">
 #side-menu li,body{
     background-color: #2f4050!important;
 }
</style>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    {{--<span>--}}
                    {{--<a href="/dashboard"><img alt="image" class="img-circle" src="{{ asset ('/assets/img/patpat_logo.png')}}" width="40px" height="40px"/></a>--}}
                    {{--</span>--}}
                    <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void(0)">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{trans('common.system_name')}}</strong>
                    </span> <span class="text-muted text-xs block">{{Auth::user()->name}} <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="/auth/logout">退出</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    {{--PatPat--}}
                </div>
            </li>
            @foreach($menus as $menu)
                <li @foreach($menu['menus'] as $submenu)
                        @if($submenu['is_active']) class="active"
                        @endif
                    @endforeach>
                    <a href="javascript:void(0)"><i style="width: 15px;" class="{{$menu['icon']}}"></i> <span class="nav-label">{{$menu['title']}}</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                    @foreach($menu['menus'] as $submenu)
                        <li @if($submenu['is_active']) class="active" @endif>
                            <a href="{{$submenu['link']}}">{{$submenu['title']}}</a>
                        </li>
                    @endforeach
                    </ul>
                </li>
            @endforeach
            <li class="landing_link">
                <a target="_blank" href="/dashboard"><i style="width: 15px;" class="fa fa-star"></i> <span class="nav-label">控制面板</span></a>
            </li>
            <li class="special_link">
                <a href="/auth/logout"><i style="width: 15px;" class="fa fa-database"></i> <span class="nav-label">用户登出</span></a>
            </li>
        </ul>

    </div>
</nav>
