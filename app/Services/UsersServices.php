<?php
/**
 * Users Services 使用者商業邏輯
 *
 * @author mckey
 * @since 2016-12-20
 */

namespace App\Services;

use App\Repositories\UsersRepository;
use Hash;

/**
 * Class UsersServices
 * @package App\Services
 */
class UsersServices
{

    const ERROR = 'error';

    /** @var UsersRepository 使用者Repository */
    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * 使用者 - 清單資料
     *
     * @param string   $email
     * @param integer  $active
     *
     * @return array
     */
    public function listUsers($email, $active)
    {
        try {
            return [
                'result' => true,
                'get'    => ['email' => $email, 'active' => $active],
                'list'   => $this->usersRepository->listUsers([
                'email'    => $email,
                'active'   => $active,
                'paginate' => config('website.paginate'),
            ])];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }


    /**
     * 使用者 - 新增
     *
     * @param  array $request
     *
     * @return array
     */
    public function addUsers(array $request = [])
    {
        try {
            $request['password'] = Hash::make($request['password']);
            return ['result' => $this->usersRepository->addUsers($request)];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 使用者 - 更新
     *
     * @param  integer  $userID
     * @param  array    $request
     *
     * @return array
     */
    public function updateUsers($userID, array $request = [])
    {
        try {
            $update = [
                'name'   => $request['name'],
                'active' => $request['active'],
            ];
            if (isset($request['password']) && $request['password'] != '') {
                $update['password'] = Hash::make($request['password']);
            }
            return ['result' => $this->usersRepository->updateUsers($userID, $update)];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 使用者 - 刪除
     *
     * @param integer $userID
     *
     * @return array
     */
    public function deleteUsers($userID)
    {
        try {
            return ['result' => $this->usersRepository->deleteUsers($userID)];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 使用者 - 登入
     *
     * @param  object  $request
     *
     * @return array
     */
    public function login($request)
    {
        try {
            $user = $this->usersRepository->checkEmail($request['email']);
            if ($user->count() == 0) {
                throw new \Exception('找不到此帳號');
            }
            $user = $user[0];
            if (!Hash::check($request['password'], $user->password)) {
                throw new \Exception('密碼錯誤');
            }
            if ($user->active != 1) {
                throw new \Exception('此帳號停用中');
            }
            session()->put([
                'user_id'    => $user->id,
                'user_email' => $user->email,
                'user_name'  => $user->name
            ]);
            return ['result' => true];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 使用者 - 登出
     *
     * @return array
     */
    public function logout()
    {
        try {
            session()->forget(['user_id', 'user_email', 'user_name']);
            return ['result' => true];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 使用者 - 修改個人密碼
     *
     * @param  object  $request
     *
     * @return array
     */
    public function changePassword($request)
    {
        try {
            $userID = session()->get('user_id', 0);
            $user = $this->usersRepository->findUser($userID);
            if ($user == null) {
                throw new \Exception('user does not exist');
            }
            if (!Hash::check($request['old_password'], $user->password)) {
                throw new \Exception('password is error');
            }
            $this->usersRepository->updateUsers($userID, ['password' => Hash::make($request['password'])]);
            return ['result' => true];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }
}