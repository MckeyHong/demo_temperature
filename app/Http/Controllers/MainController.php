<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\MainServices;
use Validator;

/**
 * Class MainController
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /** @var MainServices */
    private $mainServices;
    /** @var 自訂錯誤訊息 */
    private $errorMessage;

    /**
     * MainController constructor.
     * @param MainServices $noticesServices
     */
    public function __construct(MainServices $mainServices)
    {
        $this->mainServices = $mainServices;
        $this->errorMessage = [
            'noticesID.exists' => 'Notices does not exist',
        ];
    }

    /**
     * 首頁
     *
     * @param Request  $request
     *
     * @return View
     */
    public function index()
    {
        $info = $this->mainServices->listHome();
        return view('index', [
            'title' => ''.config('adminlte.title'),
            'list'  => $info['list'],
            'date'  => $info['date'],
        ]);
    }


    public function temperature()
    {
        return view('temperature', [
            'title' => '室內溫度紀錄 - '.config('adminlte.title'),
            'list'  => $this->mainServices->listTemperature()['list'],
        ]);
    }

    public function fan()
    {
        return view('fan', [
            'title' => '風扇控制紀錄 - '.config('adminlte.title'),
            'list'  => $this->mainServices->listFan()['list'],
        ]);
    }
}
