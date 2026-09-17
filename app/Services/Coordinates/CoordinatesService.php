<?php

namespace App\Services\Coordinates;

use App\Models\Coordinates;

class CoordinatesService
{
    public function getAll()
    {
        $coordinates = Coordinates::with('familyPlan')->get();

        return [
            'error' => false,
            'code' => 200,
            'message' => $coordinates->isEmpty()
                ? 'No hay registros de coordenadas'
                : 'Coordenadas obtenidas exitosamente',
            'data' => $coordinates,
        ];
    }

    public function getById($id)
    {
        $coordinates = Coordinates::with('familyPlan')->find($id);

        if (!$coordinates) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Coordenadas no encontradas',
            ];
        }

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Coordenadas obtenidas exitosamente',
            'data' => $coordinates,
        ];
    }

    public function getByFamilyPlan($familyPlanId)
    {
        $coordinates = Coordinates::with('familyPlan')
            ->where('family_plan_id', $familyPlanId)
            ->first();

        if (!$coordinates) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Coordenadas no encontradas para el plan familiar',
            ];
        }

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Coordenadas obtenidas exitosamente',
            'data' => $coordinates,
        ];
    }

    public function create(array $data)
    {
        $coordinates = Coordinates::create($data);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Coordenadas creadas exitosamente',
            'data' => $coordinates->load('familyPlan'),
        ];
    }

    public function update(array $data, $id)
    {
        $coordinates = Coordinates::find($id);

        if (!$coordinates) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Coordenadas no encontradas',
            ];
        }

        $coordinates->update($data);

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Coordenadas actualizadas exitosamente',
            'data' => $coordinates->load('familyPlan'),
        ];
    }

    public function delete($id)
    {
        $coordinates = Coordinates::find($id);

        if (!$coordinates) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'Coordenadas no encontradas',
            ];
        }

        $coordinates->delete();

        return [
            'error' => false,
            'code' => 200,
            'message' => 'Coordenadas eliminadas exitosamente',
        ];
    }
}