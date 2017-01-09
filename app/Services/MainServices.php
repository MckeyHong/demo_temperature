<?php

namespace App\Services;

use App\Repositories\TemperatureRepository;
use App\Repositories\TemperatureStatusRepository;
use Carbon\Carbon;

class MainServices
{
    const ERROR = 'error';

    private $temperatureRepository;
    private $temperatureStatusRepository;

    public function __construct(TemperatureRepository $temperatureRepository, TemperatureStatusRepository $temperatureStatusRepository)
    {
        $this->temperatureRepository = $temperatureRepository;
        $this->temperatureStatusRepository = $temperatureStatusRepository;
    }
    /**
     * 首頁
     *
     * @return array
     */
    public function listHome()
    {
        try {
            $temperature = [];
            $time = Carbon::yesterday();
            $startTime = $time->toDateTimeString();
            $endTime = str_replace('00:00:00', '23:59:59', $startTime);
            $temp = $this->temperatureRepository->listHome($startTime, $endTime);
            if ($temp->count()) {
                foreach ($temp as $info) {
                    $temperature[] = $info['value'];
                }
            }
            return [
                'result' => true,
                'list'   => implode(',', $temperature),
                'date'   => ['y' => $time->year, 'm' => ($time->month-1), 'd' => $time->day, 'date' => $time->year.'-'.$time->month.'-'.$time->day],
            ];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 室內溫度 - 清單資料
     *
     * @return mixed
     */
    public function listTemperature()
    {
        try {
            return [
                'result' => true,
                'list'   => $this->temperatureRepository->listTemperature([
                'paginate' => config('website.paginate'),
            ])];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 風扇 - 清單資料
     *
     * @return mixed
     */
    public function listFan()
    {
        try {
            return [
                'result' => true,
                'list'   => $this->temperatureStatusRepository->listTemperatureStatus([
                'paginate' => config('website.paginate'),
            ])];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

}