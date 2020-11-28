<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/28
 * Time: 11:38
 */
// Output::success(['id' => 12, 'name' => 'Jack']);  //{"code":0,"msg":"success","data":{"id":12,"name":"Jack"}}



class Output
{
    public static function success($data = [], $msg = 'success', $code = 0, $headers = [], $is_exit = true){
        $response = [
            'code'  => $code,
            'msg'  => $msg,
            'data'  => $data,
        ];

        static::outJson($response, $headers, $is_exit);
    }

    public static function error($msg = 'error', $code = -1, $data = [], $headers = [], $is_exit = true){
        $response = [
            'code'  => $code,
            'msg'  => $msg,
            'data'  => $data,
        ];

        static::outJson($response, $headers, $is_exit);
    }

    /**
     * @param $responseBody array
     * @param $headers array
     * @param $is_exit bool
     */
    public static function outJson($responseBody, $headers, $is_exit){
        static::responseHeaders($headers);
        header('Content-Type: application/json');

        $responseBody = json_encode($responseBody);
        if (!empty($_REQUEST['callback'])){
            echo $_REQUEST['callback'] . '(' . $responseBody . ')';
        } else {
            echo $responseBody;
        }

        $is_exit && exit;
    }

    public static function responseHeaders($headers){
        foreach ($headers as $key => $header){
            header($key . ': ' . $header);
        }
    }
}