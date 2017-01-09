<?php
/**
 * Main Services 首頁商業邏輯
 *
 * @author mckey
 * @since 2016-12-20
 */

namespace App\Services;

/**
 * Class MainServices
 * @package App\Services
 */
class MainServices
{
    const ERROR = 'error';

    /**
     * 首頁
     *
     * @return array
     */
    public function listHome()
    {
        try {
            return ['result' => true];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

}