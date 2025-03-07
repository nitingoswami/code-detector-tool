<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportDetail extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'team',
        'type', 
        'summary', 
        'date',
        'old_code', 
        'new_code',
        'daily_report_id',
        'file_path'
    ];

    public function CommentList()
    {
        return $this->hasMany("App\Models\Comment", "user_id", "id");
    }

    
}
