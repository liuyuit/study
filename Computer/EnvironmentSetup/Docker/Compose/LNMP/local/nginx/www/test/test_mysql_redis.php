<?php

$db = new PDO('mysql:host=mysql:3306;dbname=test', 'root', '123456');

try {

    foreach ($db->query('select * from test') as $row){

        print_r($row);

    }

    $db = null; //关闭数据库

} catch (PDOException $e) {

    echo $e->getMessage();

}


//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('redis', 6379);
//$redis->auth('my pass');
echo "Connection to server successfully";
//设置 redis 字符串数据
$redis->set("tutorial-name", "Redis tutorial");
// 获取存储的数据并输出
echo "Stored string in redis:: " . $redis->get("tutorial-name");
