<?php
/**
 * Request methord
 */
const g_POST = 'POST';
const g_GET = 'GET';
const g_PUT = 'PUT';
const g_DELETE = 'DELETE';

/*-------- API response key ---------*/
const g_API_STATUS  = 'status';
const g_API_MSG     = 'msg';
const g_API_CONTENT = 'content';

/*-------- API response export format ---------*/
const g_EXPORT_FORMAT_JSON  = 'json';
const g_EXPORT_FORMAT_XML   = 'xml';

/**-------- API system code ---------**/
//Normal
const g_STATUSCODE_OK = 200;

//未完成
const g_STATUSCODE_UNIFINISHED = 201;

//incorrect parameter
const g_API_ERROR = -1;
//incorrect version
const g_API_VERSIONINVALID = -2;
//system error
const g_API_SYSTEMERROR = -3;
//incorrect signcode
const g_API_SIGNERROR = -4;
//token expired
const g_API_TOKENEXPIRED = 403;
//服务器错误
const g_API_SERVER_ERROR = 1002;
//货位不正确
const g_API_SKULOCATION_ERROR = 1006;
//拣货单没有需要拣货的item了
const g_API_PICKORDER_NOITEM_NEEDPICK = 9001;
//不是待拣的拣货单
const g_API_PICKORDER_NOTMATCH = 9002;
//找不到拣货单
const g_API_PICKORDER_NOTFOUND = 9003;
//finished pick
const g_API_PICKORDER_FINISHED = 9101;

const G_API_PICKORDER_FINISH_ITEM = 9105;

//no this pick order
const g_API_NO_THISPICKORDER = 9102;
//not_need_change_location
const g_API_NOTNEED_CHANGELOCATION = 9201;
//no stock record
const g_API_NOSTOCK_RECORD = 9301;
//没有下一个入库单item
const g_API_NONEXT_ITEM = 9401;
//找不到对应的入库单
const g_API_NOTFOUND_WAREHOUSEWARRANT = 9402;
//找不到SKU
const g_API_NOTFOUND_SKU = 9403;
//已经入过库
const g_API_ALREADY_INHOUSE = 9404;
//此入库单不需入库
const g_API_WAREHOUSEWARRANT_NONEED_PUTIN = 9501;
//找不到入库单item
const g_API_WAREHOUSEWARRANTITEM_NOTFOUND = 9601;
//找不到原来的入库单item
const g_API_NOTFOUND_ORIGINITEM = 9701;
//不能结束入库单
const G_API_NOTFOUND_NO_INWAREHOUSE = 9801;
const G_API_NO_PRIVILIGES = 9901;

//FBP 货位盘盈
const G_API_PROFIT_FBP = 2001;