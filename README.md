# kit

## Quickstart
1. [Install docker](https://docs.docker.com/install/)
2. [Install docker-compose](https://docs.docker.com/compose/install/)
3. [Install traefik](https://github.com/mediaten/traefik-v2)

Полная установка приложения в докер 
```
make install
```

Для подключения к рабочему окружению 
```
make env
```

## DEMO

`administrator` role account
```
Login: webmaster
Password: webmaster
```

`manager` role account
```
Login: manager
Password: manager
```

`user` role account
```
Login: user
Password: user
```
## Php worker
Команда просмотра логов воркера

```console
docker-compose logs php-worker
```

```console
docker-compose logs -f php-worker
```
