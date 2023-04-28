<?php
/* 
    
1. The postDB class is created to store the data entered by the user. 
 The data is stored in the form of an array with the following keys: pid, uid, title, content, date, iid, and cid.

2. The constructor function of the postDB class takes an array as a parameter and stores the array values in the respective variables.

3. The StorPidData function serializes the array and stores it in a file named after the pid value.

4. The get_StorPidData function retrieves the data from the file which is named after the pid value.

5. The deletePidData function deletes the file which is named after the pid value.

6. The sortPid function checks if the values of the array keys are equal to the values of the class variables.
 If the values are equal, the function returns true; otherwise, the function returns false. */
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