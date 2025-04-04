<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['name'];  // Fillable attributes (allow mass assignment)

    public function expenses()
    {
        return $this->hasMany(Expense::class);  // Relationship with the Expense model
    }
}

