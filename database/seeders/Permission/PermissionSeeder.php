<?php

namespace Database\Seeders\Permission;

use App\Models\Permission\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de permisos del sistema.
     * 
     * Nomenclatura:
     * - {módulo}.{acción}
     * - Acciones estándar: index, show, store, update, partial-update, destroy
     * - Acciones especiales: change-status, history, by-{criterio}
     */
    public function run(): void
    {
        $permissions = [
            
            // ================================================================
            // VISTAS FRONTEND POR ROL
            // ================================================================
            ['home-frontend.voluntario', 'Vista home del voluntario'],
            ['home-frontend.supervisor', 'Vista home del supervisor'],
            ['home-frontend.administrador', 'Vista home del administrador'],

            // ================================================================
            // CUENTA Y SEGURIDAD
            // ================================================================
            ['account.verify-password', 'Verificar contraseña para acciones sensibles'],
            ['account.update-email', 'Actualizar email de la cuenta'],
            ['account.update-password', 'Actualizar contraseña de la cuenta'],

            // ================================================================
            // GESTIÓN DE USUARIOS
            // ================================================================
            ['users.index', 'Listar usuarios'],
            ['users.by-status', 'Filtrar usuarios por estado'],
            ['users.requests-admins', 'Ver peticiones de acceso (Admin)'],
            ['users.requests-supervisors', 'Ver peticiones de acceso (Supervisor)'],
            ['users.show', 'Ver detalle de usuario'],
            ['users.history', 'Ver historial de cambios de usuario'],
            ['users.store', 'Crear usuario'],
            ['users.update', 'Actualizar usuario'],
            ['users.partial-update', 'Actualizar parcialmente usuario'],
            ['users.destroy', 'Eliminar usuario'],
            ['users.change-role', 'Cambiar rol de usuario'],
            ['users.change-status', 'Cambiar estado individual de usuario'],
            ['users.approve-bulk', 'Aprobar peticiones masivamente'],
            ['users.change-status-bulk', 'Cambiar estado de múltiples usuarios'],
            ['users.reject-delete-bulk', 'Rechazar y eliminar peticiones masivamente'],

            // ================================================================
            // PERFILES
            // ================================================================
            ['profiles.index', 'Listar perfiles'],
            ['profiles.show', 'Ver perfil'],
            ['profiles.store', 'Crear perfil'],
            ['profiles.update', 'Actualizar perfil'],
            ['profiles.partial-update', 'Actualizar parcialmente perfil'],
            ['profiles.destroy', 'Eliminar perfil'],
            ['profiles.history', 'Ver historial de cambios de perfil'],

            // ================================================================
            // CATÁLOGOS BÁSICOS
            // ================================================================
            
            // Estados de Usuario
            ['state-users.index', 'Listar estados de usuario'],
            ['state-users.show', 'Ver estado de usuario'],
            ['state-users.store', 'Crear estado de usuario'],
            ['state-users.update', 'Actualizar estado de usuario'],
            ['state-users.destroy', 'Eliminar estado de usuario'],

            // Estados de Plan
            ['status-plans.index', 'Listar estados de plan'],
            ['status-plans.show', 'Ver estado de plan'],
            ['status-plans.store', 'Crear estado de plan'],
            ['status-plans.update', 'Actualizar estado de plan'],
            ['status-plans.destroy', 'Eliminar estado de plan'],

            // Géneros
            ['genders.index', 'Listar géneros'],
            ['genders.show', 'Ver género'],
            ['genders.history', 'Ver historial de cambios de género'],
            ['genders.store', 'Crear género'],
            ['genders.update', 'Actualizar género'],
            ['genders.partial-update', 'Actualizar parcialmente género'],
            ['genders.change-status', 'Cambiar estado de género'],
            ['genders.destroy', 'Eliminar género'],

            // Tipos de Documento
            ['document-types.index', 'Listar tipos de documento'],
            ['document-types.show', 'Ver tipo de documento'],
            ['document-types.history', 'Ver historial de cambios de tipo de documento'],
            ['document-types.store', 'Crear tipo de documento'],
            ['document-types.update', 'Actualizar tipo de documento'],
            ['document-types.partial-update', 'Actualizar parcialmente tipo de documento'],
            ['document-types.change-status', 'Cambiar estado del tipo de documento'],
            ['document-types.destroy', 'Eliminar tipo de documento'],

            // ================================================================
            // ESTRUCTURA ORGANIZACIONAL
            // ================================================================
            
            // Seccionales
            ['sectionals.index', 'Listar seccionales'],
            ['sectionals.show', 'Ver seccional'],
            ['sectionals.active-with-organizations', 'Listar seccionales activas con organizaciones'],
            ['sectionals.history', 'Ver historial de cambios de seccional'],
            ['sectionals.store', 'Crear seccional'],
            ['sectionals.update', 'Actualizar seccional'],
            ['sectionals.partial-update', 'Actualizar parcialmente seccional'],
            ['sectionals.change-status', 'Cambiar estado de seccional'],
            ['sectionals.destroy', 'Eliminar seccional'],
            ['sectionals.stats-supervisor', 'Ver estadisticas'],

            // Organizaciones
            ['organizations.index', 'Listar organizaciones'],
            ['organizations.show', 'Ver organización'],
            ['organizations.by-sectional', 'Ver organizaciones por seccional'],
            ['organizations.history', 'Ver historial de cambios de organización'],
            ['organizations.store', 'Crear organización'],
            ['organizations.update', 'Actualizar organización'],
            ['organizations.partial-update', 'Actualizar parcialmente organización'],
            ['organizations.change-status', 'Cambiar estado de organización'],
            ['organizations.destroy', 'Eliminar organización'],

            // ================================================================
            // GEOGRAFÍA
            // ================================================================
            
            // Zonas
            ['zones.index', 'Listar zonas'],
            ['zones.show', 'Ver zona'],
            ['zones.store', 'Crear zona'],
            ['zones.update', 'Actualizar zona'],
            ['zones.destroy', 'Eliminar zona'],

            // Sectores
            ['sectors.index', 'Listar sectores'],
            ['sectors.show', 'Ver sector'],
            ['sectors.history', 'Ver historial de cambios de sector'],
            ['sectors.store', 'Crear sector'],
            ['sectors.update', 'Actualizar sector'],
            ['sectors.partial-update', 'Actualizar parcialmente sector'],
            ['sectors.change-status', 'Cambiar estado de sector'],
            ['sectors.destroy', 'Eliminar sector'],

            // Departamentos
            ['departments.index', 'Listar departamentos'],
            ['departments.show', 'Ver departamento'],
            ['departments.history', 'Ver historial de cambios de departamento'],
            ['departments.store', 'Crear departamento'],
            ['departments.update', 'Actualizar departamento'],
            ['departments.partial-update', 'Actualizar parcialmente departamento'],
            ['departments.destroy', 'Eliminar departamento'],

            // Ciudades
            ['cities.index', 'Listar ciudades'],
            ['cities.show', 'Ver ciudad'],
            ['cities.by-department', 'Ver ciudades por departamento'],
            ['cities.history', 'Ver historial de cambios de ciudad'],
            ['cities.store', 'Crear ciudad'],
            ['cities.update', 'Actualizar ciudad'],
            ['cities.partial-update', 'Actualizar parcialmente ciudad'],
            ['cities.destroy', 'Eliminar ciudad'],

            // ================================================================
            // PLANES FAMILIARES Y VIVIENDA
            // ================================================================
            
            // Planes Familiares
            ['family-plans.index', 'Listar planes familiares'],
            ['family-plans.stats-voluntario', 'Ver estadisticas'],
            ['family-plans.by-status', 'Filtrar planes familiares por estado'],
            ['family-plans.by-user', 'Ver planes familiares del usuario autenticado'],
            ['family-plans.show', 'Ver plan familiar'],
            ['family-plans.store', 'Crear plan familiar'],
            ['family-plans.update', 'Actualizar plan familiar'],
            ['family-plans.partial-update', 'Actualizar parcialmente plan familiar'],
            ['family-plans.identify', 'Identificar plan familiar'],
            ['family-plans.change-status', 'Cambiar estado de plan familiar'],
            ['family-plans.destroy', 'Eliminar plan familiar'],
            ['family-plans.check-access', 'Verificar acceso al plan familiar'],
            ['family-plans.download-pdf', 'Descargar PDF del plan familiar'],
            ['family-plans.has-members', 'Verificar si plan familiar tiene miembros'],
            ['family-plans.validate-requirements', 'Validar requisitos del plan familiar'],
            ['family-plans.change-family-type', 'Cambiar tipo de familia del plan familiar'],

            // Tipos de Familia
            ['family-types.index', 'Listar tipos de familia'],
            ['family-types.show', 'Ver tipo de familia'],
            ['family-types.history', 'Ver historial de cambios de tipo de familia'],
            ['family-types.store', 'Crear tipo de familia'],
            ['family-types.update', 'Actualizar tipo de familia'],
            ['family-types.partial-update', 'Actualizar parcialmente tipo de familia'],
            ['family-types.change-status', 'Cambiar estado de tipo de familia'],
            ['family-types.destroy', 'Eliminar tipo de familia'],

            // Información de Vivienda
            ['housing-info.index', 'Listar información de vivienda'],
            ['housing-info.show', 'Ver información de vivienda'],
            ['housing-info.store', 'Crear información de vivienda'],
            ['housing-info.destroy', 'Eliminar información de vivienda'],
            ['housing-info.by-type', 'Ver información de vivienda por tipo'],
            
            // Tipos de Información de Vivienda
            ['housing-info-type.index', 'Listar los tipos de informacion de vivienda'],
            ['housing-info-type.show', 'Ver tipo de información de vivienda'],
            ['housing-info-type.update', 'Actualizar tipo de información de vivienda'],
            ['housing-info-type.store', 'Crear tipo de información de vivienda'],
            ['housing-info-type.destroy', 'Eliminar tipo de información de vivienda'],

            // Calidades de Vivienda
            ['housing-qualities.index', 'Listar calidades de vivienda'],
            ['housing-qualities.show', 'Ver calidad de vivienda'],
            ['housing-qualities.history', 'Ver historial de cambios de calidad de vivienda'],
            ['housing-qualities.store', 'Crear calidad de vivienda'],
            ['housing-qualities.update', 'Actualizar calidad de vivienda'],
            ['housing-qualities.partial-update', 'Actualizar parcialmente calidad de vivienda'],
            ['housing-qualities.change-status', 'Cambiar estado de calidad de vivienda'],
            ['housing-qualities.destroy', 'Eliminar calidad de vivienda'],

            // Gráficos de Vivienda
            ['housing-graphics.index', 'Listar gráficos de vivienda'],
            ['housing-graphics.show', 'Ver gráfico de vivienda'],
            ['housing-graphics.by-family-plan', 'Ver gráficos por plan familiar'],
            ['housing-graphics.store', 'Crear gráfico de vivienda'],
            ['housing-graphics.update-description', 'Actualizar descripción del gráfico'],
            ['housing-graphics.destroy', 'Eliminar gráfico de vivienda'],

            // Coordenadas
            ['coordinates.index', 'Listar coordenadas'],
            ['coordinates.show', 'Ver coordenada'],
            ['coordinates.by-family-plan', 'Ver coordenadas por plan familiar'],
            ['coordinates.store', 'Crear coordenada'],
            ['coordinates.update', 'Actualizar coordenada'],
            ['coordinates.destroy', 'Eliminar coordenada'],

            // ================================================================
            // MIEMBROS Y RELACIONES
            // ================================================================
            
            // Grupos Sanguíneos
            ['blood-groups.index', 'Listar grupos sanguíneos'],
            ['blood-groups.show', 'Ver grupo sanguíneo'],
            ['blood-groups.store', 'Crear grupo sanguíneo'],
            ['blood-groups.update', 'Actualizar grupo sanguíneo'],
            ['blood-groups.destroy', 'Eliminar grupo sanguíneo'],

            // Nacionalidades
            ['nationalities.index', 'Listar nacionalidades'],
            ['nationalities.show', 'Ver nacionalidad'],
            ['nationalities.history', 'Ver historial de cambios de nacionalidad'],
            ['nationalities.store', 'Crear nacionalidad'],
            ['nationalities.update', 'Actualizar nacionalidad'],
            ['nationalities.partial-update', 'Actualizar parcialmente nacionalidad'],
            ['nationalities.change-status', 'Cambiar estado de nacionalidad'],
            ['nationalities.destroy', 'Eliminar nacionalidad'],

            // Parentescos
            ['kinships.index', 'Listar parentescos'],
            ['kinships.show', 'Ver parentesco'],
            ['kinships.store', 'Crear parentesco'],
            ['kinships.update', 'Actualizar parentesco'],
            ['kinships.destroy', 'Eliminar parentesco'],

            // Miembros
            ['members.index', 'Listar miembros'],
            ['members.show', 'Ver miembro'],
            ['members.by-family-plan', 'Ver miembros por plan familiar'],
            ['members.select-by-family-plan', 'Obtener miembros para selector'],
            ['members.store', 'Crear miembro'],
            ['members.update', 'Actualizar miembro'],
            ['members.partial-update', 'Actualizar parcialmente miembro'],
            ['members.destroy', 'Eliminar miembro'],

            // Relaciones Familiares
            ['family-members.index', 'Listar relaciones familiares'],
            ['family-members.show', 'Ver relación familiar'],
            ['family-members.store', 'Crear relación familiar'],
            ['family-members.update', 'Actualizar relación familiar'],
            ['family-members.destroy', 'Eliminar relación familiar'],

            // Tipos de Condición
            ['condition-types.index', 'Listar tipos de condición'],
            ['condition-types.show', 'Ver tipo de condición'],
            ['condition-types.store', 'Crear tipo de condición'],
            ['condition-types.update', 'Actualizar tipo de condición'],
            ['condition-types.destroy', 'Eliminar tipo de condición'],

            // Condiciones de Miembros
            ['condition-members.index', 'Listar condiciones de miembros'],
            ['condition-members.show', 'Ver condición de miembro'],
            ['condition-members.by-member', 'Ver condiciones por miembro'],
            ['condition-members.store', 'Crear condición de miembro'],
            ['condition-members.update', 'Actualizar condición de miembro'],
            ['condition-members.partial-update', 'Actualizar parcialmente condición de miembro'],
            ['condition-members.destroy', 'Eliminar condición de miembro'],

            // ================================================================
            // EPS
            // ================================================================

            ['eps.index', 'Listar EPS'],
            ['eps.show', 'Ver EPS'],
            ['eps.history', 'Ver historial de cambios de EPS'],
            ['eps.store', 'Crear EPS'],
            ['eps.update', 'Actualizar EPS'],
            ['eps.partial-update', 'Actualizar parcialmente EPS'],
            ['eps.change-status', 'Cambiar estado de EPS'],
            ['eps.destroy', 'Eliminar EPS'],

            // ================================================================
            // MASCOTAS
            // ================================================================
            
            // Especies
            ['species.index', 'Listar especies'],
            ['species.show', 'Ver especie'],
            ['species.history', 'Ver historial de cambios de especie'],
            ['species.store', 'Crear especie'],
            ['species.update', 'Actualizar especie'],
            ['species.partial-update', 'Actualizar parcialmente especie'],
            ['species.change-status', 'Cambiar estado de especie'],
            ['species.destroy', 'Eliminar especie'],

            // Géneros Animales
            ['animal-genders.index', 'Listar géneros de animales'],
            ['animal-genders.show', 'Ver género de animal'],
            ['animal-genders.store', 'Crear género de animal'],
            ['animal-genders.update', 'Actualizar género de animal'],
            ['animal-genders.destroy', 'Eliminar género de animal'],

            // Mascotas
            ['pets.index', 'Listar mascotas'],
            ['pets.show', 'Ver mascota'],
            ['pets.by-family-plan', 'Ver mascotas por plan familiar'],
            ['pets.store', 'Crear mascota'],
            ['pets.update', 'Actualizar mascota'],
            ['pets.partial-update', 'Actualizar parcialmente mascota'],
            ['pets.destroy', 'Eliminar mascota'],

            // Vacunas de Mascotas
            ['pet-vaccines.index', 'Listar vacunas de mascotas'],
            ['pet-vaccines.show', 'Ver vacuna de mascota'],
            ['pet-vaccines.by-pet', 'Ver vacunas por mascota'],
            ['pet-vaccines.store', 'Crear vacuna de mascota'],
            ['pet-vaccines.update', 'Actualizar vacuna de mascota'],
            ['pet-vaccines.partial-update', 'Actualizar parcialmente vacuna de mascota'],
            ['pet-vaccines.destroy', 'Eliminar vacuna de mascota'],

            // ================================================================
            // RIESGOS Y AMENAZAS
            // ================================================================
            
            // Tipos de Amenaza
            ['threat-types.index', 'Listar tipos de amenaza'],
            ['threat-types.show', 'Ver tipo de amenaza'],
            ['threat-types.history', 'Ver historial de cambios de tipo de amenaza'],
            ['threat-types.store', 'Crear tipo de amenaza'],
            ['threat-types.update', 'Actualizar tipo de amenaza'],
            ['threat-types.partial-update', 'Actualizar parcialmente tipo de amenaza'],
            ['threat-types.change-status', 'Cambiar estado de tipo de amenaza'],
            ['threat-types.destroy', 'Eliminar tipo de amenaza'],

            // Factores de Riesgo
            ['risk-factors.index', 'Listar factores de riesgo'],
            ['risk-factors.show', 'Ver factor de riesgo'],
            ['risk-factors.by-family-plan', 'Ver factores de riesgo por plan familiar'],
            ['risk-factors.select-by-family-plan', 'Obtener factores de riesgo para selector'],
            ['risk-factors.store', 'Crear factor de riesgo'],
            ['risk-factors.update', 'Actualizar factor de riesgo'],
            ['risk-factors.partial-update', 'Actualizar parcialmente factor de riesgo'],
            ['risk-factors.destroy', 'Eliminar factor de riesgo'],

            // Acciones de Reducción de Riesgo
            ['risk-reduction-actions.index', 'Listar acciones de reducción de riesgo'],
            ['risk-reduction-actions.show', 'Ver acción de reducción de riesgo'],
            ['risk-reduction-actions.by-risk-factor', 'Ver acciones por factor de riesgo'],
            ['risk-reduction-actions.store', 'Crear acción de reducción de riesgo'],
            ['risk-reduction-actions.update', 'Actualizar acción de reducción de riesgo'],
            ['risk-reduction-actions.partial-update', 'Actualizar parcialmente acción de reducción'],
            ['risk-reduction-actions.destroy', 'Eliminar acción de reducción de riesgo'],

            // ================================================================
            // VULNERABILIDAD
            // ================================================================
            
            // Vulnerabilidades
            ['vulnerabilities.index', 'Listar vulnerabilidades'],
            ['vulnerabilities.show', 'Ver vulnerabilidad'],
            ['vulnerabilities.history', 'Ver historial de cambios de vulnerabilidad'],
            ['vulnerabilities.store', 'Crear vulnerabilidad'],
            ['vulnerabilities.update', 'Actualizar vulnerabilidad'],
            ['vulnerabilities.partial-update', 'Actualizar parcialmente vulnerabilidad'],
            ['vulnerabilities.change-status', 'Cambiar estado de vulnerabilidad'],
            ['vulnerabilities.destroy', 'Eliminar vulnerabilidad'],

            // Factores de Vulnerabilidad
            ['vulnerability-factors.index', 'Listar factores de vulnerabilidad'],
            ['vulnerability-factors.show', 'Ver factor de vulnerabilidad'],
            ['vulnerability-factors.by-risk-factor', 'Ver factores por factor de riesgo'],
            ['vulnerability-factors.store', 'Crear factor de vulnerabilidad'],
            ['vulnerability-factors.update', 'Actualizar factor de vulnerabilidad'],
            ['vulnerability-factors.partial-update', 'Actualizar parcialmente factor de vulnerabilidad'],
            ['vulnerability-factors.destroy', 'Eliminar factor de vulnerabilidad'],

            // Grados de Vulnerabilidad
            ['vulnerability-grades.index', 'Listar grados de vulnerabilidad'],
            ['vulnerability-grades.show', 'Ver grado de vulnerabilidad'],
            ['vulnerability-grades.store', 'Crear grado de vulnerabilidad'],
            ['vulnerability-grades.update', 'Actualizar grado de vulnerabilidad'],
            ['vulnerability-grades.destroy', 'Eliminar grado de vulnerabilidad'],

            // Preguntas del Test de Vulnerabilidad
            ['vulnerable-questions.index', 'Listar preguntas de vulnerabilidad'],
            ['vulnerable-questions.paginate', 'Paginar preguntas de vulnerabilidad'],
            ['vulnerable-questions.show', 'Ver pregunta de vulnerabilidad'],
            ['vulnerable-questions.history', 'Ver historial de cambios de pregunta'],
            ['vulnerable-questions.store', 'Crear pregunta de vulnerabilidad'],
            ['vulnerable-questions.update', 'Actualizar pregunta de vulnerabilidad'],
            ['vulnerable-questions.partial-update', 'Actualizar parcialmente pregunta de vulnerabilidad'],
            ['vulnerable-questions.change-status', 'Cambiar estado de pregunta de vulnerabilidad'],
            ['vulnerable-questions.destroy', 'Eliminar pregunta de vulnerabilidad'],

            // Test de Vulnerabilidad
            ['vulnerable-tests.index', 'Listar tests de vulnerabilidad'],
            ['vulnerable-tests.show', 'Ver resultados de test de vulnerabilidad'],
            ['vulnerable-tests.store', 'Registrar respuestas de test de vulnerabilidad'],
            ['vulnerable-tests.destroy', 'Eliminar test de vulnerabilidad'],

            // ================================================================
            // RECURSOS
            // ================================================================
            
            // Catálogo de Recursos
            ['resources.index', 'Listar recursos'],
            ['resources.show', 'Ver recurso'],
            ['resources.history', 'Ver historial de cambios de recurso'],
            ['resources.store', 'Crear recurso'],
            ['resources.update', 'Actualizar recurso'],
            ['resources.partial-update', 'Actualizar parcialmente recurso'],
            ['resources.change-status', 'Cambiar estado de recurso'],
            ['resources.destroy', 'Eliminar recurso'],

            // Recursos Disponibles
            ['available-resources.index', 'Listar recursos disponibles'],
            ['available-resources.show', 'Ver recurso disponible'],
            ['available-resources.by-family-plan', 'Ver recursos por plan familiar'],
            ['available-resources.store', 'Crear recurso disponible'],
            ['available-resources.update', 'Actualizar recurso disponible'],
            ['available-resources.partial-update', 'Actualizar parcialmente recurso disponible'],
            ['available-resources.destroy', 'Eliminar recurso disponible'],

            // ================================================================
            // PLANES DE ACCIÓN
            // ================================================================
            
            // Acciones
            ['actions.index', 'Listar acciones'],
            ['actions.show', 'Ver detalle de acción'],
            ['actions.store', 'Crear nueva acción'],
            ['actions.update', 'Actualizar acción'],
            ['actions.destroy', 'Eliminar acción'],

            // Tipos de Acción
            ['action-types.index', 'Listar tipos de acción'],
            ['action-types.show', 'Ver tipo de acción'],
            ['action-types.store', 'Crear tipo de acción'],
            ['action-types.update', 'Actualizar tipo de acción'],
            ['action-types.destroy', 'Eliminar tipo de acción'],

            // Planes de Acción
            ['action-plans.index', 'Listar planes de acción'],
            ['action-plans.show', 'Ver plan de acción'],
            ['action-plans.by-family-plan', 'Ver plan de acción por plan familiar'],
            ['action-plans.check-exists', 'Verificar existencia de plan de acción'],
            ['action-plans.store', 'Crear plan de acción'],
            ['action-plans.update', 'Actualizar plan de acción'],
            ['action-plans.destroy', 'Eliminar plan de acción'],

            // Acciones del Plan
            ['action-plan-actions.index', 'Listar acciones del plan'],
            ['action-plan-actions.show', 'Ver acción del plan'],
            ['action-plan-actions.by-action-plan', 'Ver acciones por plan de acción'],
            ['action-plan-actions.store', 'Crear acción del plan'],
            ['action-plan-actions.update', 'Actualizar acción del plan'],
            ['action-plan-actions.partial-update', 'Actualizar parcialmente acción del plan'],
            ['action-plan-actions.destroy', 'Eliminar acción del plan'],

            // ================================================================
            // AUDITORÍA Y DASHBOARDS
            // ================================================================
            ['audits.dashboard-admin', 'Ver dashboard de administrador'],
            ['audits.delete_id', 'Eliminar dato audit'],
            ['audits.delete_bulk', 'Eliminar datos audit multiple'],
            ['audits.dashboard-supervisor', 'Ver dashboard de supervisor'],

            // ================================================================
            // NOTIFICACIONES
            // ================================================================
            ['notifications.index', 'Listar todas las notificaciones'],
            ['notifications.show', 'Ver notificación'],
            ['notifications.count-unread-by-user', 'Contar notificaciones no leídas de usuario'],
            ['notifications.unread-by-user', 'Ver notificaciones no leídas de usuario'],
            ['notifications.by-user', 'Ver todas las notificaciones de usuario'],
            ['notifications.store', 'Crear notificación'],
            ['notifications.update', 'Actualizar notificación'],
            ['notifications.partial-update', 'Actualizar parcialmente notificación'],
            ['notifications.destroy', 'Eliminar notificación'],

            ['pdf.show', 'ver el pdf de plan familiar']
        ];

        foreach ($permissions as [$name, $description]) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }
}