<?php
/* 

1. UserDBp is a class that is used to store the user’s password in the data base. 

2. The constructor of the class takes 3 parameters, the user’s uid, password, and the list of uid. 

3. The class has 2 methods. 

4. The first method is UpdateUidListd(), which is used to update the list of uid. 

5. The second method is StoreUid_paswor(), which is used to store the user’s password. 

6. The user’s password is stored in the file “usersPaswordUidPair”. 

7. The name of the file is the user’s uid and a “P” character. 

8. The password is stored in a serialized format.
 */
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