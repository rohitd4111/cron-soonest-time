<?php
/**
 * Nextcrontime class defines the logic to calculate soonest cron time as per the configuration file.
*/

namespace AIopsGroup\CronTime;

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

    /**
    * @return string $result
    */
    public function calculatenextCron(){
        //Read Provided File from input
        $read_txt_file = fopen ($this->filename, "r");
        $result = '';
        while (!feof ($read_txt_file)) {
            $line = fgets($read_txt_file, 4096);
            $list = explode(" ", $line);
            $minute= $list[0];
            $hour =  $list[1];
            $timing = $list[2];
            $result .= $this->everyMinute($minute,$hour,$timing).
            $this->everyHour($minute,$hour,$timing).
            $this->everyDay($minute,$hour,$timing).
            $this->everySixtyminute($minute,$hour,$timing);
        }
        return $result;        
        //Close File Handler
        fclose ($read_txt_file);
    }

    /**
    * @param int $minute
    * @param int $hour
    * @param string $timing
    */
    public function everyMinute($minute,$hour,$timing)
    {
        if($minute== "*" && $hour=="*")
        {
            return $this->inputtime." ".$this->today." ".$timing;
        }
    }

    /**
    * @param int $minute
    * @param int $hour
    * @param string $timing
    */
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

    /**
    * @param int $minute
    * @param int $hour
    * @param string $timing
    */
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

    /**
    * @param int $minute
    * @param int $hour
    * @param string $timing
    */
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

?>
