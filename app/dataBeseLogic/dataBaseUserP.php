<?php
class UserDBp
{
    private $password;
    private $userUid;
    private array $listOfUid;

    function __construct($userUid, $userPaswor, $listUid)
    {
        $this->password = $userPaswor;
        $this->userUid = $userUid;
        $this->listOfUid = $listUid;
    }
    public function UpdateUidListd()
    {
        array_push($this->listOfUid, $this->userUid);
        $serializedData = serialize($this->listOfUid);
        file_put_contents(dirname(__DIR__).'/dataBaseFiles/UidList.txt', $serializedData) or die("Unable to update uidList data");
    }

    public function StoreUid_paswor()
    {
    
        $tempChar = "P";
        $userIDforP = $this->userUid . $tempChar;
        //"$this->userUid"=>$this->password
        $serializedData = serialize($this->password);
        file_put_contents(dirname(__DIR__)."/dataBaseFiles/usersPaswordUidPair/$userIDforP.txt", $serializedData) or die("Unable to save user pasword");
    }
}