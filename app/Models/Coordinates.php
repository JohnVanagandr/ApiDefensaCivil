<?php

namespace App\Models;

use App\Models\FamilyPlan\FamilyPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordinates extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_plan_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function familyPlan()
    {
        return $this->belongsTo(FamilyPlan::class, 'family_plan_id');
    }
}
