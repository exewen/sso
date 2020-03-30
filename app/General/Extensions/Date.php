<?php
/**
 * Created by sms.
 * User: Bruce.He
 * Date: 15/11/17
 * Time: 上午1:48
 */

/**
 * 转换UTC时间为当前用户设置的时区时间
 *
 * @param $timestamp
 * @return string
 */
function convertDate($timestamp,$is_string=false)
{
    if(!(int)$timestamp)return ''; //判断返回是否是有效的日期，剔除0000-00-00 00:00:00这样的日期
    $date = \Carbon\Carbon::createFromTimestamp($timestamp,\App\Services\Account\TimeZone::current());
    if($is_string){
        return $date->toDayDateTimeString();
    };
    return $date;
}

/**
 * 将string类型的时间转成时间戳,比如2015-01-19 10:10:10转成时间戳
 * 
 * @param $date_string
 * @return int
 */
function dateStringToTimestamp($date_string)
{
   return \Carbon\Carbon::parse($date_string)->getTimestamp();
}

function timeAgoWithTimestamp($sp){
    if(!isset($sp) && is_int($sp))return"";
    $deltaSeconds = time() - $sp;
    $deltaMinutes = $deltaSeconds/60.0;
    if ($deltaSeconds<5) {
        return "Just now";
    }else if($deltaSeconds < 60){
        return  printf("on %.0f seconds ago",ceil($deltaSeconds));
    }else if($deltaSeconds < 120){
        return "on A minute ago";
    }else if($deltaMinutes < 60){
        return  printf("on %.0f minutes ago",ceil($deltaMinutes));
    }else if($deltaMinutes < 120){
        return "on An hour ago";
    }else if($deltaMinutes < (24 * 60)){
        $minutes = (int)floor($deltaMinutes/60);
        return  printf("on %.0f hours ago",ceil($minutes));
    }else if($deltaMinutes < (24 * 60 * 2)){
        return "Yesterday";
    }else {
        return date('Y-m-d', $sp);
    }
}

function leftTime($minutes){
    if(!isset($minutes) || !$minutes)return '';
    $minutes = intval($minutes);
    $onehour = 60; //1h=60m
    $oneday = 1440; //1d=1440m
    if($minutes < $onehour){
        if($minutes>1){
            return $minutes.' '.'mins';
        }else{
            return $minutes.' '.'min';
        }
    }else if($minutes<$oneday){
        $hours = round_up($minutes/$onehour,0) ;
        if($hours>1){
            return $hours.' '.'hrs';
        }else{
            return $hours.' '.'hr';
        }
    }else{
        $days = round_up($minutes/$oneday,0) ;
        if($days>1){
            return $days.' '.'days';
        }else{
            return $days.' '.'day';
        }
    }
}

function leftTimeDetail($seconds_left){
    $timestamp = $seconds_left;
    $times = $timestamp;
    // 一天等于24*60*60秒
    $days = intval($times/(24*60*60));
    $times = $times%(24*60*60);
    // 一小时等于60*60秒
    $hours = intval($times/(60*60));
    $times = $times%(60*60);
    $minutes = intval($times/60);
    $seconds = intval($times%60);
    $date = (object)['days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$seconds];
    return $date;
}

function leftMinutes($end_at)
{
    if(strlen($end_at)<1)return 0;
    $now = \Carbon\Carbon::now()->timestamp;
    $end_at = strtotime($end_at);
    $minutes_left = round($end_at - $now)/60;
    return $minutes_left;
}
