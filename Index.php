<?php
/*  This script takes time as single argument.
*   Result will output the soonest time at which each of the commands will fire and whether it is today or  tomorrow. 
*   @author Rohit Dhiman rdhiman@aiopsgroup.com
*/

class Nextcrontime{

    public $inputtime;
    public $splitime;
    public $today;
    public $tomorrow;

    public function __construct(){
        global $argv;
        $this->filename = $argv[2].".txt";
        $this->inputtime= $argv[1];
        $this->splitime = explode(":", $this->inputtime);
        $this->today    = "Today";
        $this->tomorrow = "Tomorrow";
    }

    public function calculatenextCron(){
        $read_txt_file = fopen ($this->filename, "r");
        while (!feof ($read_txt_file)) {
            $line = fgets($read_txt_file, 4096);
            $list = explode(" ", $line);
            $minute= $list[0];
            $hour =  $list[1];
            $timing = $list[2];

            // Case 1 - Every Minute //
            echo $this->everyMinute($minute,$hour,$timing) ;

            // Case 2 - Hourly//
            echo $this->everyHour($minute,$hour,$timing) ;

            // Case 3 - Daily//
            echo $this->everyDay($minute,$hour,$timing) ;

            // Case 4 - Sixty Times //
            echo $this->everySixtyminute($minute,$hour,$timing) ;
        }
        //Close File Handler
        fclose ($read_txt_file);
    }

    public function everyMinute($minute,$hour,$timing)
    {
        if($minute== "*" && $hour=="*")
        {
            return $this->inputtime." ".$this->today." ".$timing;
        }
    }

    public function everyHour($minute,$hour,$timing)
    {
        if($minute != '*' && $hour=='*'){
            $newtime = $this->splitime[0].":".$minute;
            if(strtotime($newtime) >=  strtotime($this->inputtime)){
                return $newtime." ".$this->today." ".$timing;
            }else{
                $time = date('Y-m-d H:i', strtotime($newtime) + 60*60);
                if (date("Y-m-d") > $time) {
                    return date("H:i", strtotime($time))." ".$this->today." ".$timing;
                }else{
                    return date("H:i", strtotime($time))." ".$this->tomorrow." ".$timing;
                }
            }
        }
    }

    public function everyDay($minute,$hour,$timing)
    {
        if($minute != "*" && $hour!="*"){
            $createnewtime = $hour.":".$minute;
            if(strtotime($createnewtime) < strtotime($this->inputtime)){
                return $createnewtime." ".$this->tomorrow." ".$timing;
            }else{
                return $createnewtime." ".$this->today." ".$timing;
            }
        }
    }

    public function everySixtyminute($minute,$hour,$timing)
    {
        if($minute == "*" && $hour!="*"){
            $againcreatenewtime = $hour.':00';
            if(strtotime($againcreatenewtime) >= strtotime($this->inputtime)){
                if($this->inputtime[0] == $hour)
                {
                    return $this->inputtime." ".$this->today." ".$timing;
                }else{
                    return $againcreatenewtime." ".$this->today." ".$timing;
                }
            }elseif($this->splitime[0] == $hour){
                return $this->inputtime." ".$this->today." ".$timing;
            }
            else{
                return $againcreatenewtime." ".$this->tomorrow." ".$timing;
            }
        }
    }
}

//Execute Function
If(isset($argv) && !empty($argv[1]) && !empty($argv[2] )){
    $execute = new Nextcrontime();
    return $execute->calculatenextCron();
}else{
    echo "Invalid Format. Example - php Index.php 16:10 FILENAME";
    return;
}
?>
