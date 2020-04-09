<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html xmlns="http://www.w3.org/1999/html">
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  <meta name="csrf-token" content="{!! csrf_token() !!}">

  <link href="{{asset('/favicon.ico')}}" rel="shortcut icon" type="image/ico"/>

  <link href="{{asset('/assets/inspinia/css/bootstrap.min.css')}}" rel="stylesheet" />
  <link href="{{asset('/assets/inspinia/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />


  <!-- bootstrap daterangepicker -->
  <link href="{{asset('/assets/adminlte/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
  <!-- bootstrap dataTable -->
  <link href="{{asset('/assets/adminlte/plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" />
  <!-- Select2 -->
  <link href="{{asset('/assets/adminlte/plugins/select2/select2.min.css')}}" rel="stylesheet" />
  <link href="{{asset('/assets/adminlte/plugins/iCheck/all.css')}}" rel="stylesheet" />
  <!-- Theme style -->
  <link href="{{ asset("/assets/adminlte/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("/assets/adminlte/css/skins/skin-blue.min.css")}}" rel="stylesheet" type="text/css" />

  @yield('css')

          <!-- custom css -->
  <link href="{{elixir('assets/css/style.min.css')}}" rel="stylesheet" />
  <link href="{{elixir('assets/css/application.min.css')}}" rel="stylesheet" />
</head>
<body class="hold-transition skin-blue sidebar-mini" style="background-color: #ecf0f5">
@include('errors.flash')
@yield('content')
<!-- play voice div -->
<div id="voice"></div>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="{{ asset('/assets/ie-js/html5shiv.js') }}" type="text/javascript"></script>
<script src="{{ asset('/assets/ie-js/respond.min.js') }}" type="text/javascript"></script>
<![endif]-->

<!-- application js  -->
<script src="{{elixir('assets/js/application.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset ('/assets/adminlte/plugins/select2/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{ asset ('/assets/adminlte/plugins/iCheck/icheck.js')}}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ asset ("/assets/adminlte/js/app.min.js") }}" type="text/javascript"></script>

<!-- date-range-picker -->
<script src="{{asset('/assets/adminlte/plugins/daterangepicker/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('/assets/adminlte/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<script src="{{asset('/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js')}}" type="text/javascript"></script>
<!--dataTable -->
<script src="{{asset('/assets/adminlte/plugins/datatables/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<!-- fancybox start -->
<!-- Add mousewheel plugin (this is optional) -->
<script src="{{asset('/assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js')}}"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="{{asset('/assets/fancybox/source/jquery.fancybox.css?v=2.1.5')}}" type="text/css" media="screen" />
<script src="{{asset('/assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5')}}"></script>
<!-- fancybox end -->

<!-- bootstrap layer -->
<script src="{{asset('/assets/layer/layer.js')}}"></script>
<!-- jquery.media.js -->
<script src="{{asset('assets/js/jquery.media.js')}}"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();

    //fancybox view image
    $(".fancybox").fancybox({
      openEffect	: 'none',
      closeEffect	: 'none'
    });

    //support ajax request
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    //Date range picker
    var start_at = $("input[name='start_at']"),
            end_at = $("input[name='end_at']"),
            daterange = $("input[name='date_range']");
    var startDate = start_at.val(),endDate = end_at.val();
    @if(Input::get('date_range'))
    daterange.val("{{Input::get('date_range')}}");
    @else
            startDate = start_at.data('default');
    endDate = end_at.data('default');
    @endif
    daterange.daterangepicker({
      startDate: startDate,
      endDate: endDate,
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
    }).on('cancel.daterangepicker', function(ev, picker) {
      //$(this).val(''); //click cancel button
    }).on('apply.daterangepicker', function(ev, picker) {
      $(this).focus();
      $(this).val(picker.startDate.format(picker.locale.format)+picker.locale.separator+picker.endDate.format(picker.locale.format));
      $(this).blur();
      picker.hide();
    });
    /**单选日期***/
    $('#date_single').datepicker({
      format: 'yyyy-mm-dd',
      language:'ch',
      autoclose:true,
      pickDate: true,
      pickTime: false
    });
    //keyup_btn键盘事件
    $(document).on("keypress", document, function (event) {
      if (event.charCode == 13) {
        $(".keyup_btn").click();
      }
    });
    //search form filter empty value
    $(".search-form").on("submit", function () {
      // disable empty inputs/selector
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

    //Set toastr's option ,link https://github.com/CodeSeven/toastr
    toastr.options.timeOut = 2000; // How long the toast will display without user interaction
    toastr.options.extendedTimeOut = 6000; // How long the toast will display after a user hovers over it
    toastr.options.progressBar = true;
  });

  //play voice tip, use baidu voice service
  function playVoice(text) {
    var zhText = encodeURI(text);
    var newValue="<audio autoplay=\"autoplay\">" + "<source src=\"http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&text="+ zhText +"\" type=\"audio/mpeg\">" +
            "<embed height=\"0\" width=\"0\" src=\"http://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&text="+ zhText +"\">" + "</audio>";
    document.getElementById("voice").innerHTML = newValue;
  }

  //toastr and voice tip
  function toastrVoice(text,type) {
    playVoice(text);
    if(type && type == 1){
      toastr.success(text);
    }else{
      toastr.error(text);
    }
  }
  /**复制**/
  function copyToClipboard(txt) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(txt).select();
    document.execCommand("copy");
    $temp.remove();
  }

  @if(Session::has('flashsession_message'))
  toastrVoice('{{Session::get('flashsession_message')}}');
  @endif

</script>
<!-- 引入的其他脚本 -->
@yield('scripts')
</body>
</html>