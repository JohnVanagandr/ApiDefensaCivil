<?php

namespace App\Models\FamilyPlan;

use App\Models\Coordinates;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Audit\Audit; // 🔹 Importar Audit para la relación
use App\Models\AvailableResource\AvailableResource;
use App\Models\familyType\familyType;

/** * Importación de modelos relacionados para definir las relaciones Eloquent 
 */

use App\Models\Zone\Zone;
use App\Models\City\City;
use App\Models\HousingQuality\HousingQuality;
use App\Models\Sector\Sector;
use App\Models\StatusPlan\StatusPlan;
use App\Models\Sectional\Sectional;
use App\Models\HousingInfo\HousingInfo;
use App\Models\HousingGraphic\HousingGraphic;
use App\Models\VulnerableTest\VulnerableTest;
use App\Models\FamilyMember\FamilyMember;
use App\Models\Pet\Pet;
use App\Models\RiskFactor\RiskFactor;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

/**
 * Clase FamilyPlan
 * * Este modelo es la entidad central del sistema. Coordina la información de 
 * los planes de intervención familiar, vinculando datos geográficos, 
 * socioeconómicos y el historial de acciones ejecutadas.
 */
class FamilyPlan extends Model
{
    use HasFactory;

    /**
     * Atributos que pueden ser asignados de forma masiva (Mass Assignment).
     * Se incluyen todos los campos necesarios para la creación y edición del plan.
     * * @var array
     */
    protected $fillable = [
        'id',
        'name',               // Nombre identificador del plan
        'last_names',         // Apellidos del grupo familiar
        'zone_id',            // Referencia a la zona geográfica
        'address',            // Dirección física de residencia
        'landline_phone',     // Teléfono fijo de contacto
        'georeference',       // Coordenadas o datos de ubicación GPS
        'city_id',            // Ciudad de residencia
        'housing_quality_id', // Calidad de la vivienda (maestra)
        'sector_id',          // ID del sector económico/social
        'sector_name',        // Nombre del sector (en caso de ser manual)
        'status_plan_id',     // Estado actual del plan (ej. Activo, Cerrado)
        'sectional_id',       // Seccional a la que pertenece el registro
        'user_id',            // Usuario responsable o creador del plan
        'comentary',          // Comentarios adicionales o notas del plan
        'family_type_id',     // Tipo de familia (maestra)
        'authorization'       // Consentimiento o autorización (booleano)
    ];

    protected function sectorName(): Attribute 
    {
        return Attribute::make(
            set: fn ($value) => $this -> normalizeName($value),
        );
    }

    protected function address(): Attribute 
    {
        return Attribute::make(
            set: fn ($value) => $this -> normalizeName($value),
        );
    }

    protected function lastNames(): Attribute 
    {
        return Attribute::make(
            set: fn ($value) => $this -> normalizeMayus($value),
        );
    }

    private function normalizeName(?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        // Colapsa espacios múltiples y quita espacios al inicio/final
        $value = trim(preg_replace('/\s+/', ' ', $value));

        // minúsculas primero, luego mayúscula inicial en cada palabra
        return mb_convert_case(mb_strtolower($value, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    private function normalizeMayus(?string $value): ?string
    {
        if (!$value) {
            return $value;
        }

        // Colapsa espacios múltiples y quita espacios al inicio/final
        $value = trim(preg_replace('/\s+/', ' ', $value));

        // Todo a mayúsculas (soporta tildes/ñ)
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Valores predeterminados para los atributos del modelo.
     * * Al instanciar un nuevo Plan Familiar, se asigna automáticamente 
     * el status_plan_id con valor 5 ya que es incompleto y apenas se esta realizando.
     * * @var array
     */
    protected $attributes = [
        'status_plan_id' => 1, // Valor predeterminado para "Registrado pero solo para que el voluntario lo vea"
    ];

    /**
     * --- RELACIONES BELONGS TO (Muchos a Uno) ---
     * Cada Plan Familiar pertenece a una única categoría de las siguientes tablas:
     */

    // Relación con la Zona geográfica
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    // Relación con la Ciudad
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    // Relación con la Calidad de Vivienda
    public function housingQuality()
    {
        return $this->belongsTo(HousingQuality::class, 'housing_quality_id');
    }

    // Relación con el Sector (Nótese el nombre en mayúscula según tu código original)
    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    // Relación con el Estado del Plan
    public function statusPlan()
    {
        return $this->belongsTo(StatusPlan::class, 'status_plan_id');
    }

    // Relación con la Seccional administrativa
    public function sectional()
    {
        return $this->belongsTo(Sectional::class, 'sectional_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function familyType()
    {
        return $this->belongsTo(familyType::class, 'family_type_id');
    }

    public function coordinates()
    {
        return $this->hasOne(Coordinates::class, 'family_plan_id');
    }

    /**
     * --- RELACIONES HAS MANY (Uno a Muchos) ---
     * Un Plan Familiar puede tener múltiples registros asociados en las siguientes tablas:
     */

    /**
     * Obtiene todos los tests de vulnerabilidad aplicados a esta familia.
     */
    public function vulnerableTest()
    {
        return $this->hasMany(VulnerableTest::class, 'family_plan_id');
    }
    public function housingInfo()
    {
        return $this->hasMany(HousingInfo::class, 'family_plan_id');
    }
    public function housingGraphic()
    {
        return $this->hasMany(HousingGraphic::class, 'family_plan_id');
    }
    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class, 'family_plan_id');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'family_plan_id');
    }
    public function riskFactors()
    {
        return $this->hasMany(RiskFactor::class, 'family_plan_id');
    }
    public function audits()
    {
        return $this->morphMany(Audit::class, 'historiable');
    }

    /**
     * --- SCOPES PARA FILTRADO POR ROL ---
     */

    /**
     * Scope para Administradores: pueden ver todos los planes
     */
    public function scopeForAdministrador(Builder $query): Builder
    {
        return $query;
    }

    /**
     * Scope para Supervisores: pueden ver todos los planes de su seccional
     */
    public function scopeForSupervisor(Builder $query): Builder
    {
        $user = Auth::user();

        // Si el supervisor tiene seccional asignado, filtrar por esa seccional
        if ($user->profile->organization->sectional_id) {
            return $query->where('sectional_id', $user->profile->organization->sectional_id);
        }

        // Si no tiene seccional, ver todos
        return $query;
    }

    /**
     * Scope para Voluntarios: solo ven los planes que ellos crearon
     */
    public function scopeForVoluntario(Builder $query): Builder
    {
        return $query->where('user_id', Auth::id());
    }

    /**
     * Scope principal: aplica el filtro automático según el rol del usuario autenticado
     */
    public function scopeForAuthUser(Builder $query): Builder
    {
        $user = Auth::user();

        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        return match (true) {
            $user->hasRole('Administrador') => $query->forAdministrador(),
            $user->hasRole('Supervisor') => $query->forSupervisor(),
            $user->hasRole('Voluntario') => $query->forVoluntario(),
            default => $query->whereRaw('1 = 0')
        };
    }

    public function availableResources()
    {
        return $this->hasMany(AvailableResource::class, 'family_plan_id');
    }

}
