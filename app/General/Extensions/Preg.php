<?php
/**
 * Created by sms.
 * User: Bruce.He
 * Date: 15/11/17
 * Time: 上午1:48
 */

/**
 * 货位编码的正则
 *
 * @param $test
 * @return int
 */
function preg_SkuLocation($location)
{
    $rule  = "/^[A-Za-z]-([0-9]*)-([0-9]*)-([0-9]*)-([0-9]*)?$/";
    return preg_match($rule,$location,$result);
}


/**
 * U编码正则
 */
function preg_skuUcode($sku_barcode)
{
    $rule  = "/^[Uu]-([A-Za-z0-9]*)-([0-9]*)-?([0-9]*)?$/";
   return preg_match($rule,$sku_barcode,$result);
}


