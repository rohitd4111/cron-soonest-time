<?php
/*  This script takes time as single argument.
*   Result will output the soonest time at which each of the commands will fire and whether it is today or  tomorrow.
*   @author Rohit Dhiman rdhiman@aiopsgroup.com
*/
require 'src/Nextcrontime.php';
use AIopsGroup\CronTime\Nextcrontime as CalculateTimeClass;
if (isset($argv) && !empty($argv[1]) && !empty($argv[2])) {
    $execute = new CalculateTimeClass();
    echo $execute->calculatenextCron();
} else {
    echo "Invalid Format. Example - php Index.php 16:10 FILENAME";
    return;
}
