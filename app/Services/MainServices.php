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
    public function listHome($date)
    {
        try {
            $temperature = $result = [];
            for($no = 0 ; $no < 23 ; $no ++) {
                $result[] = 0;
            }
            if ($date == '') {
                $now = Carbon::now();
                $nowDate = $now->year.'-'.sprintf('%02d', $now->month).'-'.sprintf('%02d', $now->day);
            } else {
                $now = Carbon::parse($date);
                $nowDate = $date;
            }
            $temp = $this->temperatureRepository->listHome($nowDate.' 00:00:00', $nowDate.' 23:59:59');
            if ($temp->count()) {
                foreach ($temp as $info) {
                    $hour = substr($info['time'], 11, 2);
                    if (!isset($temperature[$hour])) {
                        $temperature[$hour] = ['cnt' => 0, 'temperature' => 0];
                    }
                    $temperature[$hour]['cnt'] += 1;
                    $temperature[$hour]['temperature'] += $info['value'];
                }

                foreach ($temperature as $key => $hour) {
                    $temperature[$key]['sum'] = round($hour['temperature']/$hour['cnt'], 1);
                }

                for ($no = 0 ; $no < 23 ; $no++) {
                    $no = sprintf('%02d', $no);
                    if (isset($temperature[$no])) {
                        $result[$no] = $temperature[$no]['sum'];
                    }
                }

            }

            return [
                'result' => true,
                'list'   => implode(',', $result),
                'get'    => $nowDate,
                'date'   => ['y' => $now->year, 'm' => ($now->month-1), 'd' => $now->day, 'date' => $nowDate, 'now' => substr(Carbon::now()->toDateTimeString(), 0, 10)],
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
    public function listTemperature($start, $end)
    {
        try {
            if ($start == '' || $end == '') {
                $time = Carbon::today();
                $start = $time->toDateTimeString();
                $end = str_replace('00:00:00', '23:59:59', $start);
            }
            return [
                'result' => true,
                'get'    => ['start' => $start, 'end' => $end],
                'list'   => $this->temperatureRepository->listTemperature([
                    'start'    => $start,
                    'end'      => $end,
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
    public function listFan($start, $end, $status)
    {
        try {
            if ($start == '' || $end == '') {
                $time = Carbon::today();
                $start = $time->toDateTimeString();
                $end = str_replace('00:00:00', '23:59:59', $start);
            }
            return [
                'result' => true,
                'get'    => ['start' => $start, 'end' => $end, 'status' => $status],
                'list'   => $this->temperatureStatusRepository->listTemperatureStatus([
                    'start'    => $start,
                    'end'      => $end,
                    'paginate' => config('website.paginate'),
                    'status'   => $status
            ])];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

}