<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <link href="{{asset('/assets/inspinia/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />

    <!-- Toastr style -->
    <link href="{{asset('/assets/inspinia/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet" />
    <!-- Gritter -->
    <link href="{{asset('/assets/inspinia/js/plugins/gritter/jquery.gritter.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/css/animate.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />

    <link href="{{asset('/assets/adminlte/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet" />

    <link href="{{asset('/assets/inspinia/css/plugins/select2/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/css/plugins/iCheck/custom.css')}}" rel="stylesheet" />

    <link href="{{asset('/assets/inspinia/css/plugins/footable/footable.core.css')}}" rel="stylesheet" />
    <link href="{{asset('/assets/inspinia/css/plugins/blueimp/css/blueimp-gallery.min.css')}}" rel="stylesheet" />
    <link href="{{elixir('assets/css/application.min.css')}}" rel="stylesheet" />

    <script src="{{asset('/assets/js/application_new.min.js')}}"></script>
    <script src="{{ asset ('/assets/inspinia/js/plugins/select2/select2.full.min.js')}}" type="text/javascript"></script>
    {{--时间插件--}}
    <script src="{{asset('/assets/adminlte/plugins/daterangepicker/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('/assets/adminlte/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/assets/layer/layer.js')}}"></script>

    {{--fancybox@3.5.7--}}
    <link rel="stylesheet" href="{{asset('/assets/fancybox3/dist/jquery.fancybox.min.css')}}" />
    <script src="{{asset('/assets/fancybox3/dist/jquery.fancybox.min.js')}}"></script>
    {{--fancybox@3.5.7--}}

    <style>
        .select2-container .select2-selection--single {
            background-color: #FFFFFF;
            background-image: none;
            border: 1px solid #e5e6e7;
            border-radius: 1px;
            color: inherit;
            display: block;
            padding: 3px 12px;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            width: 100%;
            font-size: 14px;
            height: 34px;
        }
    </style>
    @yield('css')
</head>

<body>
<div id="wrapper">
    @include('common.template.sidebar')
    <div id="page-wrapper" class="gray-bg">
        @include('common.template.header')
        @yield('content')
        @include('common.template.footer')
    </div>
</div>

@yield('scripts')
<script>
    $(".select2").select2();

    // fancybox 选项
    $('[data-fancybox]').fancybox({
    });

    //搜索去掉空值
    $(".search-form").on("submit", function () {
        $("input", $(this)).each(function () {
            if ($(this).val() == "") {
                $(this).attr('disabled', "disabled");
            }
        });
        $("select", $(this)).each(function () {
            var selected = $("option:selected", $(this)).val();
            if (selected == "") {
                $(this).attr("disabled", "disabled");
            }
        });
    });
    //对时间做处理
    var columns = {
        startDate: moment().subtract(6, 'days'),
        endDate: moment(),
        "autoApply": false,
        "opens": "center",
        autoUpdateInput: false, //set did not auto update input
        locale:{
            format: "YYYY-MM-DD",
            separator: ' - ',
            applyLabel: '{{trans('确定')}}',
            cancelLabel: '{{trans('取消')}}',
            weekLabel: 'W',
            customRangeLabel: '{{trans('自定义日期范围')}}',
            daysOfWeek: moment.weekdaysMin(),
            monthNames: moment.monthsShort(),
            firstDay: moment.localeData().firstDayOfWeek()
        },
        ranges: {
            '今天': [moment(), moment()],
            '昨天': [moment().subtract(1, 'days'), moment().subtract(1,'days')],
            '过去7天': [moment().subtract(6, 'days'), moment()],
            '本月': [moment().startOf('month'), moment().endOf('month')]
        }
    };
    $(".search-time").each(function () {
        var time = $(this).data('time');
        if(time){
            var arr = time.match(/^([0-9\-]{10})\s-\s([0-9\-]{10})/);
            if(arr.length === 3){
                columns.startDate = arr[1];
                columns.endDate = arr[2];
            }
            $(this).val(time);
        }
        $(this).daterangepicker(columns).on('cancel.daterangepicker', function(ev, picker) {
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).focus();
            $(this).val(picker.startDate.format(picker.locale.format)+picker.locale.separator+picker.endDate.format(picker.locale.format));
            $(this).blur();
            picker.hide();
        });
    });
</script>
</body>
</html>
