<?php

namespace Modules\Subsystem\Services;

use Modules\Api\Assistants\ApiResponse;
use DB;
use Log;
use App\Models\Permission\AuthGroup;

class SubsystemService
{

    public function index($param)
    {
        try {
            $limit = 20;

            $user = auth()->user();
            $customer_id = isset($param['customer_id']) ? $param['customer_id'] : $user->customer_id;
            $res = AuthGroup::selectRaw('*')->with('customer')->with('authUserGroup.user');
            !empty($param['cn_name']) && $res->where('cn_name', $param['cn_name']);
            $res->where('customer_id', $customer_id)->whereNotIn('name', AuthGroup::$allDisableNames);
            $datas = $res->orderBy('id', 'desc')->paginate($limit);
            return $datas;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            Log::error(class_basename(__CLASS__) . ' ' . $msg);
            return [];
        }
    }

    public function store($param)
    {
        try {
            unset($param['_token']);
            $model = new AuthGroup();
            // 校验记录的唯一性
            $is_exist = $model::onWriteConnection()
                ->where(function ($q) use ($param) {
                    $q->where('name', $param['name'])->orWhere('cn_name', $param['cn_name']);
                })
                ->value('id');
            if ($is_exist) throw new \Exception('中文名或英文名记录已存在，请检查');
            foreach ($param as $k => $v) {
                $model->$k = $v;
            }
            $model->save();
            return ApiResponse::success();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            Log::error(class_basename(__CLASS__) . ' ' . $msg);
            return ApiResponse::failure(g_API_ERROR, $msg);
        }
    }

    /**
     * 更新状态或编辑
     * @param $param
     * @param $id
     * @return mixed
     */
    public function update($param, $id)
    {
        try {
            $model = AuthGroup::find($id);
            if (!$model) throw new \Exception('查无相关的记录');
            unset($param['type']);
            unset($param['_token']);
            if (!isset($param['status'])) {
                // 校验 角色的中文名、中文名，均校验唯一性
                $is_exist = $model::onWriteConnection()->where('customer_id', $model->customer_id)->where('id', '<>', $id);
                $is_exist->where(function ($q) use ($param) {
                    $q->where('name', $param['name'])->orWhere('cn_name', $param['cn_name']);
                });
                $is_exist = $is_exist->value('id');
                if ($is_exist) throw new \Exception('中文名或英文名记录已存在，请检查');
            }
            foreach ($param as $k => $v) {
                $model->$k = $v;
            }
            $model->save();
            return ApiResponse::success();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            Log::error(class_basename(__CLASS__) . ' ' . $msg);
            return ApiResponse::failure(g_API_ERROR, $msg);
        }
    }

}

