# Serialization of 'Closure' is not allowed

## references

> https://stackoverflow.com/questions/49157861/laravel-jobs-serialization-of-closure-is-not-allowed
>
> https://www.cnblogs.com/mzli/p/12572780.html

```
vim app/Http/Controllers/TestController.php
```

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Jobs\Society\SyncCdn;


class TestController extends Controller
{

    public function test(Request $request)
    {
        $this->dispatch(new SyncCdn($request));
        return success();
    }
}

```

```
vim app/Http/Controllers/TestController.php
```

```
<?php

namespace App\Jobs\Society;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use App\Dao\Sociaty\GameadvoperateDao;

class SyncCdn extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
//        parent::__construct();
    }

    /**
     * Execute the job.
     *
     * @param Request $request
     * @return bool
     */
    public function handle()
    {
        ini_set('max_execution_time', '0');
        $columns = array('id', 'gid', 'ctype', 'gver', 'cver', 'aid', 'atype', 'url', 'state', 'down_url', 'down_url_r');
        $data = array_only($this->request->all(), $columns);

        if ($data['state'] != 3) {
            return false;
        }

        $exec_command = config('system.upload_cdn_command');
        $exec_command .= $data['down_url_r'];
        $exec_command .= ' -l ' . $data['url'];
        $exec_command .= ' -a ' . '0';      //game_adv表的id，公会不存储game_adv数据
        $exec_command .= ' -o ' . $data['id'] . ' 1>/dev/null';
        //锁定上传cdn数据，等待上传完成
        $Gameadvoperatedao = new GameadvoperateDao();
        $checkData['id'] = $data['id'];
        $Gameadvoperatedao->saveUpdate($data['id'], ['state' => 9]);
        \Log::info('分包CDN存储命令1-' . $exec_command);
        exec($exec_command);
        return true;
    }
}
```

result 

```
Serialization of 'Closure' is not allowed
```

laravel 的文档是这样说的

> 注意，在这个例子中，我们在任务类的构造器中直接传递了一个 Eloquent 模型。因为我们在任务类里引用了 SerializesModels 这个 trait，使得 Eloquent 模型在处理任务时可以被优雅地序列化和反序列化。如果你的队列任务类在构造器中接收了一个 Eloquent 模型，那么只有可识别出该模型的属性会被序列化到队列里。
>

可以给任务类传一个模型，模型的序列化实际上是序列化了模型的主键，在执行任务的时候会通过主键再生成模型实例。但是其他的对象不能传入。

所以可以改成

```
class TestController extends Controller
{

    public function test(Request $request)
    {
        $this->dispatch(new SyncCdn($request->all()));
        return success();
    }
}

```

