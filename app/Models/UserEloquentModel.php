<?php
namespace App\Models;

use App\EloquentModel;

class UserEloquentModel extends EloquentModel
{
    protected $table = 'users';

    public static function allUsers()
    {
        return self::all();
    }

    public function image()
    {
        return $this->hasMany('App\Models\UserImagesEloquentModel', 'user_id');
    }
}
