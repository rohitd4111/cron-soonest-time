# Soonest Cron Run Time

A simple script written in PHP which takes time as single argument and output the soonest time at which each of the commands will fire and whether it is today or tomorrow.

## Examples of the scheduler config

```bash
30 1 /bin/run_me_daily
45 * /bin/run_me_hourly
* * /bin/run_me_every_minute
* 19 /bin/run_me_sixty_times

```
## Examples of the Output

```bash
1:30 tomorrow - /bin/run_me_daily
16:45 today - /bin/run_me_hourly
16:10 today - /bin/run_me_every_minute
19:00 today - /bin/run_me_sixty_times
```

## Usage

```bash
php-cgi -f Index.php time=20:31
````

## Note
```
This is just a simple script. We can improve code quality and make it better
````

## License
```
OSL
```