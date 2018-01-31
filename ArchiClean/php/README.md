# Archi Clean : PHP

## Requirements
- PHP >=5.5.0
- [Composer (global install is recommended)](https://getcomposer.org/doc/00-intro.md) 

## Dependencies
Install dependencies by running:
```bash
cd /path/to/the/php/directory
composer install
```
*If composer is intalled locally, you need to replace `composer` by `php composer.phar`* 

## Tests execution
```
composer test
```
*Tests do not need the application to actually run on a server to work* 


## Run the application on a local server

```
composer start
```
*By default, the local server listens on `localhost:9090`, you may want to change it in the `composer.json` file (under the `scripts` section)* 