# Sam
Simple Assets Manager

[![Build Status](https://travis-ci.org/johnkrovitch/Sam.svg?branch=master)](https://travis-ci.org/johnkrovitch/Sam)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/johnkrovitch/Sam/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/johnkrovitch/Sam/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/johnkrovitch/Sam/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/johnkrovitch/Sam/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/johnkrovitch/Sam/badges/build.png?b=master)](https://scrutinizer-ci.com/g/johnkrovitch/Sam/build-status/master)


Sam is your friend. Sam is like you, he does not like handling complicated
assets related stuff.

Sam is a Simple Assets Manager to help assets management in PHP Project.
You will be able to easily describe where you
want your assets to compiled.

Sam was first develop to be used with the SamBundle, but it can be used 
stand-alone. Using Sam stand-alone require coding your building tasks.
With the SamBundle, you will be able to use a more convenient yml configuration syntax.


## Installation

Using composer :

```
    composer require johnkrovitch/sam
```


## Usage

### Filters

A filter is a process which transform one or more source files into one
or more destination files, using a binary or not. A filter can have options,
like the binary path.

Sam a has the following built-in filters for now. More will be added.
You can also add your own filter

* Compass filter

The Compass filter will use the [Compass](http://compass-style.org/)
binary to transform your ".scss" files into ".css" files.

```php
    $configuration = [
        'compass' => [
            // put here the compas binary path. By default it will assume
            // that the binary is loaded in $PATH
            'bin' => 'compass'
        }
    ];
```

* Minify filter

The Minify Filter use the [matthiasmullie/minify](https://github.com/matthiasmullie/minify)
library to minify your css and js files. No options are available for now.

* Merge filter

The Merge filter will merge multiple files into one. It allow you to reduce
the number of http requests. No options are available for now.

* Copy filter

The Copy filter will copy one or more files into one or more directory.

#### Building your filters

In order to use compile your assets, you have to build the filters, using
the FilterBuilder. It will check the given configuration and create
filters instances according to this configuration.

```php
    
    // enable the filters with default configuration
    $configuration = [
        'compass' => [],
        'merge' => [],
        'minify' => [],
    ];
    
    // an event dispatcher is required. Some events will be dispatched
    // before the application of each filter
    $eventDispatcher = new EventDispatcher();
    
    // build tasks using the builder
    $builder = new FilterBuilder($eventDispatcher);
    
    // filters are build, they can be passed to the runner
    $filters = $builder->build($configuration);

```

### Tasks

A Task will apply one or more filters to a list of source files to a 
directory or a list of destination files. It describe your assets compilation
process.

#### Building your tasks

```php

    $taskConfigurations = [
        // main.css is just a name, you can put what ever you want
        'main.css' => [
            // this filters will be applied in this order to the destination files 
            'filters' => [
                'compass',
                'minify',
                'merge',
            ],
            // sources file to be compiled
            'sources' => [
                'src/MyBundle/Resources/public/css/mycss.css',
                'app/Resources/public/css/mycss.css',
                'css/mycss.css',
                'vendor/a-library/css/lib.css'
            ],
            // all assets will be minified and merged to this file
            'destinations' => [
                'web/css/main.min.css'
            ],
        ],
        
        'main.js' => [
            'filters' => [
                'compass',
                'minify',
                'merge',
            ],
            'sources' => [
                'css/myjs.js',
                'css/mycss.css',
                'css/mycss.scss',
            ],
            // all assets will be minified and merged to this directory
            'destinations' => [
                'web/css'
            ],
        ]
    ];
    
    // build tasks using the builder
    $builder = new TaskBuilder();
    $tasks = $builder->build($taskConfigurations);
    
```


### Running the tasks


Once your filters are configured and build, once your tasks are create,
you should run the task using the TaskRunner. A normalizer should be 
created to normalize file names for the filters.

```php

    // used to normalize file names because multiple pattern could be passed
    $normalizer = new Normalizer('/path/to/project_root');
    
    // used to locate the file
    $locator = new Locator($normalizer);
    
    // create the runner
    $runner = new TaskRunner(
        $filters,
        $locator,
        // debug
        false
    );

    // run your task
    foreach ($tasks as $task) {
        $runner->run($task);
    }

```


## SamBundle

Sam is designed to be used with Symfony and [SamBundle](https://github.com/johnkrovitch/SamBundle) 
integrate the Sam library with Symfony. It will allow your to put your assets
configuration into yaml files. It will ease the integration of vendor js/css
library (bootstrap, jquery...) using composer.

You no more have to use BootstrapBundle or jQueryBundle. You just have to 
require them using composer and easily copy, merge, minify those files
with your custom assets.


## Issues


Feel free to open an issue if you have a problem using this library.


## License

The Sam library is release under the [MIT License](https://opensource.org/licenses/MIT).
