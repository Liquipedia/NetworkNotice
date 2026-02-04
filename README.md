# NetworkNotice
![Code Style](https://github.com/Liquipedia/NetworkNotice/workflows/Code%20Style/badge.svg)

MediaWiki Extension for Liquipedia

Whilst this extension is open source, it has a close source dependency. If you want to use this extension you will have to patch it to use some other means to compile SCSS.

# Installation
* Install Liquipedias SCSS extension
* Extract the extension folder to extensions/NetworkNotice/
* Add the following line to LocalSettings.php:

```
wfLoadExtension( 'NetworkNotice' );
```

* Add table to DB (can import from networknotice.sql).
