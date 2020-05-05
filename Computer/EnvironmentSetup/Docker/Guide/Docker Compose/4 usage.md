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
% vim Dockerfile
```

写入以下内容

```
FROM python:3.6-alpine
ADD . /code
WORKDIR /code
RUN pip install redis flask
CMD ["python", "app.py"]
```

#### docker-compose

```
% vim docker-compose.yml
```

写入以下内容

```
version: '3'
services:

  web:
    build: .
    ports:
     - "5000:5000"

  redis:
    image: "redis:alpine"
```

#### 运行

```
% docker-compose up
```

访问

```
http://localhost:5000/

Hello World! 该页面已被访问 2 次。
```

