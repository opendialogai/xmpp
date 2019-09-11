# OpenDialog Example Package

[![CircleCI](https://circleci.com/gh/opendialogai/xmpp/tree/master.svg?style=svg&circle-token=d14bcacaf3cd3e6ae4dfd2fb3bf03658cf0ca8fa)](https://circleci.com/gh/opendialogai/xmpp/tree/master)

This is the OpenDialog XMPP package that can be used inside OpenDialog for XMPP communications.

[OpenDialog](https://opendialog.ai) is a conversation management platform and OpenDialog Core provides the 
key pieces of support required. It has been created by the conversational interface and applied AI team at [GreenShoot Labs](https://www.greenshootlabs.com/).

We will soon be releasing our webchat package that gives you a webchat interface as well as a full Laravel-based
application that makes use of OpenDialog core and provides a GUI to manage conversations. 

In the meantime if you would like a preview please [get in touch](https://www.greenshootlabs.com/).

## Installing

To install using [Composer](https://getcomposer.org/) run the following command:

`composer require opendialogai/xmpp`

## Local Config
To publish config files for local set up and customisation, run

```php artisan vendor:publish --tag="config"```

This will copy over all required config files into `config/opendialog/`

## Running Code Sniffer

To run code sniffer, run the following command
```./vendor/bin/phpcs --standard=od-cs-ruleset.xml src/ --ignore=*/migrations/*,*/tests/*```

This will ignore all files inside of migration directories as they will never have a namespace

## Running Tests

```./vendor/bin/phpunit```

## DGraph

You may find instructions to setup a development instance of DGraph in dgraph/dgraph-setup.md

You will need to set the DGraph URL and port in your .env file, e.g.:

```
DGRAPH_URL=http://10.0.2.2
DGRAPH_PORT=8080
```

### Query Logging

To log DGraph queries to the standard application log, set the `LOG_DGRAPH_QUERIES` environment variable to true.
All queries are logged at info level

## Logging API requests

By default, all incoming and outgoing API calls will be logged to the request and response mysql tables.
To prevent this happening, set the `LOG_API_REQUESTS` env variable to `false`

## Local Artisan

If you need to use artisan commands in the development of this pacakge, you can use `/vendor/bin/artisan` in it's place.
This also works if you set up a symlink from the project root:
```ln -s vendor/bin/artisan artisan```
