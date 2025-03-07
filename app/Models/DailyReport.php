<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyReport extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'path',
        'description',
        'name',
        'title',
    ];


    public function ReportData()
    {
        return $this->hasMany("App\Models\ReportDetail", "daily_report_id", "id");
    }

}
