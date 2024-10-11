<?php
declare(strict_types=1);

namespace App\Repository;


use App\Models\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RuleRepository
{

    const LOG_RULES_CACHE_KEY = 'admin_save_log_rules';

    public function __construct(
        protected readonly Rule $ruleModel
    ){
    }

    /**
     * 获取规则
     * @return array
     */
    public function getRules()
    {
        $rules = $this->ruleModel->getAllOrderBySort();
        return $this->tree($rules);
    }

    /**
     * 树形结构
     * @param $rules
     * @param $pid
     * @return array
     */
    private function tree(&$rules, $pid = 0)
    {
        $arr = [];
        foreach ($rules as $k => $v) {
            if ($v->pid == $pid) {
                $v["children"] = self::tree($rules, $v['id']);

                $arr[] = $v;
                unset($rules[$k]);
            }
        }
        return $arr;
    }

    /**
     * 记录日志的权限
     * @param bool $isNew
     * @return mixed
     */
    public static function getLogSaveRules(bool $isNew){
        $key = self::LOG_RULES_CACHE_KEY;
        if($isNew){
            Cache::delete($key);
        }

        return Cache::remember($key, 3600, function() {
            return Rule::query()
                ->where('is_log', 1)
                ->where('rule', "<>","")
                ->select('rule')
                ->get();
        });
    }

}
