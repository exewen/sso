<?php
/**
 * TomeZone in PatPat
 *
 * Class PPTimeZone
 */
class PPTimeZone
{
    const UTC = "UTC";
    const Los_Angeles = "America/Los_Angeles";
}

/**
 * 订单状态
 *
 * Class OrderStatus
 */
class OrderStatus
{
    const NOT_PAY = "notpay";   //没有付款
    const PLACED = "placed"; //已经付款还没扣钱
    const SETTLED = "settled"; //已经扣钱
    const SETTLEfAILURE = "settlefailure"; //扣钱失败
    const PRESHIP = "pre_ship"; //等待发货,发货单创建时的默认状态
    const ASSORTING = "assorting"; //正在配货,这个状态涵盖了仓库那边拣货,分拣,质检
    const PARTLY_SHIPPED = "partlyshipped"; //部分发货,配货完成,打包交给快递,在路上运输
    const SHIPPED = "shipped";  //全部发货, 配货完成,打包交给快递,在路上运输
    const DELIVERED = "delivered"; //已经收货
    const CANCELLED = "cancelled";  //取消
    const RETURNING = "returning"; //正在退货
    const RETURNED = "returned";  //
}

