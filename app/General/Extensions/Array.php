<?php
/**
 * Created by vms.
 * User: Bruce.He
 * Date: 15/11/24
 * Time: 下午10:05
 */

/**
 * 将stdClass Object转array，如果要转的数据量比较大采用array_object_l方法
 *
 * @param $array
 * @return array
 */
function array_object($array){
    if(is_object($array)){
        $array = (array)$array;
    }
    if(is_array($array)){
        foreach($array as $key=>$value){
            $array[$key] = array_object($value);
        }
    }
    return $array;
}

/**
 * 将stdClass Object转array， 对json的特性，只能是针对utf8的，否则得先转码下
 *
 * @param $array
 * @return mixed
 */
function array_object_l($array){
    $array = json_decode(json_encode($array),TRUE);
    return $array;
}

function array_checkreplace($arr){
    if(!isset($arr)){
        return [];
    }
    return $arr;
}
