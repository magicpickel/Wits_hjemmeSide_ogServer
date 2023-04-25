<?php
class UserDB
{
    private $uid;
    private $firstname;
    private $lastname;
    private $date;

    private $userAry;



    function __construct(
        array $userAry

    ) {

        $this->uid = $userAry['uid'];
        $this->firstname = $userAry['firstname'];
        $this->lastname = $userAry['lastname'];
        $this->date = $userAry['date'];
        $this->userAry = $userAry;
    }


    public function StorUsrData()
    {

        $serializedData = serialize($this->userAry);
        file_put_contents(dirname(__DIR__)."/dataBaseFiles/usersData/$this->uid.txt", $serializedData) or die("Unable to save user data");
    }

    public function get_StorUsrData()
    {
        $recoveredData = file_get_contents(dirname(__DIR__)."/dataBaseFiles/usersData/$this->uid.txt") or die("Unable to get user data");
        $recoveredArray = unserialize($recoveredData);
        return $recoveredArray;
    }
    public function deleteUserData()
    {
        unlink(dirname(__DIR__)."/dataBaseFiles/usersData/$this->uid.txt") or die("Unable to delete user data");
    }
}
