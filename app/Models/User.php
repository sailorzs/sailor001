<?php

namespace App\Models;

use function GuzzleHttp\Psr7\str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'activition_token', 'activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //生成用户头像
    public function gravatar($size = '100'){
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //监控creating事件，并生成随机的激活token
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::creating(function ($user){
           $user->activation_token = Str::random(10);
        });
    }

    //建立数据模型之间的关联
    public function status(){
        return $this->hasMany(Status::class);
    }

    //获取微博集合
    public function feed(){
        return $this->status()->orderBy('created_at', 'desc');
    }

    //多对多关联-获取粉丝关系列表
    public function followers(){
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    //多对多关联-获取关注的用户列表
    public function followings(){
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    //关注用户-followings()、sync()
    public function follow($user_ids){
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    //取消关注用户-detach()
    public function unfollow($user_ids){
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
            $this->followings()->detach($user_ids);
        }
    }

    public function isFollowing($user_id){
        return $this->followings->contains($user_id);
    }


}
