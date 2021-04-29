# laravle 路由 404

route

```
routes/api.php

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/order/ship', 'App\Http\Controllers\OrderController@ship');
```

controller

```
app/Http/Controllers/OrderController.php

<?php

namespace App\Http\Controllers;

use App\Events\OrderShipped;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function ship(Request $request)
    {
        $order = Order::findOrFail($request->id);
        event(new OrderShipped($order));
    }
}
```

请求

```
{{appurl}}/api/order/ship?id=1
```

response  404

原来 Request 会自动鉴权，鉴权失败会跳转到  login，而我没有定义 login 路由，所以 404

需要 重写 authorize()

```
<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\HasJsonBody;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\Api\ValidationException;
use App\Exceptions\Api\UnauthorizedException;
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

}

```

