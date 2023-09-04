<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['title','user_id','bio'];
    public function user(){

       return $this->belongsTo(User::class);
    }

    public function tasks(){

        return $this->hasMany(Task::class);
    }
}
