# build single mysql

## references

> https://hub.docker.com/_/mysql

docker-compose.yml

```
version: '3.1'
services:
  db:
    image:mysql:5.7
    command: --default-authentication-plugin=mysql_native_password
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: learn_mysql#123
    ports:
      - 33060:3306
```

