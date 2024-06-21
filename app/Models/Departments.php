<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;

    protected $guarded;

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'comapny_departments', 'department_id', 'company_id');
    }
}