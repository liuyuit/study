# Usage

## references

> https://github.com/yeasy/docker_practice/blob/master/compose/usage.md

## 术语

- sevice ,一个应用容器
- Project, 一组关联的应用容器，用于联合提供某种服务

## example

下面用 python 来做一个能记录页面访问次数的 web 服务

#### web

```
% mkdir python_web
% cd python_web
% vim app.py
```

写入以下内容

```
from flask import Flask
from redis import Redis

app = Flask(__name__)
redis = Redis(host='redis', port=6379)

@app.route('/')
def hello():
    count = redis.incr('hits')
    return 'Hello World! 该页面已被访问 {} 次。\n'.format(count)

if __name__ == "__main__":
    app.run(host="0.0.0.0", debug=True)
```

#### Dockerfile

```

```

