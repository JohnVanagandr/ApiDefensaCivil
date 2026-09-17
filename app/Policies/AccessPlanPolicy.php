<?php

namespace App\Policies;

use App\Models\FamilyPlan\FamilyPlan;

class AccessPlanPolicy
{
    /**
     * Verifica si el usuario tiene acceso a un FamilyPlan específico.
     *
     * @param  \App\Models\User  $user
     * @param  int  $planId
     * @return bool
     */
    public function access(int $planId): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $plan = FamilyPlan::find($planId);

        if (!$plan) {
            return false;
        }

        $access = false;

        $roleId = $user->roles->first()?->id ?? null;

        // 🔴 Si NO es 2 ni 3 → sin acceso
        if ($roleId != 2 && $roleId != 3) {
            return false;
        }

        // 🟢 Rol 3 → Voluntario
        if ($roleId == 3) {

            // Solo si el estado NO está en [2,4,6,7]
            if (!in_array($plan->status_plan_id, [2, 4, 6, 7])) {
                $access = $plan->user_id == $user->id;
            }
        }

        // 🔵 Rol 2 → Supervisor
        if ($roleId == 2) {

            if (
                $user->profile &&
                $user->profile->organization &&
                $user->profile->organization->sectional_id === $plan->sectional_id &&
                // $plan->status_plan_id == 4
                in_array($plan->status_plan_id, [4,5,6,7])
            ) {
                $access = true;
            }
        }

        return $access;
    }
}