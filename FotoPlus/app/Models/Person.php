<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Person extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function searchPeopleByName($queryPersonFilter)
    {
        $user = Auth::id();
        $peopleList = Person::selectRaw('people.id, people.name, people.user_id, MIN(faces.url_face) as url_face')
            ->leftJoin('faces', 'faces.id_person', '=', 'faces.id')
            ->where('people.user_id', $user)
            ->where('people.name', 'like', '%' . $queryPersonFilter . '%')
            ->groupBy('people.id', 'people.name', 'people.user_id')
            ->orderBy('people.name')
            ->paginate(10);
            return $peopleList;
    }

    public function faces()
    {
        return $this->hasMany(Face::class, 'id_person', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
