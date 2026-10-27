core2
=====
PHP framework for business applications.

Minimum Server Requirements
---------------------------

* PHP 7.4 or greater
* PDO PHP extension
* MySQL or PostgreSQL Database
* composer for dependencies


Installation
------------
1. Put the source code into *core2* folder anywhere on your server.
2. Create MySQL schema with [db.sql](db.sql)
3. Create *admin* user with the same password.
 ```sql
  INSERT INTO `core_users` (`u_login`, `u_pass`, `visible`, `is_admin_sw`, `date_added`) VALUES ('admin', 'ad7123ebca969de21e49c12a7d69ce25', 'Y', 'Y', NOW());
  ```

4. Create *index.php* file anywhere inside the document root. Make sure that *core2* folder is available from its place.
 ```php
  try {
  	require_once("core2/inc/classes/Error.php");
  	require_once("core2/inc/classes/Init.php");
 
  	$init = new Init();
  	$init->checkAuth();
 
  	echo $init->dispatch();
  } catch (Exception $e) {
  	\Core2\Error::catchException($e);
  }
 ```
5. Create *conf.ini* file near the index.php
 
 ```ini
  [production]
  database.params.host      = localhost
  database.params.port      = 3306
  database.params.dbname    = <database name>
  database.params.username  = <database user>
  database.params.password  = <user password>
 ```

Docker
------
Run the project locally with Docker Compose.

Requirements: Docker with Compose v2, free ports 8080 and 3306.

1. Start the containers:
 ```bash
  docker compose up -d --build
 ```
2. Wait for the first start to finish (`docker compose logs -f app`). The container:
   * runs `composer install` if *vendor* is missing;
   * downloads the UI theme into *html/default* if it is missing (from [core2-front-default](https://github.com/easterism/core2-front-default), set `THEME_REPO` to use another repository).
3. Open http://localhost:8080 and log in with `admin` / `admin`. Change the password after the first login.

Services:

| Service | What | Access |
|---------|------|--------|
| `app` | Apache + PHP 8.2 | http://localhost:8080 |
| `db` | MySQL 8.4 | `localhost:3306`, database `core2`, user `core2`, password `core2` |
| `gearmand` | Gearman job server | `127.0.0.1:4730` inside `app` |
| `redis` | Redis for the *Eventer* worker | `127.0.0.1:6379` inside `app` |
| `worker` | Gearman workers (`worker.php`) | — |

Ports can be changed with `APP_PORT` and `DB_PORT` env variables, e.g. `APP_PORT=8081 docker compose up -d`.
When you open the app on a host other than `localhost` or `127.0.0.1`, add a section for it to [app/conf.ini](app/conf.ini) (the config section is selected by the host name).

On the first start of the database it is created from [db.sql](db.sql), the *admin* user is added and the [cities](mod/cities) module is installed.
SQL scripts run only when the database volume is empty — to apply them again remove the volume with `docker compose down -v`.

Layout inside the container:
* [app](app) — *index.php*, *conf.ini*, *.htaccess* of the application (`/var/www/html`)
* repository root — the *core2* folder (`/var/www/html/core2`)
* [mod/cities](mod/cities) — the *cities* module (`/var/www/html/mod/cities`)

Useful commands:
 ```bash
  docker compose logs -f app worker              # logs
  docker compose exec app bash                   # shell inside the app container
  docker compose exec db mysql -ucore2 -pcore2 core2   # MySQL console
  docker compose exec app rm -rf /var/www/html/cache/*  # clear the cache (e.g. after changing core_modules)
  docker compose down                            # stop
  docker compose down -v                         # stop and remove the database data
 ```

`gearmand`, `redis` and `worker` share the network of `app`, so recreate them together with it: run `docker compose up -d` for the whole stack.
Sessions are stored inside the `app` container, after recreating it log in again.

Usage
-----
Open URL of new index.php file in your browser. Use 'admin' username and 'admin' password.

Support
-------
<img src="phpStorm.png"/>
<a href="https://www.jetbrains.com/phpstorm/" target="_blank">PhpStorm</a>
