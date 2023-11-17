<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APICategory extends Model
{
    use HasFactory;

    protected $table = 'api_categories';

    protected $fillable = ['api_id', 'api_category', 'category_id'];
}
