<?php

namespace App\Http\Requests\Coordinates;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoordinatesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'family_plan_id' => 'required|integer|exists:family_plans,id|unique:coordinates,family_plan_id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ];
    }

    public function attributes(): array
    {
        return [
            'family_plan_id' => 'plan familiar',
            'latitude' => 'latitud',
            'longitude' => 'longitud',
        ];
    }
}