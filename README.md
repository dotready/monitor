# monitor

Mini application for monitoring url's. Capable of monitoring multiple url's or anything that makes use of a socket.
Configurable options for anything in a monitor.

Uses one panic email address for sending an email once(!) if an error occurs. Only sends a follow up email if url is not giving 
any errors anymore

## Getting started

VirtualBox is a requirement because the Vagrantfile is setup for a VirtualBox, not parallels or any other virtualization layer.
You can get it [here](https://www.virtualbox.org/wiki/Downloads)

1. git clone https://github.com/dotready/monitor.git
2. cd monitor/vagrant
3. vagrant plugin install vagrant-hostmanager (edits your /etc/hosts file)
4. vagrant plugin install vagrant-bindfs (mounts the vb filesystem to local fs)
5. vagrant plugin install vagrant-vbguest (vb guest additions
6. vagrant up (this could take about 5 minutes on a good connection, a debian image is downloaded and installed)
7. vagrant ssh (you are now in the guest system)
8. cd /var/www/Moninitor/
9. php composer.phar install
10. visit https://monitor.videodock.com/

## Command line

The application is also designed to run from commandline. You can set it up as a cronjob using this as base:
`php app/cli/console.php /`

## Explaining the application

The application is based on configurable parts. Url's that should be watch by the system are configured in `app/config/monit.json`.
Each url configuration must have a host, sslOnOff, path and callback (executable class)

### Breaking down a configuration

```json
{
  "panicEmailAddress": "email@domain.com",
  "monitor": {
    "urls": [
      {
        "ssl": true,
        "host": "yoursite.com",
        "path": "/optionalpath",
        "callback": "\\path\\to\\your\\Callback"
      }
    ]
  }
}
```

The panic email address is setup once, to warn the developer/administrator to something is not right. This results in malformed json or a 404 in most cases.
In the monitor section we have an array of url's with the said options.

### Services and dependency injection

The system uses 3 services: Mail, Monitor and Config services. These are all configured in their own service providers.
Providers are necessary the share the services and only the provider knows how to setup a service correct.

Some services also have dependencies on other services/object, like the config service object. 
It needs a reader and only accepts an object that has implemented the FileReaderInterface.
This way the service doesn't care what you give him, it knows it has always the requested methods.

```php
/**
 * Registers services on the given app.
 *
 * This method should only be used to configure services and parameters.
 * It should not get services.
 */
 public function register(Application $app)
 {
    $app['config.service'] = new ConfigService(
        new JsonReader(),
        $app['configpath']
    );
  }
```

### Domains

The application is divided in domains. Each domain is an expert on it's own. This is part of DDD philosophy and [Separation of concerns](https://en.wikipedia.org/wiki/Separation_of_concerns)
Current domains are:

1. Config
2. Mail
3. Monitor
