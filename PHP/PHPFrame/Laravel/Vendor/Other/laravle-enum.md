# laravle-enum

https://github.com/BenSampo/laravel-enum

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
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ErrorCode extends Enum
{
    const UNAUTHORIZED =   -1001; // token 授权未通过
    const SERVICE_UNAVAILABLE =   -1002; // token 授权未通过

    public static function getDescription($value): string
    {
        if ($value === static::UNAUTHORIZED) {
            return 'token 授权未通过，请重新登录';
        } elseif ($value === static::SERVICE_UNAVAILABLE) {
            return '外部服务不稳定，请重试';
        }

        return parent::getDescription($value);
    }
}
```

```
php artisan enum:annotate
```

```
<?php

namespace App\Http\Controllers\Api\V1\SubUser;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Api\V1\SubUser\Create\IndexRequest;
use App\Services\Api\V1\SubUser\Create;
use App\Enums\Api\ErrorCode;

class CreateController extends BaseController
{
    public function index(IndexRequest $request)
    {
        $a = ErrorCode::UNAUTHORIZED; // -1001
        $enumInstance = new ErrorCode(ErrorCode::UNAUTHORIZED);
        $enumInstance = ErrorCode::fromValue(ErrorCode::UNAUTHORIZED); // same as the constructor,get instance by value

        $enumInstance->key; // UNAUTHORIZED
        $enumInstance->value; // -1001
        $enumInstance->description; // Unauthorized
        $value = (string)$enumInstance; // -1001

        $unauthorized = ErrorCode::fromValue(ErrorCode::UNAUTHORIZED);
        $result = $unauthorized->is(ErrorCode::UNAUTHORIZED); // true
        $result = $unauthorized->is(ErrorCode::UNAUTHORIZED()); // true
        $result = $unauthorized->is(ErrorCode::SERVICE_UNAVAILABLE); // false
        $result = $unauthorized->in([ErrorCode::UNAUTHORIZED, ErrorCode::SERVICE_UNAVAILABLE]); // true


        $descrition = ErrorCode::getDescription(ErrorCode::UNAUTHORIZED); // token 授权未通过，请重新登录
        ErrorCode::getValues(); //
        ErrorCode::getKeys(); //
        ErrorCode::hasKey('UNAUTHORIZED');
        ErrorCode::hasValue(ErrorCode::UNAUTHORIZED); // true
        ErrorCode::getRandomValue(); //
    }
}
```

