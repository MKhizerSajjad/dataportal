<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technologies extends Model
{
    use HasFactory;

    // protected $table = 'technologies';
    protected $guarded;


    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_technologies', 'technology_id', 'company_id');
    }
}
