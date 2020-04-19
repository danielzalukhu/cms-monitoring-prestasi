<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table= 'grades';
    protected $fillable = ['NAME', 'DESCRIPTION', 'GRADE', 'PROGRAMS_ID', 'STAFFS_ID'];

    public function program()
    {
        return $this->belongsTo('App\Program', 'PROGRAMS_ID');
    }

    public function staff()
    {
        return $this->belongsTo('App\Staff', 'STAFFS_ID');
    }

    public function student()
    {
        return $this->hasMany('App\Student');
    }
}
