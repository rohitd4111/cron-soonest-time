<?php
/*  This script takes time as single argument.
*   Result will output the soonest time at which each of the commands will fire and whether it is today or tomorrow. 
*   @author Rohit Dhiman rdhiman@aiopsgroup.com
*/

class nextcrontime{
    //Get Single argument passed
    function calculate_next_cron(){
        If(isset($_GET['time']) && !empty($_GET['time'])){
            //Read config.txt file
            $read_txt_file = fopen ("config.txt", "r");
            $splitime = explode(":", $_GET['time']);
            // Loop Content from config.txt line by line and perform required actions
            while (!feof ($read_txt_file)) {
                $line = fgets($read_txt_file, 4096);
                $list = explode(" ", $line);

                // Case 1 - Every Minute //
                if($list[0] == "*" && $list[1]=="*")
                {
                    echo $_GET['time']." ".'Today'." ".$list[2];
                }

                // Case 2 - Hourly//
                if($list[0] != '*' && $list[1]=='*'){
                    $newtime = $splitime[0].":".$list[0];
                    if(strtotime($newtime) >=  strtotime($_GET['time'])){
                        echo $newtime." ".'Today'." ".$list[2];
                    }else{
                        $time = date('Y-m-d H:i', strtotime($newtime) + 60*60);
                        if (date("Y-m-d") > $time) {
                            echo date("H:i", strtotime($time))." ".'Today'." ".$list[2];
                        }else{
                            echo date("H:i", strtotime($time))." ".'Tomorrow'." ".$list[2];
                        }
                    }
                }

                // Case 3 - Daily//
                if($list[0] != "*" && $list[1]!="*"){
                    $createnewtime = $list[1].":".$list[0];
                    if(strtotime($createnewtime) < strtotime($_GET['time'])){
                        echo $createnewtime." ".'Tomorrow'." ".$list[2];
                    }else{
                        echo $createnewtime." ".'Today'." ".$list[2];
                    }
                }

                // Case 4 - Sixty Times //
                if($list[0] == "*" && $list[1]!="*"){
                    $againcreatenewtime = $list[1].':00';
                    if(strtotime($againcreatenewtime) >= strtotime($_GET['time'])){
                        if($_GET['time'][0] == $list[1])
                        {
                            echo $_GET['time']." ".'Today'." ".$list[2];
                        }else{
                            echo $againcreatenewtime." "."Today"." ".$list[2];
                        }
                    }elseif($splitime[0] == $list[1]){
                        echo $_GET['time']." ".'Today'." ".$list[2];
                    }
                    else{
                        echo $againcreatenewtime." "."Tomorrow"." ".$list[2];
                    }
                }

            }
        }else{
            echo "Please Pass Time in HH:MM format. Example > php-cgi -f Index.php time=16:10.";
            return;
        }
        //Close File Handler
        fclose ($read_txt_file);
    }
}

//Execute Function
$execute = new nextcrontime();
$execute->calculate_next_cron();

?>
