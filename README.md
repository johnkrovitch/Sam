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

* Compass Filter

The Compass filter will use the Compass binary to transform your scss
files into css files.

```php
    $configuration = [
        'compass' => [
            // put here the compas binary path. By default it will assume
            // that the binary is loaded in $PATH
            'bin' => 'compass'
        }
    ];
```
