# docker入门教程

## reference

> http://www.ruanyifeng.com/blog/2018/02/docker-tutorial.html

## 制作自己的docker容器

#### 下载源码

```
https://github.com/liuyuit/koa-demos.git
```

#### dockerfile文件

在根目录中写入文件`.dockerignore`

```
.git
node_modules
npm-debug.log
```

表示在打包image文件时忽略这些文件

`Dockerfile`文件写入

```
FROM node:8.4
COPY . /app
WORKDIR /app
RUN npm install  --registry==https://registry.npm.taobao.org
EXPOSE 3000
```

> `FROM node:8.4`表示image基于node
>
> `COPY . /app` 将当前目录下的文件复制到image的app目录下（除了.dockerignore忽略的文件）
>
> `WORKDIR /app` app目录将作为工作目录
>
> `RUN npm install` 执行npm的依赖安装命令，安装的依赖都会打进image文件中
>
> `EXPOSE 3000`对外暴露3000端口

#### 打包image文件

```
docker image build -t koa-demo:0.0.1 .
```

`-t`表示给这个image打上名字和标签 

`.`表示Dockerfile文件所在的目录

#### 生成容器

```
$ docker container run -p 8000:3000 --rm -it koa-demo:0.0.1 /bin/bash
```

> -p 容器的3000端口映射到本机的8000端口
>
> -i 容器的shell映射到当前的shell，本机的窗口输入的命令，会进入到容器中执行
>
> --rm 容器终止运行后，自动删除该容器
>
> /bin/bash  容器启动后，内部执行的第一个命令，保证用户可以使用shell

执行完成后会进入容器的shell界面，然后再执行一个命令

```
root@68f237903f27:/app# node demos/01.js
```

访问 `http://127.0.0.1:8000/`。

响应 not found是因为没有定义路由。

执行 ctrl +c 退出执行，执行exit退出容器。

#### CMD命令

前面一种方式需要手动输入 npm命令，我们可以把这个写入Dockerfile中

```
FROM node:8.4
COPY . /app
WORKDIR /app
RUN npm --registry https://registry.npm.taobao.org install
EXPOSE 3000
CMD node demos/01.js
```

> 这种方式，不能在执行docker run的时候后接shell命令。
>
> CMD和RUN的区别是，run执行后的修改会写入到image文件中，而CMD产生的改动只会影响当前运行的容器。

只能以这种方式执行

```
docker container run -p 8000:3000 --rm -it koa-demo:0.0.1
```

这个命令执行后会直接进入到容器中，但是不能输入shell命令，也没有任何输出。只能通过ctrl + c退出容器。

