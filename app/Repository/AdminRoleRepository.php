<?php
declare(strict_types=1);

namespace App\Repository;

use App\Models\AdminRole;
use App\Models\Role;
use App\Models\Rule;
use Illuminate\Support\Facades\Cache;

class AdminRoleRepository
{
    const ADMIN_ROLE_CACHE_KEY = 'admin_role_';

    public function __construct(
        protected readonly AdminRole $adminRoleModel
    ){

    }

    /**
     * @param $adminId
     * @return mixed
     */
    public function getAdminRole($adminId)
    {
        return Cache::remember(self::ADMIN_ROLE_CACHE_KEY . $adminId, 3600, function () use ($adminId) {
            return $this->adminRoleModel->firstByFeild('admin_id', $adminId);
        });
    }
}
