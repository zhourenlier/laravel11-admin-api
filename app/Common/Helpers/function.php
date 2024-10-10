<?php
use App\Common\Enum\CommonCode;
use App\Common\Enum\AdminCode;
use Illuminate\Support\Facades\Cache;

if (!function_exists('responseSuccess')) {
    /**
     * @param array $data
     * @return mixed
     */
    function responseSuccess($data = [])
    {
        $newCode = new AdminCode(CommonCode::SUCCESS_CODE);
        $return = [
            'code' => $newCode->getCode(),
            'msg' => $newCode->getMsg(),
            'data' => $data
        ];

        if ($data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            $data = $data->toArray();
            $data_page = [
                'current_page' => (int)$data['current_page'],
                'last_page' => (int)$data['last_page'],
                'per_page' => (int)$data['per_page'],
                'total' => (int)$data['total'],
                'data' => $data['data'],
            ];

            $return['data'] = $data_page;
        }

        return response()->json($return, 200);
    }
}


if (!function_exists('responseError')) {
    /**
     * @param int $httpCode
     * @param array $errs
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    function responseError($errs = [], int $httpCode = 500, $data = [])
    {
        $newCode = new AdminCode($errs["code"] ?? CommonCode::ERROR_CODE, $errs["msg"] ?? "");
        $return = [
            'code' => $newCode->getCode(),
            'msg' => $newCode->getMsg(),
            'data' => $data
        ];
        return response()->json($return, $httpCode);
    }
}


if (!function_exists('resultSuccess')) {
    /**
     * Success data
     * @return Arrar
     */
    function resultSuccess(array $data = [])
    {
        return [
            'result' => true,
            'data' => $data
        ];
    }
}

if (!function_exists('resultError')) {
    /**
     * Error data
     * @return Arrar
     */
    function resultError(array $data = [])
    {
        return [
            'result' => false,
            'data' => $data
        ];
    }
}

if (!function_exists('getRandomString')) {
    /**
     * 随机生成字符
     * @param $len
     * @return string
     */
    function getRandomString($len)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        mt_srand(10000000 * (double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}

if (!function_exists('checkResult')) {
    /**
     *
     * @param $result
     * @return bool
     */
    function checkResult($result){
        foreach ($result as $v) {
            if (!$v || empty($v)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('checkPassword')) {
    /**
     * 验证密码格式
     * @param $result
     * @return bool
     */
    function checkPassword($password){
        if(preg_match("/^[a-zA-Z0-9\_\-\.\,]{6,16}$/", $password)){
            return true;
        }
        return false;
    }
}

if (!function_exists('checkRegexFormat')) {
    /**
     * 字段正则验证格式
     * @param $result
     * @return string
     */
    function checkRegexFormat($field){
        switch ($field){
            case 'password':
                return "/^[a-zA-Z0-9\_\-\.\,]{6,16}$/";
            case 'string':
                return "/^[0-9a-zA-Z@\_\-\.\,\s]+$/";
            case 'email':
                return "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
            case 'color':
                return "/^#[0-9a-zA-Z]+$/";
            default:
                return "";
        }
    }
}

if (!function_exists('cacheData')) {
    /**
     * 缓存数据
     * @param $key
     * @param $timer 单位：秒
     * @return string
     */
    function cacheData($key, $timer = 60){
        if(Cache::has($key) && Cache::get($key) > time()){
            return false;
        }
        Cache::put($key, time()+$timer, $timer-1); //缓存5分钟
        return true;
    }
}
