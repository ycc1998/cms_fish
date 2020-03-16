<?php

namespace App\Http\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';
    protected $dateFormat = 'U';
    //设置表名
    protected $table = 'user';
    protected $_admin_roles = null;

    /**
     * 不可以被赋值的属性。
     * @var array
     */
    protected $guarded = [
    ];
    /**
     * 可以被赋值的属性。
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];

    /**
     * The attributes that should be hidden for arrays.
     *数组隐藏的属性
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    /**本地作用域
     * @param $query
     * @return mixed
     * 只查询reg_account_type等于0的数据
     */
    public function scopeType($query)
    {
        return $query->where('reg_account_type', 0);
    }

    // 获得管理员权限列表，不含公共权限
    public function getAdminPermissions() {
        if($this->type != 'admin') {
            throw new \Exception(sprintf("%s用户不是管理员", $this->username));
        }

        $admin_roles = json_decode($this->admin_roles);

        if (is_array($admin_roles) and !empty($admin_roles)) {
            if (is_null($this->_admin_roles)) {
                $this->_admin_roles = AdminRole::where('enabled', '1')->whereIn('id',$admin_roles)->get();
            }
            $adminRoles = $this->_admin_roles;
            $permissions = [];
            foreach($adminRoles as $v) {
                $permission = is_array(json_decode($v['permissions'])) ? json_decode($v['permissions']) : [];
                $permissions = array_merge($permissions, $permission);
            }
            return $permissions;

        }else {
            return [];
        }
    }
}
