<?php

class postDB
{
    private $pid;
    private $uid;
    private $title;
    private $content;
    private $date;
    private $iid;
    private $cid;
    private $pidArray;



    function __construct(
        array $pidArray
    ) {

        $this->pid = $pidArray['pid'];   
        $this->uid = $pidArray['uid'];
        $this->title = $pidArray['title'];
        $this->content = $pidArray['content'];
        $this->date = $pidArray['date'];
        $this->iid=$pidArray['iid'];
        $this->cid=$pidArray['cid'];
        $this->pidArray = $pidArray;
    }


    public function StorPidData()
    {

        $serializedData = serialize($this->pidArray);
        file_put_contents(dirname(__DIR__)."/dataBaseFiles/post/$this->pid.txt", $serializedData) or die("Unable to save pid data");
    }

    public function get_StorPidData()
    {
        $recoveredData = file_get_contents(dirname(__DIR__)."/dataBaseFiles/post/$this->pid.txt") or die("Unable to get pid data");
       
            $recoveredArray = unserialize($recoveredData);
            
            return $recoveredArray;
           
    }
    public function sortPid($v){
        $pid=$this->get_StorPidData();
        
       if($pid["$v"]==$this->{"$v"}){
        return true;
       }

       return false;

    }   
    public function deletePidData()
    {
        unlink(dirname(__DIR__)."/dataBaseFiles/post/$this->pid.txt") or die("Unable to delete user data");
    }
}
