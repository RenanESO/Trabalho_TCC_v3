<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Face extends Model
{
    use HasFactory;

    protected $fillable = ['id_person', 'url_face'];

    public function People()
    {
        return $this->belongsTo(Person::class);
    }
}
