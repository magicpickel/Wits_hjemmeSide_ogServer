<?php


class imageDB
{
    private $iid;
    private $path;
    private $date;

    private $pid;

    private $filename;

    function __construct($path, $iid, $date,$pid)
    {
        $this->iid = $iid;
        $this->path = $path;
        $this->date = $date;
        $this->pid = $pid;
        $this->filename="";
    }


    public function StoreiidData($newPath)
    {       
        if($newPath == ""){
            $iddArray = array("iid" => $this->iid, "path" => "http://$_SERVER[HTTP_HOST]/images/$this->filename", "date" => $this->date, "pid" => $this->pid);
        }else{
            $iddArray = array("iid" => $this->iid, "path" => $newPath, "date" => $this->date, "pid" => $this->pid);}

        


        $serializedData = serialize($iddArray);
        file_put_contents(dirname(__DIR__)."/dataBaseFiles/iidData/$this->iid.txt", $serializedData) or die("Unable to save iid");
    }
    public function StoreImage()

    {   
        error_reporting(E_ERROR | E_PARSE);
        session_write_close();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
        
        $image_data = curl_exec($ch);
        
        $pathinfo = pathinfo($this->path);
        $extension = $pathinfo['extension'];
        $this->filename = "$this->iid" . "." . $extension;
        $file_path = dirname(__DIR__) . "/public/images/$this->filename";
        
        $fp = fopen($file_path, 'w');
        fwrite($fp, $image_data);
        fclose($fp);
        
        session_start();
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
       
    }
    
    public function get_StoreUid_iidData()
    {


        $recoveredData = file_get_contents(dirname(__DIR__)."/dataBaseFiles/iidData/$this->iid.txt") or die("Unable to get iid data");
       
        $recoveredArray = unserialize($recoveredData);
        return $recoveredArray;
        
    }

    public function sortiid($v){
        
        $iid=$this->get_StoreUid_iidData();

       
        
       if($iid["$v"]==$this->{"$v"}){
        
        return true;
       }

       return false;
    }

    
    public function deleteiidData()
    {
        unlink(dirname(__DIR__)."/dataBaseFiles/iidData/$this->iid.txt") or die("Unable to delete iid data");
    }
}
