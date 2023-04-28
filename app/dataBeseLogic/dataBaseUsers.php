<?php
/* 
1. The constructor takes an array of user data and assigns it to properties of the class.

2. The StorUsrData() method serializes the user data and stores it in a file named after the user id.

3. The get_StorUsrData() method retrieves the data from the file and returns it as an array.

4. The deleteUserData() method deletes the file containing the user data. */
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
