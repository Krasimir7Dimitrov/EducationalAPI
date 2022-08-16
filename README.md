# Usage
- To start the project go to its folder and run in terminal
```sh
$ docker compose up -d
```
The first time it will take some time so don't worry :)

- To use other services like composer or npm you can use:
```sh
$ docker compose run --rm npm
```
or
```sh
$ docker compose run --rm composer
```