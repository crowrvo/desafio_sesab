<?php

namespace App\Models;

use Database\Factories\RolesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return RolesFactory::new ();
    }

    function Users()
    {
        return $this->hasMany(Users::class);
    }
}