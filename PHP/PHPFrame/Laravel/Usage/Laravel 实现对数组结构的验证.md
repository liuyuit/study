# Laravel 实现对数组结构的验证

```
<?php

namespace App\Services\Pay\Sdk;

use App\Exceptions\Pay\InvalidArgumentException;
use Illuminate\Support\Facades\Validator;

/**
 * 支付SDK的统一返回值
 *
 * @property string url
 */
class PreOrderResult implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $rules = [
        'url' => 'bail|required_without:html|string',
        'html' => 'bail|required_without:url|string',
    ];

    /**
     * Output constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->validate();
        $this->data['type'] = !empty($data['url']) ? 'url' : 'html';
        $this->data['result'] = $data['url'] ?? $data['html'];
    }

    /**
     * 验证结构体
     */
    protected function validate()
    {
        $validator = Validator::make($this->data, $this->rules);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors()->first());
        }
    }

    /**
     * Determine if the given item exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Get the item at the given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Set the item at the given offset.
     *
     * @param $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Unset the item at the given key.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
```

use

```
    /**
     * 下单
     *
     * @param array $order
     * @return mixed|PreOrderResult
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index(array $order): PreOrderResult
    {
        $this->initOrder($order);

        $url = IPayNow::ali($this->config)->pre($order);

        $result = new PreOrderResult([
            'url' => $url,
        ]);

        return $result;
    }
```

可以在用数组作为函数的参数或返回值时保证数组的接口

```
// 请求网关进行预下单
        /** @var PreOrderResult $preOrderResult */
        $preOrderResult = $sdk->index($orderData);
```

```
function test(PreOrderResult $preOrderResult) {

}
```

