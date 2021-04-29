# 魔术方法 __callStatis 的使用

## references

> https://blog.csdn.net/weixin_33875839/article/details/94159380

尝试在 laravle 模型中非静态方法，然后用静态方式调用



vim app/Models/Log/UserEquipment.php

```
<?php

namespace App\Models\Log;

use App\Casts\Ip;
use App\Models\HasCompositePrimaryKey;
use App\Models\Model;

class UserEquipment extends Model
{
    use HasCompositePrimaryKey;

    protected $table = 'user_equipment_log';

    protected $fillable = [
        'uid', 'equipment_id', 'gid', 'ip', 'rest',
    ];

    protected $primaryKey = ['uid', 'equipment_id', 'gid'];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'ip' => Ip::class,
        'rest' => 'array',
    ];

    public function existsOrInsert($uid, $equipmentId, $gid, $ip, $rest)
    {
        $where = [
            'uid' => $uid,
            'equipment_id' => $equipmentId,
            'gid' => $gid,
        ];
        $exists = $this->where($where)->exists();

        if ($exists) {
            return;
        }

        $attributes = [
            'ip' => $ip,
            'rest' => $rest,
        ];

        $values = array_merge($where, $attributes);

        $this->create($values);
    }
}
```

call

```
UserEquipment::existsOrInsert($request->uid, $request->equipment_id, $request->gid, $request->getClientIp(), $request->rest);
```

error

```
Non-static method App\\Models\\Log\\UserEquipment::existsOrInsert() should not be called statically
```

原来只有在用静态方式调用一个不可访问的方法时，__callStatic 才会生效

所以方法签名要改成

```
public function existsOrInsert($uid, $equipmentId, $gid, $ip, $rest)
```

