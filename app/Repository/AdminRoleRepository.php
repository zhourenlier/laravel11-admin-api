<?php

namespace App\Repository;

use App\Models\AdminRole;
use App\Models\Role;
use App\Models\Rule;
use Illuminate\Support\Facades\Cache;

class AdminRoleRepository
{
    const ADMIN_ROLE_CACHE_KEY = 'admin_role_';

    /**
     * @param $adminId
     * @return mixed
     */
    public static function getAdminRole($adminId)
    {
        return Cache::remember(self::ADMIN_ROLE_CACHE_KEY . $adminId, 3600, function () use ($adminId) {
            return AdminRole::query()
                ->where('admin_id', $adminId)
                ->first();
        });
    }
}
