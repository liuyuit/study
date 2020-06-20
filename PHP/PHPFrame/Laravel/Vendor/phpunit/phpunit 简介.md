# phpunit

## references

> https://learnku.com/docs/laravel/7.x/testing/7505
>
> https://learnku.com/articles/17529

```
composer create-project --prefer-dist laravel/laravel phpunit
```

```
% artisan make:test UserTest
```

编写测试用例

```
% vim tests/Unit/UserTest.php

<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(1);
    }
}
```

执行测试

```
% php artisan test

   PASS  Tests\Unit\ExampleTest
  ✓ basic test

   FAIL  Tests\Unit\UserTest
  ✕ example

  Tests:  1 failed, 1 passed, 3 pending

  Failed asserting that 1 is true.

  at tests/Unit/UserTest.php:16
    12|      * @return void
    13|      */
    14|     public function testExample()
    15|     {
  > 16|         $this->assertTrue(1);
    17|     }
    18| }
    19|
```

