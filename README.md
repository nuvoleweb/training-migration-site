# training-migration-site
Destination Drupal8 site for the DrupalCon Amsterdam 2019 migration training.

## Development setup

Run the following commands:

```
$ docker-compose up -d
$ docker-compose exec php composer install
$ docker-compose exec php ./vendor/bin/run drupal:site-install
```

Go to [http://localhost:8080](http://localhost:8080) and you have a Drupal site running with the module and its dependencies!
