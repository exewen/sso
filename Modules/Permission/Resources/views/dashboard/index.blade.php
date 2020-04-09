@extends('common.template.default')

@section('title')
     {{trans('common.system_name')}}
@endsection

@section('css')
    <style type="text/css">
        .main-sta h5{font-size: 18px;}
        .main-sta p{height: 25px;margin-top:5px;}
    </style>
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-4">
        <h2>控制面板</h2>
        <ol class="breadcrumb">
            <li>
                <a href="/">Home</a>
            </li>
            <li class="active">
                <strong>控制面板</strong>
            </li>
        </ol>
    </div>
</div>
{{--@inject('productPresenter','App\Modules\Audit\Presenters\ProductPresenter')--}}
<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="row">
        <div class="row main-sta">
            @if(Auth::user()->type !== 3)
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <h5>入库</h5>
                        <div style="margin-left:13%;height:220px;">
                            <p>采购入库-待入库数量：
                                @if(isset($sta_num['stockin_purchase']))
                                    {{$sta_num['stockin_purchase']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>RMA退货入库-待入库数量：
                                @if(isset($sta_num['stockin_rma_return']))
                                    {{$sta_num['stockin_rma_return']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>FBA Overstock退货-待入库数量：
                                @if(isset($sta_num['stockin_fba_overstock']))
                                    {{$sta_num['stockin_fba_overstock']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>FBA Defective退货-待入库数量：
                                @if(isset($sta_num['stockin_fba_defective']))
                                    {{$sta_num['stockin_fba_defective']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>调度入库--待入库数量：
                                @if(isset($sta_num['stockin_schedule']))
                                    {{$sta_num['stockin_schedule']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>其他入库--待入库数量：
                                @if(isset($sta_num['stockin_other']))
                                    {{$sta_num['stockin_other']}}
                                @else
                                    0
                                @endif
                            </p>
                        </div>
                        <div class="stat-percent font-bold text-navy"><a href="/directive/stockin">详情</a></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <h5>出库</h5>
                        <div style="margin-left:13%;height:220px;">
                            <p>B2C出库-待出库数量：
                                @if(isset($sta_num['stockout_b2c']))
                                    {{$sta_num['stockout_b2c']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>销售出库-待出库数量：
                                @if(isset($sta_num['stockout_sale']))
                                    {{$sta_num['stockout_sale']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>FBA出库-待出库数量：
                                @if(isset($sta_num['stockout_fba']))
                                    {{$sta_num['stockout_fba']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>调度出库-待出库数量：
                                @if(isset($sta_num['stockout_schedule']))
                                    {{$sta_num['stockout_schedule']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>销毁出库-待出库数量：
                                @if(isset($sta_num['stockout_destroy']))
                                    {{$sta_num['stockout_destroy']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>其他出库-待出库数量：
                                @if(isset($sta_num['stockout_other']))
                                    {{$sta_num['stockout_other']}}
                                @else
                                    0
                                @endif
                            </p>
                        </div>
                        <div class="stat-percent font-bold text-navy"><a href="/directive/stockout">详情</a></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-content">
                        <h5>SKU转换</h5>
                        <div style="margin-left:13%;height:220px;">
                            <p>待加工组数：
                                @if(isset($sta_num['sku_process']))
                                    {{$sta_num['sku_process']}}
                                @else
                                    0
                                @endif
                            </p>
                            <p>待拆分组数：
                                @if(isset($sta_num['sku_split']))
                                    {{$sta_num['sku_split']}}
                                @else
                                    0
                                @endif
                            </p>
                        </div>
                        <div class="stat-percent font-bold text-navy"><a href="/directive/sku_transform">详情</a></div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('scripts')
        <!-- Morris.js charts -->
    <script src="{{ asset ("/assets/adminlte/plugins/morris/raphael-min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/assets/adminlte/plugins/morris/morris.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/assets/adminlte/js/demo.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/assets/adminlte/plugins/chartjs/Chart.min.js") }}" type="text/javascript"></script>
    <script>
        $(function () {
           //
        });
    </script>
@endsection

