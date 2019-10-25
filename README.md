# Nuvole training site

Destination Drupal8 site for the DrupalCon Amsterdam 2019 migration training.

## Branches

* `Master` contains the basic site installation
* `MIGRATIONS` contains the migration setup, configuration and code

## Setup

1. Check out the `MIGRATIONS` branch
2. Run the following commands:

```
$ docker-compose up -d
$ docker-compose exec php composer install
$ docker-compose exec php ./vendor/bin/run drupal:site-install
```

Go to [http://localhost:8080](http://localhost:8080) and you have a Drupal site running with the module and its dependencies!

To run the migrations, download the JSON files into the `sites/default/files/migrations` folder so that an individual JSON file would be located at `sites/default/files/migrations/node/blog/und`.

Then you can run the migrate commands:

```
$ docker-compose exec php migrate:status
$ docker-compose exec php migrate:import blog --update
```

Or to rollback:

```
$ docker-compose exec php migrate:rollback blog
```
