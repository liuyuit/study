<?php

namespace liuyuit\SignatureHelper;

class SignatureHelper
{

    /**
     * 计算md5签名
     * @param array $params
     * @param string $secretKey
     * @return  string
     */
    public function sign(array $params, $secretKey)
    {
        $sortString = $this->buildSortString($params);
        $sortString .= $secretKey;
        $signature = md5($sortString);

        return $signature;
    }


    /**
     * 验证签名
     * @param array $params
     * @param $signature
     * @param $secretKey
     * @return bool
     */
    public function verifySignature(array $params, $signature, $secretKey)
    {
        $tmpSign = $this->sign($params, $secretKey);
        return $signature == $tmpSign ? true : false;
    }

    /**
     * 构造排序字符串
     * @param array $params
     * @return string
     */
    public function buildSortString(array $params)
    {
        $params = array_filter($params, function ($v, $k) {
            if ($k !== 'sign' && $k !== 'sign_type') {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_BOTH);

        ksort($params);

        $fieldStr = '';
        foreach ($params as $key => $value) {
            $fieldStr .= $key . '=' . $value . '&';
        }
        return $fieldStr;
    }
}
