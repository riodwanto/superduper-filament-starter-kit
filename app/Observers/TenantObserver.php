<?php

namespace App\Observers;

use App\Models\Tenant;
use App\Models\User;

class TenantObserver
{
    /**
     * Handle the Tenant "created" event.
     */
    public function created(Tenant $tenant): void
    {
        $this->addSuperadminToTenant($tenant);
    }

    /**
     * Handle the Tenant "updated" event.
     */
    public function updated(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "deleted" event.
     */
    public function deleted(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "restored" event.
     */
    public function restored(Tenant $tenant): void
    {
        //
    }

    /**
     * Handle the Tenant "force deleted" event.
     */
    public function forceDeleted(Tenant $tenant): void
    {
        //
    }

    protected function addSuperadminToTenant($tenant)
    {
        $superadmins = User::whereRelation('roles.name', config('filament-shield.super_admin.name'))->get();
        foreach ($superadmins as $superadmin) {
            $tenant->users()->attach($superadmin->id);
        }
    }
}
