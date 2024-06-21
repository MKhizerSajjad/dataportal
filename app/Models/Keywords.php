<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    use HasFactory;

    protected $guarded;
    protected $table = 'keywords';

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_keywords', 'keyword_id', 'company_id');
    }
}
