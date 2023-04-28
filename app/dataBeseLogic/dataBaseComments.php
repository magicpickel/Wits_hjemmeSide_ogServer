<?php
/* 

1. This class is responsible of storing the user comment data in a file, 
 get the user comment data from a file and delete the user comment data from a file.

2. The constructor of this class takes an array as an argument, which contains the user comment data.

3. The function StorCidData() is responsible of storing the user comment data in a file. 
It serialize the array that contains the user comment data then store it in a file.

4. The function get_StorCidData() is responsible of getting the user comment data from a file.
 It recovers the data from the file then unserialize it and return it.

5. The function deleteCidData() is responsible of deleting the user comment data from a file.
 It deletes the file that contains the user comment data.

6. The function sortCid($v) is responsible of checking if the user comment data is stored in a file or not.
 It takes a string as an argument, which is the key of the user comment data.
 It uses this key to check if the user comment data is stored in a file or not.
 It returns true if the user comment data is stored in a file and false if not.

7. The variable $cidArray stores the user comment data in an array. */

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

