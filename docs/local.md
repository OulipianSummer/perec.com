# Local Development

To run locally, install ddev, clone this repo and run the following commands.

Note: you must create your own hash salt and place it in
`$APP_ROOT/secrets/salt.txt`

``` bash
ddev start
ddev composer install
```

Then, visit your local site and go through the Drupal install process.

Once you are through with installation, you can run `ddev drush cim`

To import the default configuration.
