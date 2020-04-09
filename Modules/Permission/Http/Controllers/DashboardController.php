<?php

namespace Modules\Permission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard主页面
     *
     * @return mixed
     */
    public function show()
    {
        // 统计类目总数
        $catesNum = 0;
        // 统计属性总数
        $attrsNum = 0;
        // 统计待审核总数
        $auditProsNum = 0;
        // 统计提现待审核总数
        $auditWithdrawsNum = 0;

        //入库、出库、转换数据统计
        $sta_num = $this->DashboardSta();
        $str=420255209374869903504700415721;
//
//        $user = auth()->user();
//        $permissionService = new \App\Services\PermissionService();
//        $permissionService->setUserPermissions($user);// 刷新当前用户权限
        return view('permission::dashboard.index', compact('catesNum', 'attrsNum', 'auditProsNum','sta_num'));
    }

    /**
     * 修改本地化语言
     *
     * @param Request $request
     * @return mixed
     */
    public function changeLanguage(Request $request)
    {
        session()->put('locale',$request->lang);
        return Redirect::intended('/');
    }

    /**
     * 获取首页统计数据
     * @return array
     */
    public function DashboardSta(){
        return [];
        $customer_id = 0;
        if(Auth::user()->type == 2){
            $customer_id = Auth::user()->customer_id;
        }
        $rs = [];

        $stockin = StockinDirective::selectRaw('sum(total_quantity-received_quantity) as num,type')
            ->whereNotIn('status',[StockinDirective::STATUS_CLOSED,StockinDirective::STATUS_CANCELLED])
            ->groupBy('type');

        if($customer_id){
            $stockin_num = $stockin->where('customer_id',$customer_id)->get();
        }else{
            $stockin_num = $stockin->get();
        }

        foreach($stockin_num as $item){
            $rs[$item->type] = $item->num;
        }

        $stockout = DB::table('sms_stockout_directive as sd')
            ->selectRaw('sum(sd.total_quantity - ooi.out_quantity) as num,type')
            ->leftJoin('sms_outbound_order_item as ooi','ooi.sys_voucher_no','=','sd.sys_voucher_no')
            ->whereNotIn('sd.status',[StockoutDirective::HAVE_OUTBOUND,StockoutDirective::CANCELLED])
            ->groupBy('sd.type');


        /*$stockout = StockoutDirective::selectRaw('sum(sms_stockout_directive.total_quantity-sms_outbound_order_item.out_quantity) as num,type')
            ->leftjoin('sms_outbound_order_item','sms_outbound_order_item.sys_voucher_no','=','sms_stockout_directive.sys_voucher_no')
            ->whereNotIn('sms_stockout_directive.status',[StockoutDirective::HAVE_OUTBOUND,StockoutDirective::CANCELLED])
            ->groupBy('sms_stockout_directive.type');*/

        if($customer_id){
            $stockout_num = $stockout->where('sd.customer_id',$customer_id)->get();
        }else{
            $stockout_num = $stockout->get();
        }

        foreach($stockout_num as $item){
            $rs[$item->type] = $item->num;
        }

        $transform = SkuTransformDirective::selectRaw('sum(total_quantity-transform_quantity) as num,type')
            ->whereNotIn('status',[SkuTransformDirective::CLOSED,SkuTransformDirective::CANCELLED])
            ->groupBy('type');

        if($customer_id){
            $transform_num = $transform->where('customer_id',$customer_id)->get();
        }else{
            $transform_num = $transform->get();
        }

        foreach($transform_num as $item){
            $rs[$item->type] = $item->num;
        }

        return $rs;
    }

}
