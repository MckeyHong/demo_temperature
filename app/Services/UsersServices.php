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
    /** @var SessionsRepository Sessions Repository */
    private $sessionsRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    /**
     * 使用者 - 清單資料
     *
     * @param string   $fullname
     * @param integer  $active
     * @param integer  $role
     *
     * @return array
     */
    public function listUsers($username, $active, $role)
    {
        try {
            return [
                'result' => true,
                'get'    => ['username' => $username, 'active' => $active, 'role' => $role],
                'list'   => $this->usersRepository->listUsers([
                'username' => $username,
                'active'   => $active,
                'role'     => $role,
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
                'fullname' => $request['fullname'],
                'active'   => $request['active'],
                'role'     => $request['role'],
            ];
            if (isset($request['password']) && $request['password'] != '') {
                $update['password'] = Hash::make($request['password']);
            }
            if ($request['active'] == '2') {
                $this->sessionsRepository->deleteSessions($userID);
            }
            return ['result' => $this->usersRepository->updateUsers($userID, $update)];
        } catch (\Exception $e) {
            // 其他錯誤
            return ['result' => self::ERROR, 'code' => ($e->getCode() ? $e->getCode() : config('errorCode.otherError')), 'msg' => $e->getMessage()];
        }
    }

    /**
     * 使用者 - 更新帳號狀態
     *
     * @param integer $userID
     * @param integer $active
     *
     * @return array
     */
    public function activeUsers($userID, $active)
    {
        try {
            if ($active == '2') {
                $this->sessionsRepository->deleteSessions($userID);
            }
            return ['result' => $this->usersRepository->updateUsersActive($userID, $active)];
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
            $this->sessionsRepository->deleteSessions($userID);
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
                throw new \Exception('email does not exist');
            }
            $user = $user[0];
            if (!Hash::check($request['password'], $user->password)) {
                throw new \Exception('password is error');
            }
            if ($user->active != 1) {
                throw new \Exception('email was disabled');
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