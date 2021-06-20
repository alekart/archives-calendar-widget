# Archives Calendar Widget

This is the __dev__ version of __Archives Calendar Widget__ plugin for WordPress and can be
_unstable, broken or with debug messages_ activated

## Install stable release
Use the stable release from the **[WordPress repository](https://wordpress.org/plugins/archives-calendar-widget/)**

## Install a build
### Build WP plugin
You should have nodeJS and NPM installed.

Install dependencies:
```shell
npm install
```
Build the plugin
```shell
npm run build
```
The working version of the plugin should be in the `dist` folder.
Copy the content of the `dist` folder into your wordpress installation `wp-content/plugins/arcwp` then activate it.

### Developing
Start development environment
```shell
npm run serve
```

Edit files in the `src` folder, the working plugin will be available in the `dist` folder.

You can make a symlink to your WordPress installation plugin folder to test it directly.
```shell
ln -s ~/repository-clone/dist /absolute/pathTo/wordpress/wp-content/plugins/arcwp
```

### Testing with PHPUnit

1. Setup local PHP + MySQL environment
   - Configure MySQL to be on localhost with user `root` and password `root`
2. Run `npm run setup-tests` to download WordPress testing libraries and configure the database
3. Run `npm run test` to run test suite.

Note: you might need to edit the `./tests/lib/wp-tests-config.php` file to change the ABSPATH
to your actual absolute path to WordPress folder:
```php
define( 'ABSPATH', '/Users/{USER_NAME}/arcw/tests/lib/wordpress/' );
```
 
## Addons
- __[Popover](https://github.com/alekart/arcwp/)__ 
_Shows a popover with post titles (and links) on day/month hover_

## [Changelog](https://github.com/alekart/arcw/blob/master/CHANGELOG.md)
