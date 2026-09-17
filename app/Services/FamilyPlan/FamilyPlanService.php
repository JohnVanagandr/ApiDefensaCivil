<?php

namespace App\Services\FamilyPlan;

use App\Models\FamilyPlan\FamilyPlan;
use App\Services\Notification\NotificationService;
use App\Models\History\History;
use Illuminate\support\Arr;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Servicio para la gestión de Planes Familiares.
 * Incluye lógica de auditoría para el seguimiento de registros.
 */
class FamilyPlanService
{
    /**
     * Obtiene todos los planes familiares paginados según el rol del usuario autenticado.
     * 
     * 🔹 Aplica filtros automáticos por rol:
     *    - Administrador: Ve todos los planes sin restricción
     *    - Supervisor: Ve planes de su seccional
     *    - Voluntario: Ve solo los planes que creó (user_id)
     * 
     * @param int $perPage Cantidad de registros por página
     * @return array Respuesta con datos paginados y metadata
     */
    // public static function getAll(int $perPage = 15): array
    public static function getAll($perPage): array
    {
        // 🔹 Obtener planes aplicando el scope forAuthUser (filtra por rol automáticamente)
        $paginator = FamilyPlan::forAuthUser()
            ->with([
                'zone',                  // Relación con zona geográfica
                'city',                  // Relación con ciudad
                'city.department',       // Departamento a través de city
                'statusPlan',            // Estado actual del plan
                'sectional',             // Seccional administrativa
                'user',                  // Usuario responsable/creador
                'familyType',
                'coordinates',
            ])
            ->orderBy('created_at', 'desc') // Ordenar por fecha de creación (más reciente primero)
            ->paginate($perPage);

        // 🔹 Transformar la colección para formatear la respuesta
        $items = $paginator->map(function ($plan) {
            return [
                'id'             => $plan->id,
                'last_names'     => $plan->last_names,
                'address'        => $plan->address ?? 'Sin dirección',
                'comentary'      => $plan->comentary ?? 'No hay comentarios',
                'zone'           => $plan->zone?->name,              // Usar null safe operator
                'city'           => $plan->city?->name,
                'department'     => $plan->city?->department?->name,
                'status'         => $plan->statusPlan?->name,
                'status_id'      => $plan->statusPlan?->id,
                'sectional'      => $plan->sectional?->name,
                'responsable'    => $plan->user?->profile?->names,
                'responsable_id' => $plan->user?->id,  
                'date_create'    => $plan->created_at->format('d/m/Y'), // Formato DD/MM/YYYY
                'family_type'    => $plan->familyType?->name,
                'family_type_id' => $plan->familyType?->id,
                'coordinates'   => [
                    'id'        => $plan->coordinates?->id,
                    'latitude'  => $plan->coordinates?->latitude,
                    'longitude' => $plan->coordinates?->longitude,
                ],
            ];
        });

        // 🔹 Retornar respuesta estructurada
        return [
            "error" => false,
            "code" => 200,
            "message" => $items->isEmpty()
                ? "No hay planes familiares disponibles"
                : "Planes familiares obtenidos exitosamente",
            "data" => $items,
            "paginate" => [
                'current_page' => $paginator->currentPage(),  // Página actual
                'per_page'     => $paginator->perPage(),      // Registros por página
                'total'        => $paginator->total(),        // Total de registros
                'last_page'    => $paginator->lastPage(),     // Última página
                'from'         => $paginator->firstItem(),    // Primer registro de la página
                'to'           => $paginator->lastItem(),     // Último registro de la página
            ],
        ];
    }

    /**
     * Obtiene un plan familiar específico por su ID.
     * 
     * 🔹 Incluye relaciones anidadas como city.department
     * 🔹 Retorna datos transformados con formato consistente
     * 
     * @param int $id ID del plan familiar
     * @return array Respuesta con el plan o error si no existe
     */
    public function getById($id)
    {
        // 🔹 Buscar plan con relaciones
        $familyPlan = FamilyPlan::forAuthUser()
            ->with([
                'city.department',
                'zone',
                'statusPlan',
                'sectional',
                'user',
                'housingQuality',
                'sector',
                'familyType',
                'coordinates',
            ])->find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // 🔹 Transformar datos manualmente
        $data = [
            'id'                 => $familyPlan->id,
            'last_names'         => $familyPlan->last_names,
            'address'            => $familyPlan->address,
            'landline_phone'     => $familyPlan->landline_phone,
            'georeference'       => $familyPlan->georeference,
            'comentary'          => $familyPlan->comentary,
            'authorization'      => $familyPlan->authorization,

            // IDs
            'zone_id'            => $familyPlan->zone_id,
            'city_id'            => $familyPlan->city_id,
            'department_id'      => $familyPlan->city?->department?->id,
            'housing_quality_id' => $familyPlan->housing_quality_id,
            'sector_id'          => $familyPlan->sector_id,
            'status_plan_id'     => $familyPlan->status_plan_id,
            'sectional_id'       => $familyPlan->sectional_id,
            'user_id'            => $familyPlan->user_id,
            'family_type_id'     => $familyPlan->familyType?->id,
            'family_type'        => $familyPlan->familyType?->name,

            // Nombres de relaciones
            'zone'               => $familyPlan->zone?->name,
            'city'               => $familyPlan->city?->name,
            'department'         => $familyPlan->city?->department?->name,
            'housing_quality'    => $familyPlan->housingQuality?->name,
            'sector_name'        => $familyPlan->sector_name ?? $familyPlan->sector?->name,
            'status'             => $familyPlan->statusPlan?->name,
            'sectional'          => $familyPlan->sectional?->name,
            'responsable'        => $familyPlan->user?->profile?->names,
            'responsable_id'     => $familyPlan->user?->id,  

            // Coordenadas geográficas
            'coordinates'        => [
                'id'        => $familyPlan->coordinates?->id,
                'latitude'  => $familyPlan->coordinates?->latitude,
                'longitude' => $familyPlan->coordinates?->longitude,
            ],

            // Fechas
            'created_at'         => $familyPlan->created_at->format('d/m/Y'),
            'updated_at'         => $familyPlan->updated_at->format('d/m/Y'),
        ];

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar obtenido exitosamente",
            "data" => $data,
        ];
    }

    /**
     * Crea un nuevo plan familiar.
     * 
     * 🔹 Los valores por defecto se aplican desde el modelo (status_plan_id = 1)
     * 
     * @param array $data Datos del plan a crear
     * @return array Respuesta con el plan creado
     */
    public function create(array $data)
    {
        // 🔹 Crear plan (aplica fillable y attributes del modelo)
        $familyPlan = FamilyPlan::create($data);

        return [
            "error" => false,
            "code" => 201,
            "message" => "Plan familiar creado exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Actualización completa de un plan familiar (PUT).
     * 
     * 🔹 Reemplaza todos los campos con los datos enviados
     * 
     * @param array $data Nuevos datos del plan
     * @param int $id ID del plan a actualizar
     * @return array Respuesta con el plan actualizado o error
     */
    public function update(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // 🔹 Actualizar todos los campos
        $familyPlan->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar actualizado exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Actualización parcial de campos del plan (PATCH).
     * 
     * 🔹 Solo actualiza los campos enviados en $data
     * 
     * @param array $data Campos a actualizar
     * @param int $id ID del plan
     * @return array Respuesta con el plan actualizado
     */
    public function partialUpdate(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // 🔹 Actualizar solo campos presentes en $data
        $familyPlan->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar actualizado parcialmente exitosamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Cambia el estado de un plan familiar y registra auditoría.
     * 
     * 🔹 Guarda el historial del cambio de estado en la tabla audits
     * 🔹 Solo audita si el nuevo estado no es 1, 2 o 3
     * 
     * @param array $data Debe contener status_plan_id
     * @param int $id ID del plan
     * @return array Respuesta con el plan actualizado
     */
    public function changeStatus(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // 🔹 Guardar estado anterior
        $oldStatus = $familyPlan->statusPlan->name;

        // 🔹 Actualizar estado
        $familyPlan->update($data);

        // 🔹 Recargar relación para obtener el nuevo estado
        $familyPlan->refresh()->load('statusPlan');
        $newStatus = $familyPlan->statusPlan->name;

        // 🔹 Registrar auditoría si no es estado 1, 2 o 3
        if ($newStatus != 1 && $newStatus != 2 && $newStatus != 3) {
            // $familyPlan->audits()->create
            $audit = $familyPlan->audits()->create([
                'user_name'      => auth()->user()->profile->names . " " . auth()->user()->profile->last_names,
                'rol_name'       => auth()->user()->getRoleNames()->first(),
                'date_time'      => now(),
                'action_execute' => 'Cambio de estado del plan familiar',
                'status_old'     => $oldStatus,
                'status_new'     => $newStatus,
            ]);

            $newStatusId = $familyPlan->status_plan_id;

            if ($newStatusId == 4) {
                NotificationService::notifySupervisoresBySectional($familyPlan->sectional_id, $audit->id);
            } elseif (in_array($newStatusId, [5, 6, 7])) {
                NotificationService::notify($familyPlan->user_id, $audit->id);
            }
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Cambio de estado del plan familiar actualizado correctamente",
            "data" => $familyPlan,
        ];
    }

    public function patchFamilyType(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // 🔹 Actualizar solo el campo family_type_id
        if (isset($data['family_type_id'])) {
            $familyPlan->update(['family_type_id' => $data['family_type_id']]);
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Tipo de familia del plan familiar actualizado correctamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Actualiza los datos de identificación específicos del plan.
     * 
     * 🔹 Método dedicado para actualizar campos de identificación
     * 
     * @param array $data Datos de identificación a actualizar
     * @param int $id ID del plan
     * @return array Respuesta con el plan actualizado
     */
    public function identify(array $data, $id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        $familyPlan->update($data);

        return [
            "error" => false,
            "code" => 200,
            "message" => "Identificacion del plan familiar actualizado correctamente",
            "data" => $familyPlan,
        ];
    }

    /**
     * Elimina un plan familiar del sistema.
     * 
     * 🔹 Primero elimina el historial asociado
     * 🔹 Luego elimina el plan (soft delete si está configurado)
     * 
     * @param int $id ID del plan a eliminar
     * @return array Respuesta de confirmación o error
     */
    public function delete($id)
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
            ];
        }

        // 🔹 Eliminar plan
        $familyPlan->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Plan familiar eliminado exitosamente",
        ];
    }

    /**
     * Verifica si el usuario autenticado tiene acceso a un plan específico.
     * 
     * 🔹 Lógica de acceso por rol:
     *    - Rol 1 (Administrador): No aplica esta validación
     *    - Rol 2 (Supervisor): Acceso si pertenece a la misma seccional y estado es 4 o 7
     *    - Rol 3 (Voluntario): Acceso si es el creador (user_id) y no está en estado 1,2,4,6,7
     * 
     * @param string $planId ID del plan a verificar
     * @return array Respuesta con access_check (true/false)
     */
    public function checkAccess(string $planId): array
    {
        $user = auth()->user();
        $access = false;

        if (!$user) {
            return [
                "error" => false,
                "code" => 401,
                "message" => "Usuario no autenticado",
                "data" => [
                    "access_check" => $access
                ]
            ];
        }

        $plan = FamilyPlan::find($planId);

        if (!$plan) {
            return [
                "error" => false,
                "code" => 404,
                "message" => "Plan familiar no encontrado",
                "data" => [
                    "access_check" => $access
                ]
            ];
        }

        // 🔹 Obtener ID del primer rol del usuario
        $roleId = $user->roles->first()?->id ?? null;

        // 🔹 Si NO es rol 2 (Supervisor) ni 3 (Voluntario) → sin acceso
        if ($roleId != 2 && $roleId != 3) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "Verificación de acceso realizada",
                "data" => [
                    "access_check" => $access
                ]
            ];
        }

        // 🔹 Lógica para Voluntario (Rol 3)
        if ($roleId == 3) {
            // No puede acceder si el estado es 4 o 6
            if (!in_array($plan->status_plan_id, [1, 2, 4, 6, 7])) {
                $access = $plan->user_id == $user->id;
            }
        }

        // 🔹 Lógica para Supervisor (Rol 2)
        if ($roleId == 2) {
            if (
                $user->profile &&
                $user->profile->organization &&
                $user->profile->organization->sectional_id === $plan->sectional_id &&
                in_array($plan->status_plan_id, [4, 7])
            ) {
                $access = true;
            }
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Verificación de acceso realizada",
            "data" => [
                "access_check" => $access
            ]
        ];
    }

    /**
     * Genera un PDF del plan familiar.
     * 
     * 🔹 Crea un documento PDF simple con información básica
     * 🔹 Retorna el PDF como descarga
     * 
     * @param int $id ID del plan
     * @return \Illuminate\Http\Response PDF para descargar
     */
    public function generatePdf($id)
    {
        $plan = FamilyPlan::findOrFail($id);

        // 🔹 Crear HTML básico para el PDF
        $html = '
            <h1>Plan Familiar #' . $plan->id . '</h1>
            <p><strong>Nombre:</strong> ' . $plan->last_names . '</p>
            <p><strong>Fecha:</strong> ' . $plan->created_at . '</p>
            <ul>
        ';

        $html .= '</ul>';

        // 🔹 Generar PDF desde HTML
        $pdf = Pdf::loadHTML($html);

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="plan_' . $plan->id . '.pdf"');
    }

    /**
     * Verifica si un plan familiar tiene al menos un integrante registrado.
     *
     * 🔹 Se usa para validar antes de realizar operaciones que requieren
     *    que el plan tenga integrantes (ej: activar, enviar, procesar).
     *
     * @param int $id ID del plan familiar a verificar
     * @return array Respuesta con has_members (true/false)
     */
    public function hasMembers(int $id): array
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                'error'   => true,
                'code'    => 404,
                'message' => 'Plan familiar no encontrado',
            ];
        }

        // 🔹 Verificar si existe al menos un miembro
        return [
            'error'   => false,
            'code'    => 200,
            'message' => 'Verificación de integrantes realizada',
            'data'    => [
                'has_members' => $familyPlan->familyMembers()->exists(),
            ],
        ];
    }

    /**
     * Verifica si un plan familiar tiene al menos un factor de riesgo registrado.
     *
     * 🔹 Se usa para validar antes de realizar operaciones que requieren
     *    que el plan tenga factores de riesgo identificados (ej: activar, procesar).
     * 🔹 Utiliza la relación Eloquent `riskFactors()` definida en el modelo.
     *
     * @param int $id ID del plan familiar a verificar
     * @return array Respuesta con has_risk_factors (true/false)
     */
    public function hasRiskFactors(int $id): array
    {
        $familyPlan = FamilyPlan::find($id);

        if (!$familyPlan) {
            return [
                'error'   => true,
                'code'    => 404,
                'message' => 'Plan familiar no encontrado',
            ];
        }

        // 🔹 Verificar si existe al menos un factor de riesgo
        return [
            'error'   => false,
            'code'    => 200,
            'message' => 'Verificación de factores de riesgo realizada',
            'data'    => [
                'has_risk_factors' => $familyPlan->riskFactors()->exists(),
            ],
        ];
    }

    /**
     * Valida que el plan familiar cumpla los requisitos mínimos para procesamiento.
     *
     * 🔹 **Requisitos mínimos obligatorios:**
     *    - Al menos 1 integrante registrado (`familyMembers`)
     *    - Al menos 1 factor de riesgo identificado (`riskFactors`)
     * 🔹 Retorna código HTTP 422 si no cumple requisitos (estándar Laravel)
     * 🔹 Incluye conteos exactos para debugging y UX
     *
     * @param int $id ID del plan familiar a verificar
     * @return array Respuesta completa con estado de validación
     */
    public function validateRequirements(int $id): array
    {
        // 🔹 Verificar existencia del plan
        $familyPlan = FamilyPlan::find($id);
        if (!$familyPlan) {
            return [
                'error'   => true,
                'code'    => 404,
                'message' => 'Plan familiar no encontrado',
            ];
        }

        // Verifica que haya al menos 2 integrantes registrados
        $membersCount = $familyPlan->familyMembers()->count();
        $hasMinMembers = $membersCount >= 2;
        // $hasMembers = $familyPlan->familyMembers()->exists();


        $hasBasicData = $familyPlan->address
        && $familyPlan->sector_id
        && $familyPlan->sector_name
        && $familyPlan->last_names
        && $familyPlan->city_id
        && $familyPlan->department_id
        && $familyPlan->zone_id;


        // Verifica que haya al menos 1 factor de riesgo registrado
        $riskFactorsCount = $familyPlan->riskFactors()->count();
        $hasMinRiskFactors = $riskFactorsCount >= 1;
        // $hasRiskFactors = $familyPlan->riskFactors()->exists();


        // Verifica que haya al menos un recurso disponible registrado
        $resourcesCount = $familyPlan->availableResources()->count();
        $hasMinResources = $resourcesCount >= 1;


        // Al menos debe haber una foto de entorno registrada
        $photosCount = $familyPlan->housingInfo()->where('housing_info_type_id', 2)->count();
        $hasMinPhotos = $photosCount === 1;


        // Debe al menos existir un grafico (plano) de la vivienda registrado
        // $housingGraphicsCount = $familyPlan->housingInfo()->count();
        // $hasMinHousingGraphics = $housingGraphicsCount >= 1;


        // Verificar si el plan de acción al menos tienes un antes, durante y después registrado
        $actionPlanIds = \App\Models\ActionPlan\ActionPlan::whereHas('riskFactor', function ($q) use ($id) {
        $q->where('family_plan_id', $id); })->pluck('id');

        $hasActionBefore = \App\Models\ActionPlanAction\ActionPlanAction::whereIn('action_plan_id', $actionPlanIds)
            ->where('action_type_id', 1)->exists();

        $hasActionDuring = \App\Models\ActionPlanAction\ActionPlanAction::whereIn('action_plan_id', $actionPlanIds)
            ->where('action_type_id', 2)->exists();

        $hasActionAfter = \App\Models\ActionPlanAction\ActionPlanAction::whereIn('action_plan_id', $actionPlanIds)
            ->where('action_type_id', 3)->exists();

        $hasActionPlan = $hasActionBefore && $hasActionDuring && $hasActionAfter;


        // Determinar validez (ambos requisitos deben cumplirse)
        // $isValid = $hasMembers && $hasRiskFactors;
        $isValid = $hasMinMembers
            && $hasMinRiskFactors
            && $hasMinResources
            && $hasMinPhotos
            && $hasBasicData
            // && $hasMinHousingGraphics
            && $hasActionPlan;


        return [
            'error'   => false,
            'code'    => $isValid ? 200 : 422,
            'message' => $isValid
                ? 'El plan familiar cumple con todos los requisitos para ser enviado'
                : 'El plan familiar no cumple con todos los requisitos',

            'data'    => [

                'is_valid'           => $isValid,

                'has_min_members'    => $hasMinMembers,
                'members_count'      => $membersCount,

                'has_risk_factors'   => $hasMinRiskFactors,
                'risk_factors_count' => $riskFactorsCount,

                'has_resources'      => $hasMinResources,
                'resources_count'    => $resourcesCount,

                'has_photos'         => $hasMinPhotos,
                'photos_count'       => $photosCount,

                'has_basic_data' => $hasBasicData,
                'missing_basic_data' => array_filter([
                    !$familyPlan->address ? 'address' : null,
                    !$familyPlan->sector_id ? 'sector_id' : null,
                    !$familyPlan->sector_name ? 'sector_name' : null,
                    !$familyPlan->last_names ? 'last_names' : null,
                    !$familyPlan->city_id ? 'city_id' : null,
                    !$familyPlan->department_id ? 'department_id' : null,
                    !$familyPlan->zone_id ? 'zone_id' : null,
                ]),

                'has_action_before'  => $hasActionBefore,
                'has_action_during'  => $hasActionDuring,
                'has_action_after'   => $hasActionAfter,

                // 'has_members'        => $hasMembers,
                // 'members_count'      => $familyPlan->familyMembers()->count(),
                // 'has_risk_factors'   => $hasRiskFactors,
                // 'risk_factors_count' => $familyPlan->riskFactors()->count(),
            ],
        ];
    }

    /**
     * Obtiene los planes familiares filtrados por estado.
     *
     * Retorna una lista paginada de planes familiares que coinciden con el ID de estado
     * proporcionado. Cada plan incluye información básica: apellidos, ubicación geográfica,
     * estado actual y fecha de creación.
     *
     * @param int $statusId ID del estado por el cual filtrar los planes
     * @param int $perPage Cantidad de elementos por consulta
     * @return array Respuesta estructurada con datos paginados y metainformación
     */
    public function getByStatus(int $statusId, int $perPage = 10)
    {
        // Obtiene planes familiares filtrados por estado con paginación de 10 registros
        $data = FamilyPlan::where('status_plan_id', $statusId)->forAuthUser()->paginate($perPage);

        // Transforma cada plan al formato de respuesta esperado
        $plans = $data->map(function ($plan) {
            return [
                "id"          => $plan->id,
                "last_names"  => $plan->last_names,
                "city"        => $plan->city->name,
                "department"  => $plan->city->department->name,
                "status"      => $plan->statusPlan->name,
                "status_id"   => $plan->statusPlan->id,
                "date_create" => $plan->created_at->format('d/m/Y'),
            ];
        });

        // Retorna respuesta estructurada con datos y metadatos de paginación
        return [
            "error" => false,
            "code" => 200,
            "message" => $plans->isEmpty()
                ? "No hay planes familiares disponibles"
                : "Planes familiares obtenidos exitosamente",
            "data" => $plans,
            "paginate" => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ];
    }

    public function getStatsVoluntario(): array
    {
        $user = auth()->user();


        if ($user && $user->roles()->whereIn('id', [1, 2])->exists()) {
            return [
                'error'   => true,
                'code'    => 403,
                'message' => 'Acceso denegado: Los supervisores y administradores no tienen permisos para consultar estadísticas de voluntario.',
            ];
        }

        
        $planes = FamilyPlan::where('user_id', $user->id)->get();

        $totalPlanes = $planes->count();
        $familiasVulnerables = $planes->where('family_type_id', 1)->count();
        $familiasNoVulnerables = $planes->where('family_type_id', 2)->count();

        $tiposFamilias = [
            'Familias Vulnerables'   => $familiasVulnerables,
            'Familias no Vulnerables' => $familiasNoVulnerables,
        ];

        return [
            'error'   => false,
            'code'    => 200,
            'message' => 'Mis estadísticas personales de voluntario obtenidas exitosamente',
            'data'    => [
                'total_planes'         => $totalPlanes,
                'familias_registradas' => $tiposFamilias,
            ],
        ];
    }
}
