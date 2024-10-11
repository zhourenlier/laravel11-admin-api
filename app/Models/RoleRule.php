<?php
declare(strict_types=1);

namespace App\Models;

class RoleRule extends BaseModel
{
    protected $guarded = ['role_id'];

    /**
     * @param Role $role
     * @param array $ruleIds
     * @return void
     */
    public function roleRuleSync(Role $role, array $ruleIds)
    {
        $role->rules()->sync($ruleIds);
    }
}
