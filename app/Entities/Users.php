<?php
/**
 * 使用者 Model
 *
 * @author Mckey
 * @since 2016-12-19
 */
namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Users
 * @package App\Entities\Users
 */
class Users extends Model
{
    protected $table = 'users';
    protected $fillable = ['username', 'password', 'fullname', 'active', 'role', 'remark'];
    protected $hidden = ['password'];
}
