<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilterByOrganization
{
    public function scopeFilterByOrganization(Builder $query)
    {
        $user = Auth::user();

        if ($user && !$user->isSuperAdmin()) {
            $organizationId = $user->organization_id;

            if ($organizationId) {
                $table = $this->getTable();
                return $query->where("$table.organization_id", $organizationId);
            }
        }

        return $query;
    }
}
