
## Cronjob

When building an API it is sometimes required that specific code gets executed
at a certain interval. For this purpose Fusio provides the cronjob panel.

### Installation

To execute the cronjobs Fusio uses the standard linux `cron` util. It can write
a dedicated cron file which contains all needed cron entries. This file should
be placed in the `cron.d` folder of your system.

To tell Fusio that it should write such a cron file you need to create the file
where the `fusio_cron_file` setting points to i.e. `/etc/cron.d/fusio`. The
web user needs also write access to this file since Fusio always updates the
file in case you create or update an entry. You can change those settings at the
`configuration.php` file
