<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const ERROR = 'error';

    /**
     * 統整所有api 回傳給前端的 json格式
     * @see CONTRIBUTING.md
     *
     * @param array $result
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @author kaihan
     */
    protected function responseWithJson(array $result)
    {
        $data = $result['result'] ?? self::ERROR;
        $errorCode = $result['code'] ?? config('errorCode.otherError');
        $message = $result['msg'] ?? '';

        if ($data !== self::ERROR) {
            $statusCode = 200;
            $responseData = ['result' => $data];
        } else {
            //$result['result'] is 'error'

            $statusCode = substr($errorCode, 0, 3);
            $responseData = ['error' => ['code' => $errorCode, 'message' => $message]];
            $this->logData($responseData, $message);
        }
        return response()->json($responseData, $statusCode);
    }

    /**
     * @param array  $responseData
     * @param string $message
     *
     * @author kaihan
     */
    protected function logData(array $responseData, string $message)
    {
        Log::debug("========ERROR Start========");
        Log::debug(json_encode($responseData));
        Log::error($message);
        Log::debug("========ERROR END==========");
    }
}
