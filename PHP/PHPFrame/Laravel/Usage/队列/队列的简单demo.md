# 队列的简单demo

## references

> https://learnku.com/docs/laravel/5.2/queues/1129#running-the-queue-listener
>
> https://learnku.com/laravel/t/32097

## model

```
$ php artisan make:model podcast
Model created successfully.
```

创建表

```
CREATE TABLE `podcast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'CURRENT_TIMESTAMP',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $connection = '34w';
    protected $table = 'podcast';
}
```

## job

```
$ php artisan make:job ProcessPodcast
Job created successfully.
```

```
<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Podcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessPodcast extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $podcast;

    public function __construct(Podcast $podcast)
    {
        $this->podcast = $podcast;
    }

    public function handle()
    {
        $this->podcast->status = 1;
        $this->podcast->update();
    }
}
```


## Controller

```
$ php artisan make:controller API/PodcastController
Controller created successfully.
```

```
<?php

namespace App\Http\Controllers\Api;

use App\Podcast;
use App\Jobs\ProcessPodcast;
use App\Http\Controllers\Controller;

class PodcastController extends Controller
{
    public function store(  ) {
        $model = new Podcast();
        $model->status = 0;
        $model->save();
        $job = new ProcessPodcast($model);
        $job->delay(10);;
        $this->dispatch($job);
    }
}
```

## routes

在route文件中新增

```
Route::get('queue','Api\PodcastController@store');
```

## 运行

请求api

```
http://www.newadminpk.abc/queue
```

运行命令

```
$ php artisan queue:work
[2020-02-24 17:55:28] Processed: App\Jobs\ProcessPodcast
```

或者监听

```
$ php artisan queue:listen
[2020-02-24 18:23:45] Processed: App\Jobs\ProcessPodcast
[2020-02-24 18:23:46] Processed: App\Jobs\ProcessPodcast
[2020-02-24 18:23:47] Processed: App\Jobs\ProcessPodcast
[2020-02-24 18:23:48] Processed: App\Jobs\ProcessPodcast

```

数据库

```
id	status	updated_at	created_at
1	1	2020-02-24 17:49:06	2020-02-24 17:49:00
7	0	2020-02-24 18:00:52	2020-02-24 18:00:52
8	0	2020-02-24 18:00:54	2020-02-24 18:00:54
```

