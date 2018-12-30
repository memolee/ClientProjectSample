<?php

namespace App;

use App\Image;
use App\Traits\Excludable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Excludable;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $guarded=[];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function path()
    {
        return '/products/'.$this->id;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
