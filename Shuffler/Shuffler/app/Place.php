<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    // Table Name
    protected $table = 'places';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}
