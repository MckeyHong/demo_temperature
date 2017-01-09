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
            'get'    => $info['get'],
            'list'   => $info['list'],
            'showID' => intval(request()->noticesID),
            'cate'   => config('website.noticesCate'),
        ]);
    }
}
