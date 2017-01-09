<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\UsersServices;
use Validator;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{
    /** @var UsersServices */
    private $usersServices;
    /** @var 自訂錯誤訊息 */
    private $errorMessage;

    /**
     * UsersController constructor.
     * @param UsersServices $usersServices
     */
    public function __construct(UsersServices $usersServices)
    {
        $this->usersServices = $usersServices;
        $this->errorMessage = [
            'userID.exists'   => 'User does not exist',
            'username.unique' => 'username is exist',
        ];
    }

    /**
     * 使用者 - 清單資料
     *
     * @param Request  $request
     *
     * @return View
     */
    public function index()
    {
        $info = $this->usersServices->listUsers(request()->input('username', ''), request()->input('active', ''), request()->input('role', ''));
        return view('users', [
            'get'    => $info['get'],
            'list'   => $info['list'],
            'cate'   => config('website.noticesCate'),
            'active' => config('website.usersActive'),
            'role'   => config('website.usersRole'),
        ]);
    }

    /**
     * 使用者 - 新增
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // 驗證參數
        $validator = Validator::make(request()->all(), [
            'username' => 'required|alpha_num|between:4,8|unique:users,username',
            'password' => 'required|confirmed|between:6,12',
            'fullname' => 'required',
            'active'   => 'required|alpha_num:'.implode(',', array_keys(config('website.usersActive'))),
            'role'     => 'required|alpha_num:'.implode(',', array_keys(config('website.usersRole'))),
        ], $this->errorMessage);
        if ($validator->fails()) {
            $result = ['result' => false, 'code' => config('errorCode.validateFail'), 'msg' => $validator->errors()->first()];
        } else {
            $result = $this->usersServices->addUsers(request()->all());
        }
        return $this->responseWithJson($result);
    }

    /**
     * 使用者 - 更新
     *
     * @param integer  $userID
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($userID)
    {
        // 驗證參數
        $request  = request()->all();
        $request['userID'] = $userID;
        $validator = Validator::make($request, [
            'password' => 'confirmed|between:6,12',
            'fullname' => 'required',
            'active'   => 'required|alpha_num:'.implode(',', array_keys(config('website.usersActive'))),
            'role'     => 'required|alpha_num:'.implode(',', array_keys(config('website.usersRole'))),
            'userID'   => 'required|exists:users,id',
        ], $this->errorMessage);
        if ($validator->fails()) {
            $result = ['result' => false, 'code' => config('errorCode.validateFail'), 'msg' => $validator->errors()->first()];
        } else {
            $result = $this->usersServices->updateUsers($userID, $request);
        }
        return $this->responseWithJson($result);
    }

    /**
     * 使用者 - 刪除
     *
     * @param integer  $userID
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy($userID)
    {
        // 驗證參數
        $validator = Validator::make(['userID' => $userID], [
            'userID' => 'required|exists:users,id'
        ], $this->errorMessage);

        if ($validator->fails()) {
            $result = ['result' => false, 'code' => config('errorCode.validateFail'), 'msg' => $validator->errors()->first()];
        } else {
           $result = $this->usersServices->deleteUsers($userID);
        }
        return $this->responseWithJson($result);
    }

    /**
     * 使用者 - 更改帳號狀態
     *
     * @param integer  $userID
     * @param integer  $active
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function active($userID, $active)
    {
        // 驗證參數
        $validator = Validator::make(['userID' => $userID], [
            'userID' => 'required|exists:users,id'
        ], $this->errorMessage);

        if ($validator->fails()) {
            $result = ['result' => false, 'code' => config('errorCode.validateFail'), 'msg' => $validator->errors()->first()];
        } else {
           $result = $this->usersServices->activeUsers($userID, $active);
        }
        return $this->responseWithJson($result);
    }

    /**
     * 使用者 - 登入介面
     *
     * @param Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getLogin()
    {
        if (session()->has('user_id')) {
            return redirect()->guest('/');
        }
        return view('login');
    }

    /**
     * 使用者 - 登入
     *
     * @param Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postLogin()
    {
        // 驗證參數
        $validator = Validator::make(request()->all(), [
            'email'    => 'required|email',
            'password' => 'required|between:6,12',
        ], $this->errorMessage);

        if ($validator->fails()) {
            $result = ['result' => false, 'code' => config('errorCode.validateFail'), 'msg' => $validator->errors()->first()];
        } else {
            $result = $this->usersServices->login(request()->all());
        }
        return $this->responseWithJson($result);
    }

    /**
     * 使用者 - 登出
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $this->usersServices->logout();
        return  redirect('/login');
    }

    /**
     * 使用者 - 修改個人密碼
     *
     * @param Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function changePassword()
    {
        // 驗證參數
        $validator = Validator::make(request()->all(), [
            'old_password' => 'required|between:6,12',
            'password'     => 'required|confirmed|between:6,12',
        ], $this->errorMessage);
        if ($validator->fails()) {
            $result = ['result' => false, 'code' => config('errorCode.validateFail'), 'msg' => $validator->errors()->first()];
        } else {
            $result = $this->usersServices->changePassword(request()->all());
        }
        return $this->responseWithJson($result);
    }
}
