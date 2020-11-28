<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/28
 * Time: 14:52
 */

/*$data = [
    'id'  => 123,
    'name'  => '',
    'email' => '',
];
$validator = new Validator($data, [
    'id'    => 'required',
    'name'    => 'isset',
    'email' => 'required',
]);
if ($validator->failed()){
    echo $validator->firstMessage(); // email 不能为空(不能为 0, null, 空字符串, 空数组)
}*/




class Validator
{
    protected $messages = []; // 错误的消息提示。如果为空表示没有错误，此次校验通过
    protected $data = []; // 要校验的数据数组

    public function __construct($data, $rules){
        $this->data = $data;

        foreach ($rules as $key => $rule){

            $this->checkRule($rule, $key);
        }
    }

    /**
     * 检查一对键值对是否符合规则
     * @param $key
     * @param $rule
     * @return bool true ： 符合规则 false： 不符合规则
     */
    protected function checkRule($rule, $key){
        switch($rule){
            case 'isset':

                if (!isset($this->data[$key])){
                    $this->messages[] =  $key . ' 是必传的';
                    return false;
                }

                return true;
                break;
            case 'required':  // no break 这个分支同时作为默认值
            default:

                if (empty($this->data[$key])){
                    $this->messages[] =  $key . ' 不能为空(不能为 0, null, 空字符串, 空数组)';
                    return false;
                }
                return true;
                break;
        }
    }

    public function failed(){
        return !empty($this->messages);
    }

    public function firstMessage(){
        return $this->messages[0];
    }
}