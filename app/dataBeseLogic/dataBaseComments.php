<?php

class commentDB
{
    private $cid;
    private $uid;
    private $pid;
    private $content;
    private $date;

    private $cidArray;



    function __construct(
        array $cidArray
    ) {

        $this->cid = $cidArray['cid'];   
        $this->uid = $cidArray['uid'];
        $this->content = $cidArray['content'];
        $this->date = $cidArray['date'];
        $this->pid=$cidArray['pid'];
        $this->cidArray = $cidArray;
    }


    public function StorCidData()
    {

        $serializedData = serialize($this->cidArray);
        file_put_contents(dirname(__DIR__)."/dataBaseFiles/comments/$this->cid.txt", $serializedData) or die("Unable to save cid data");
    }

    public function get_StorCidData()
    {
        $recoveredData = file_get_contents(dirname(__DIR__)."/dataBaseFiles/comments/$this->cid.txt") or die("Unable to get cid data");
        
            $recoveredArray = unserialize($recoveredData);
            return $recoveredArray;
            
           
    }
    public function sortCid($v){
        $cid=$this->get_StorCidData();
        
       if($cid["$v"]==$this->{"$v"}){
        return true;
       }
    
       return false;

    }   
    public function deleteCidData()
    {
        unlink(dirname(__DIR__)."/dataBaseFiles/comments/$this->cid.txt") or die("Unable to delete user comment");
    }
}
