<?php
namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;

class AuthUserGroup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "sso_auth_user_group";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function authGroup()
    {
        return $this->belongsTo(AuthGroup::class, 'group_id', 'id');
    }

}
