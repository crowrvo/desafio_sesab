<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $with = ['Roles:name,id', 'Addresses'];
    protected $fillable = ['name', 'cpf', 'email', 'role_id'];
    
    protected static function newFactory()
    {
        return UserFactory::new ();
    }
    function Roles()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }

    function Addresses()
    {
        return $this->belongsToMany(
            Addresses::class,
            'users_addresses',
            'user_id',
            'address_id'
        );
    }
}