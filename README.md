Yii-less
========

This extension allows you to register your less files and they will be compiled to CSS with the option to cache.

Installation
------------

Copy the yii-less folder into the extensions folder of you app.

Usage
----

In your config file register the extension:
```php
'behaviors'=>array(
      'ext.yii-less.components.LessCompilationBehavior',
),
```
In your config file register your less files to precompile:
```php
'components'=>array(
  'lessCompiler'=>array(
    'class'=>'ext.yii-less.components.LessCompiler',
    'paths'=>array(
      // you can access to the compiled file on this path
      'css/bootstrap.css' => array(
        'precompile' => true, // whether you want to cache the generation
        'paths' => array('less/bootstrap.less') //paths of less files. you can specify multiple files.
      ),
    ),
  ),
),
```
Register your asset in your layout:
```php
Yii::app()->clientScript->registerCssFile('css/bootstrap.css')
```
Additional Information
----------------------
This extension is heavily based on Crisu83's extension: http://www.yiiframework.com/extension/less/.
I changed how the configuration works so they are not compatible and that's the reason for the fork. I hope he doesn't mind.

Contribution
------------
Pull requests are welcomed.
