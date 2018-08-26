<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    // Table Name
    protected $table = 'histories';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}
