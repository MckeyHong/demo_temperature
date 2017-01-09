<?php

namespace App\Repositories;

use App\Entities\TemperatureStatus;

class TemperatureStatusRepository
{
    private $temperatureStatus;

    public function __construct(TemperatureStatus $temperatureStatus)
    {
        $this->temperatureStatus = $temperatureStatus;
    }

    /**
     * 風扇 - 清單資料
     *
     * @param  array $request
     *
     * @return mixed
     */
    public function listTemperatureStatus($request)
    {
        $obj = $this->temperatureStatus->whereBetween('time', [$request['start'], $request['end']]);
        if(isset($request['status']) && $request['status'] != 'all') {
            $obj = $obj->where('status', $request['status']);
        }
        return $obj->orderby('time', 'DESC')->paginate($request['paginate']);
    }

    /**
     * 風扇 - 新增
     *
     * @param  array  $request
     *
     * @return static
     */
    public function addTemperatureStatus($request)
    {
        return $this->temperatureStatus->create($request);
    }

    /**
     * 風扇 - 更新
     *
     * @param  integer  $ID
     *
     * @param  array    $request
     *
     * @return static
     */
    public function updateTemperatureStatus($ID, $request)
    {
        return $this->temperatureStatus->find($ID)->update($request);
    }

    /**
     * 風扇 - 刪除
     *
     * @param  integer  $ID
     *
     * @return static
     */
    public function deleteTemperatureStatus($ID)
    {
        return $this->temperatureStatus->destroy($ID);
    }
}
