# Laravle 构建统一的 API 响应和异常处理

## references

> https://learnku.com/docs/laravel/7.x/responses/7463#json-responses
>
> https://learnku.com/docs/laravel/7.x/requests/7462#old-input
>
> https://learnku.com/docs/dingo-api/2.0.0/Errors-And-Error-Responses/1447

#### 技术栈

- Laravel Framework 7.29.3
- dingo/api  3.0

```
composer require dingo/api 3.0
```

## response

#### helper

vim app/Helpers/response.php

```
<?php

if (!function_exists('apiSuccess')) {
    function apiSuccess($data = [], $msg = '成功', $headers = []): \Illuminate\Http\JsonResponse
    {
        return apiResponse($msg, 0, $data, $headers);
    }
}

if (!function_exists('apiError')) {
    function apiError($msg = '失败', $code = -1, $data = [], $headers = []): \Illuminate\Http\JsonResponse
    {
        return apiResponse($msg, $code, $data, $headers);
    }
}

if (!function_exists('apiResponse')) {
    function apiResponse($msg, $code, $data, $headers): \Illuminate\Http\JsonResponse
    {
        $response = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        return response()
            ->json($response)
            ->withHeaders($headers)
            ->withCallback(request('callback'));
    }
}
```

#### route

vim routes/api/v1.php

```
<?php
/* @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');

$globalParams = [
    'domain' => config('app.api_url'),
    'namespace' => 'App\\Http\\Controllers\\Api\\V1',
    'version' => 'v1',
];

$api->group($globalParams, function () use ($api) {
    // 基础上报
    $api->group(['prefix' => 'report'], function () use ($api) {
        $api->post('active', 'Report\ActiveController@index')->name('api.active');
    });
});
```

#### controller

```
php artisan make:controller Api/V1/Report/Active
```

```
<?php

namespace App\Http\Controllers\Api\V1\Report;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Api\V1\Report\Active\IndexRequest;

class ActiveController extends BaseController
{
    public function index(IndexRequest $request)
    {
        return apiError('testtst', -1, [], ['X-Header-Two' => 'Header Value',]);
    }
}
```

result

```
{
    "code": -1,
    "msg": "testtst",
    "data": []
}
```

## Enum

```
 php artisan make:enum Api/ErrorCode
```

```
<?php

namespace App\Enums\Api;

use BenSampo\Enum\Enum;

/**
 * 有特定意义的错误码
 *
 * @method static static UNAUTHORIZED()
 * @method static static SERVICE_UNAVAILABLE()
 */
final class ErrorCode extends Enum
{
    const UNAUTHORIZED =   -1001; // token 授权未通过
    const SERVICE_UNAVAILABLE =   -1002; // 外部服务不稳定
    const NOT_FOUNT =   -1003; // 资源未找到
    const VALIDATION =   -1004; // 表单验证失败
    const DEFAULT =   -2; // 默认，无特殊意义

    public static function getDescription($value): string
    {
        if ($value === static::UNAUTHORIZED) {
            return 'token 授权未通过，请重新登录';
        } elseif ($value === static::SERVICE_UNAVAILABLE) {
            return '外部服务不稳定，请重试';
        } elseif ($value === static::NOT_FOUNT) {
            return '资源未找到，请重试';
        } elseif ($value === static::VALIDATION) {
            return '表单验证失败, 请检查输入';
        }

        return parent::getDescription($value);
    }
}
```

## Exception

```
php artisan make:exception  Api/BaseException
```

```
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 3/3/2021
 * Time: 10:58 AM
 */

namespace App\Exceptions\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseException extends HttpException
{
    public function __construct(string $message = null, int $code = 0, int $statusCode = -2, array $headers = [], \Throwable $previous = null)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
```

```
php artisan make:exception  Api/ValidationException
```

```
<?php

namespace App\Exceptions\Api;

use App\Enums\Api\ErrorCode;

class ValidationException extends BaseException
{
    public function __construct(string $message = null, int $code = 0, array $headers = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, ErrorCode::VALIDATION, $headers, $previous);
    }
}
```

```
php artisan make:exception Api/NotFoundException
```

```
<?php

namespace App\Exceptions\Api;

use App\Enums\Api\ErrorCode;

class NotFoundException extends BaseException
{
    public function __construct(string $message = '未找到资源', int $code = 0, array $headers = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, ErrorCode::NOT_FOUNT, $headers, $previous);
    }
}
```

```
php artisan make:exception Api/UnauthorizedException
```

```
<?php

namespace App\Exceptions\Api;

use App\Enums\Api\ErrorCode;

class UnauthorizedException extends BaseException
{
    public function __construct(string $message = '授权未通过，请重新登录', int $code = 0, array $headers = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, ErrorCode::UNAUTHORIZED, $headers, $previous);
    }
}
```



#### provider

```
php artisan make:provider ExceptionHandlerServiceProvider
```

```
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExceptionHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app('Dingo\Api\Exception\Handler')->register(function (\App\Exceptions\Api\BaseException $exception) {
            if (!config('app.debug')) {
                return apiError($exception->getMessage(), $exception->getStatusCode());
            }

            $debug = [
                'line'  => $exception->getLine(),
                'file'  => $exception->getFile(),
                'tract'  => $exception->getTrace(),
            ];

            return apiError($exception->getMessage(), $exception->getStatusCode(), ['debug' => $debug]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
```

vim config/app.php

```
    'providers' => [
		.......
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\ExceptionHandlerServiceProvider::class,

    ],
```

#### request

```
php artisan make:request Api/V1/Request
```

```
<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\HasJsonBody;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\Api\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    use HasJsonBody;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator->errors()->first());
    }
}
```

vim app/Http/Requests/HasJsonBody.php

```
<?php

namespace App\Http\Requests;

trait HasJsonBody
{
    /**
     * 将请求体的json作为验证对象
     *
     * @param null $keys
     * @return array
     */
    public function all($keys = null): array
    {
        $_array = parent::all($keys);
        $array = empty($keys) ? parent::json()->all() : collect(parent::json()->all())->only($keys)->toArray();
        return array_merge($_array, $array);
    }
}
```

app/Http/Requests/Api/V1/Report/Active/IndexRequest.php

```
php artisan make:request Api/V1/Report/Active/IndexRequest
```

```
<?php

namespace App\Http\Requests\Api\V1\Report\Active;

use App\Enums\PlatformType;
use App\Http\Requests\Api\V1\Request;
use BenSampo\Enum\Rules\EnumValue;

class IndexRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gid' => 'required',
            'lid' => 'nullable',
            'platform' => [
                'required',
                new EnumValue(PlatformType::class, false)
            ],
            'equipment_id' => 'required',
            'equipment_idfv' => 'required',
            'rest' => 'required',
        ];
    }
}
```

query

http://api.cps.ggxx.local/report/active?gid=1

result

```
{
    "code": -1,
    "msg": "platform 不能为空。",
    "data": []
}
```

exception

```
throw new UnauthorizedException();

result
{
    "code": -1001,
    "msg": "授权未通过，请重新登录",
    "data": {
    }
}    
```

