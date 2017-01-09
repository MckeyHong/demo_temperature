<?php

namespace App\Repositories;

use App\Entities\Temperature;

class TemperatureRepository
{
    private $temperature;

    public function __construct(Temperature $temperature)
    {
        $this->temperature = $temperature;
    }


    public function listHome($startTime, $endTime)
    {
        return $this->temperature->whereBetween('time', [$startTime, $endTime])->orderBy('time', 'ASC')->get();
    }
    /**
     * 室內溫度 - 清單資料
     *
     * @param  array $request
     *
     * @return mixed
     */
    public function listTemperature($request)
    {
        return $this->temperature->whereBetween('time', [$request['start'], $request['end']])
                                 ->orderby('time', 'DESC')->paginate($request['paginate']);
    }

    /**
     * 室內溫度 - 新增
     *
     * @param  array  $request
     *
     * @return static
     */
    public function addTemperature($request)
    {
        return $this->temperature->create($request);
    }

    /**
     * 室內溫度 - 更新
     *
     * @param  integer  $ID
     *
     * @param  array    $request
     *
     * @return static
     */
    public function updateTemperature($ID, $request)
    {
        return $this->temperature->find($ID)->update($request);
    }

    /**
     * 室內溫度 - 刪除
     *
     * @param  integer  $ID
     *
     * @return static
     */
    public function deleteTemperature($ID)
    {
        return $this->temperature->destroy($ID);
    }

}
