<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achivement extends Model
{
    protected $fillable = [
        'runner_id','distance','badge_id','badge_name'
    ];
}
