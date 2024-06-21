<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $guarded;


    public function technologies()
    {
        return $this->belongsToMany(Technologies::class, 'company_technologies', 'company_id', 'technology_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keywords::class, 'company_keywords', 'company_id', 'keyword_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Departments::class, 'comapny_departments', 'company_id', 'department_id');
    }
}
