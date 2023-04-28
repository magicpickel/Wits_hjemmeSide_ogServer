<?php

/* 
1. The User class is the main class of this program. It is used to create a new user and check if the user is already in the database.
2. The constructor function is used to initialize the values of the class variables.
3. The createU() function is used to create a new user.
4. The get_uidList() function is used to get the list of users.
5. The get_StoreUid_paswor() function is used to get the list of users and their passwords.
6. The doseUserEx() function is used to check if the user is already in the database.
7. The itItcorretLogin() function is used to check if the user’s password is correct. */

require_once(dirname(__DIR__).'/dataBeseLogic/dataBaseUsers.php');
require_once(dirname(__DIR__).'/dataBeseLogic/dataBaseUserP.php');
class User
{


    private $userDB;
    private $usersUid;
    private $usersPaswo;

    public function __construct($uid, array $userAryToDB, $usersPaswo)
    {
        $this->userDB = new UserDB($userAryToDB);
        $this->usersUid = $uid;
        $this->usersPaswo = $usersPaswo;
    }

    public function get_uidList()
    {
        error_reporting(E_ERROR | E_PARSE);
        
        if (!file_get_contents(dirname(__DIR__).'/dataBaseFiles/UidList.txt')) {
            
            return array();
        }
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $recoveredData = file_get_contents(dirname(__DIR__).'/dataBaseFiles/UidList.txt');
        $recoveredArray = unserialize($recoveredData);

        return $recoveredArray;
    }

    public function createU()
    {

        $password = password_hash($this->usersPaswo, PASSWORD_DEFAULT);
        $listUid = $this->get_uidList();
        $userDPpasw = new UserDBp($this->usersUid, $password, $listUid);
        $userDPpasw->UpdateUidListd();
        $userDPpasw->StoreUid_paswor();
        $this->userDB->StorUsrData();
    }
    public function get_StoreUid_paswor()
    {
        error_reporting(E_ERROR | E_PARSE);
        if (!file_get_contents(dirname(__DIR__).'/dataBaseFiles/UidList.txt')) {
            return array();
        }
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $tempChar = "P";
        $userIDforP = $this->usersUid . $tempChar;
        error_reporting(E_ERROR | E_PARSE);
        if (!file_get_contents(dirname(__DIR__)."/dataBaseFiles/usersPaswordUidPair/$userIDforP.txt")) {
           
            return array();
        }
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $recoveredData = file_get_contents(dirname(__DIR__)."/dataBaseFiles/usersPaswordUidPair/$userIDforP.txt") or die("Unable to retrieve userDB pasword");
        $recoveredArray = unserialize($recoveredData);
        return $recoveredArray;
    }
    public function doseUserEx()
    {
        foreach ($this->get_uidList() as $tempUidlist) {
    
            $tempUid = $tempUidlist;

            if (preg_match("/$this->usersUid\b/", $tempUid)) {
               
                return true;
            }
        }
        return false;
    }
    public function itItcorretLogin()
    {   
        
        
        $hashp = $this->get_StoreUid_paswor();

        
        
        foreach ($this->get_uidList() as $tempUidlist) {

            $tempUid = $tempUidlist;
            if($this->doseUserEx()){

            if (preg_match("/$this->usersUid/", $tempUid,)) {
                
                
                if (password_verify($this->usersPaswo, $hashp)) {
                   
                    return true;
                    
                }
                
            }
        }
            
        }
        return false;
    }
}
