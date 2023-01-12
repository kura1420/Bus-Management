<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static $rules = [
        'name' => 'required|string|max:40',
        'email' => 'required|string|max:40|email|unique:admins,email',
        'username' => 'required|string|max:40|alpha_num|unique:admins,username',
        'image' => 'nullable|image',
        'password' => 'string|min:6',
    ];

    public function getActiveTextAttribute()
    {
        return $this->active ? 'Active' : 'No Active';
    }

}
