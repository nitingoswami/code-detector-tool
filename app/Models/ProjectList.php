<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProjectList extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
       'project_name'
    ];


    public function dailyReports()
    {
        return $this->hasMany('App\Models\DailyReport', 'project_id', 'id');
    }
}
