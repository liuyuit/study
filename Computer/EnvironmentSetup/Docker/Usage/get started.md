# get started

> https://docs.docker.com/get-started/

## Orientation and setup

#### Test doker version

```
liuyu@usercomputerdeMacBook-Air bulletin-board-app % docker --version
Docker version 19.03.5, build 633a0ea
```

#### Test docker intallation

```
docker run hello-world
```

```
liuyu@usercomputerdeMacBook-Air ~ % docker ps -all
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS                      PORTS               NAMES
ea0a13691185        hello-world         "/hello"            41 seconds ago      Exited (0) 40 seconds ago                       practical_nobel
```

## build and run your image

#### Git

Clone the example project from github

```
git clone https://github.com/dockersamples/node-bulletin-board
cd node-bulletin-board/bulletin-board-app
```

#### Define a container with Dockerfile

```
# Use the official image as a parent image
FROM node:current-slim

# Set the working directory
WORKDIR /usr/src/app

# Copy the file from your host to your current location
COPY package.json .

# Run the command inside your image filesystem
RUN npm install

# Inform Docker that the container is listening on the specified port at runtime.
EXPOSE 8080

# Run the specified command within the container.
CMD [ "npm", "start" ]

# Copy the rest of your app's source code from your host to your image filesystem.
COPY . .
```

#### Build and test you image

```
cd node-bulletin-board/bulletin-board-app
```

```
docker build -t bulletinboard:1.0 .
```

#### Run your image as a container

Start a conainer based on your new image 

```
docker container run --publish 8000:8080 --detach --name bb bulletinboard:1.0
```

Visit your application in a brwser at `localhost:8000`

You can delete it 

```
docker container rm --force bb
```

The `--force` option remove the runing container.

## Share images on docker hub

#### setup on you docker hub

- visit the docker hub homepage http://hub.docker.com
- click on the docker icon ,and click sign in
- you can do the same thing from your command line by typing `docker login`.

#### Create a docker hub repository and push your image

fill out the repository names as buletinboard,and navigate to Repositories->create

```
docker image tag bulletinboard:1.0 gordon/bulletinboard:1.0
```

```
docker image push gordon/bulletinboard:1.0
```

