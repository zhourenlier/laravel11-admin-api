<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 验证表单规则
     * @param array $params
     * @param array $rules
     * @param array $messages
     * @return mixed|null
     */
    protected function validateParams(array $params, array $rules, array $messages)
    {
        $validator = Validator::make($params, $rules, $messages);
        if ($validator->fails()) {
            $bags = $validator->getMessageBag()->toArray();
            foreach ($bags as $bag) {
                foreach ($bag as $item) {
                    return $item;
                }
            }
        }
        return null;
    }
}
