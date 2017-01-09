<?php
/**
 * Users Repository 處理DB語法
 *
 * @author Mckey
 * @since 2016-12-15
 */

namespace App\Repositories;

use App\Entities\Users;

/**
 * Class UsersRepository
 * @package App\Repositories
 */
class UsersRepository
{
    /** @var Users Entities */
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    /**
     * 使用者 - 清單資料
     *
     * @param  array $request
     *
     * @return mixed
     */
    public function listUsers($request)
    {
        $obj = $this->users->where('email', 'LIKE', '%'.$request['email'].'%');
        if (isset($request['active']) && $request['active'] != '') {
            $obj = $obj->where('active', $request['active']);
        }
        return $obj->orderby('email', 'ASC')->paginate($request['paginate']);
    }

    /**
     * 使用者 - 新增
     *
     * @param  array  $request
     *
     * @return static
     */
    public function addUsers($request)
    {
        return $this->users->create($request);
    }

    /**
     * 使用者 - 更新
     *
     * @param  integer  $userID
     *
     * @param  array    $request
     *
     * @return static
     */
    public function updateUsers($userID, $request)
    {
        return $this->users->find($userID)->update($request);
    }

    /**
     * 使用者 - 更新帳號狀態
     *
     * @param  integer  $userID
     *
     * @param  array    $request
     *
     * @return static
     */
    public function updateUsersActive($userID, $active)
    {
        return $this->users->find($userID)->update(['active' => $active]);
    }

    /**
     * 使用者 - 刪除
     *
     * @param  integer  $userID
     *
     * @return static
     */
    public function deleteUsers($userID)
    {
        return $this->users->destroy($userID);
    }

    /**
     * 使用者 - 檢查帳號
     *
     * @param  string  $email  帳號
     *
     * @return object          搜尋資料
     */
    public function checkEmail($email)
    {
        return $this->users->where('email', $email)->skip(0)->take(1)->get();
    }

    /**
     * 使用者 - 取得單使用者資料
     *
     * @param  integer  $userID
     *
     * @return object   搜尋資料
     */
    public function findUser($userID)
    {
        return $this->users->find($userID);
    }
}
