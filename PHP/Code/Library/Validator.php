<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/28
 * Time: 14:52
 */

/**
$data = [
'id'  => 123,
'name'  => '',
'age' => 21,
];
$validator = new Validator($data, [
'id'    => 'required',
'name'    => 'isset',
'age' => 'int|max:20',
]);
if ($validator->failed()){
echo $validator->firstMessage(); // age 不能大于 20
}
 */



class Validator
{
    protected $messages = []; // 错误的消息提示。如果为空表示没有错误，此次校验通过
    protected $data = []; // 要校验的数据数组

    public function __construct($data, $rules){
        $this->data = $data;


        foreach ($rules as $key => $roleGroup){
            $rules = explode('|', $roleGroup); //  $roleGroup 的值可以是 'int|max:10' ，表示必须是 int 并且最大值为 10。
            foreach ($rules as $rule){
                $this->checkRule($rule, $key);
            }
        }
    }

    /**
     * 检查一对键值对是否符合规则
     * @param $key string 要校验的参数值， data 数组的索引。
     * @param $rule string 校验的规则标识，如 isset 、 max:10 等
     * @return bool true ： 符合规则 false： 不符合规则
     */
    protected function checkRule($rule, $key){
        $ruleArr = explode(':', $rule); // rule 可以是 max:10，表示最大值是 10
        $ruleKey = $ruleArr[0]; // 如果 rule 是 max:10，则 ruleKey 为 max
        $ruleParam = isset($ruleArr[1]) ? $ruleArr[1] : null; // 如果 rule 是 max:10，则 ruleParam 为 10

        switch($ruleKey){
            case 'isset':

                if (!isset($this->data[$key])){
                    $this->messages[] =  $key . ' 是必传的';
                    return false;
                }

                return true;
                break;
            case 'int':
                if (!is_int($this->data[$key])){
                    $this->messages[] =  $key . ' 必须是 int';
                    return false;
                }

                return true;
                break;
            case 'numeric':
                if (!is_numeric($this->data[$key])){
                    $this->messages[] =  $key . ' 必须是数值型';
                    return false;
                }

                return true;
                break;
            case 'min':
                if ($this->data[$key] < $ruleParam){
                    $this->messages[] =  $key . ' 不能小于 ' . $ruleParam;
                    return false;
                }

                return true;
                break;
            case 'max':
                if ($this->data[$key] > $ruleParam){
                    $this->messages[] =  $key . ' 不能大于 ' . $ruleParam;
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

    public function messages(){
        return $this->messages;
    }
}