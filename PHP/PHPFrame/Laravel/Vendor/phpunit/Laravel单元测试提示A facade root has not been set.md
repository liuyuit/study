# Laravel单元测试提示A facade root has not been set

## references

> http://www.gqz666.cn/article/137.html

修改 use PHPUnit\Framework\TestCase; 为 use Tests\TestCase;

或者 use CreatesApplication 这个 trait

phpunit.xml 加上

```
    <extensions>
        <extension class="Tests\Bootstrap"/>
    </extensions>
```

