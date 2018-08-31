<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    // Table Name
    protected $table = 'searches';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}
