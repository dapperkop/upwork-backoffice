<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
