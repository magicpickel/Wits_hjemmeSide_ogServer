<?php
require_once(dirname(__DIR__) . '/mainApiLogic/postLogic.php');
require_once(dirname(__DIR__) . '/dataBeseLogic/dataBasePost.php');
require_once(dirname(__DIR__) . '/dataBeseLogic/dataBaseImage.php');



function get_uids()
/* 
1. Creates a new User object with empty values for the instance variables.
2. Calls the get_uidList method of the User class.
3. Returns the array of user IDs. */
{
    $user = new User("", array("uid" => "", "firstname" => "", "lastname" => "", "date" => ""), "");
    return $user->get_uidList();
}
function get_pids()
/* 
1. Creates a new Post object.
2. Gets the total number of posts in the database.
3. Creates an array of post IDs.
4. Returns the array. */
{
    $post = new Post("", "", "", "", "", "");

    $count = $post->Count(dirname(__DIR__) . '/dataBaseFiles/post') - 1;

    $pids = array();
    for ($i = 0; $i <= $count; $i++) {
        $pids[$i] = $i + 1;
    }

    return $pids;
}
function get_cids()
/* 
1. Creates a new post object with empty values.
2. Counts the number of comments in the comments file.
3. Creates a new array and stores the number of comments in the array.
4. Returns the array. */
{           
    $post = new Post("", "", "", "", "", "");
    $count = $post->Count(dirname(__DIR__) . '/dataBaseFiles/comments') - 1;
    $cids = array();
    for ($i = 0; $i <= $count; $i++) {
        $cids[$i] = $i + 1;
    }

    return $cids;
}
function get_iids()
/* 
1. Create a new instance of the Post class
2. Count the number of items in the iidData file
3. Create an array with the number of items in the iidData file
4. Return the array */
{
    $post = new Post("", "", "", "", "", "");
    $count = $post->Count(dirname(__DIR__) . '/dataBaseFiles/iidData') - 1;

    $iids = array();
    for ($i = 0; $i <= $count; $i++) {
        $iids[$i] = $i + 1;
    }

    return $iids;
}

function get_pids_by_uid($uid)
/* 
1. Get all the pid's
2. Get the posts with the uid of the user
3. Add the pid to the array if the post has the uid of the user
4. Return the array */
{
    $pids = get_pids();
    $arrayOFpid = array();
    foreach ($pids as $pidID) {
        $databasePost = new postDB(array("pid" => "$pidID", "uid" => $uid, "title" => "", "content" => "", "date" => "", "iid" => "", "cid" => ""));
        if ($databasePost->sortPid("uid")) {
            array_push($arrayOFpid, $pidID);
        }
    }

    return $arrayOFpid;
}
function get_cids_by_uid($uid)
/* 
1. Get all the comments' IDs
2. For each comment ID, create a new commentDB object, passing in the comment ID and the UID of the user we want to check
3. If the comment ID is found in the database, then the comment belongs to the user we're checking, so we add it to the array of comments to return
4. If the comment ID is not found in the database, then the comment does not belong to the user we're checking, so we do nothing with it
5. Return the array of comments that belong to the user we're checking */
{ 
    $cids = get_cids();
    $arrayOFcids = array();
    foreach ($cids as $cid) {
        $databaseCid = new commentDB(array("cid" => "$cid", "uid" => "$uid", "pid" => "", "content" => "", "date" => ""));
        if ($databaseCid->sortCid("cid")) {
            array_push($arrayOFcids, $cid);
        }
    }

    return $arrayOFcids;
}
function get_cids_by_pid($pid)
/* 
1. Create an array of cids.
2. Create an array to store the cids that has the same pid as the argument.
3. Create a commentDB object for each cid in the array of cids.
4. If the pid of the commentDB object is the same as the argument, store the cid in the array.
5. Return the array of cids that has the same pid as the argument. */
{ 
    $cids = get_cids();
    $arrayOFcids = array();
    foreach ($cids as $cid) {
        $databaseCid = new commentDB(array("cid" => "$cid", "uid" => "", "pid" => "$pid", "content" => "", "date" => ""));
        if ($databaseCid->sortCid("pid")) {
            array_push($arrayOFcids, $cid);
        }
    }

    return $arrayOFcids;
}
function get_iids_by_pid($pid)
/* 
1. assigns the return value of get_iids() to a variable named $iids
2. creates an empty array named $arrayOFiids
3. starts a foreach loop and assigns the current value of $iids to a variable named $iid
4. creates a new imageDB object named $databaseiid, passing it the following arguments:
    a. an empty string
    b. the value of the $iid variable
    c. an empty string
    d. the value of the $pid variable
5. calls the sortiid() method of the $databaseiid object and passes it the string "pid" as an argument
6. if the sortiid() method returns the value true, the code pushes the value of $iid onto the $arrayOFiids array
7. the foreach loop ends
8. the code returns the value of $arrayOFiids to the calling code */
{
    $iids = get_iids();
    $arrayOFiids = array();
    foreach ($iids as $iid) {
        $databaseiid = new imageDB("", $iid, "", $pid);
        if ($databaseiid->sortiid("pid")) {
            array_push($arrayOFiids, $iid);
        }
    }

    return $arrayOFiids;
}

function get_user($uid)
/* 
1. Creates a new object of type UserDB, passing the uid as the only parameter.
2. Calls the get_StorUsrData() function, which returns a User object.
3. Returns the user object. */
{
    $userdb = new UserDB(array("uid" => $uid, "firstname" => "", "lastname" => "", "date" => ""));
    $user = $userdb->get_StorUsrData();
    return $user;
}


function get_post($pid)
/* 
1. Creates a new object that represents a blog post. The object is initialized with the post’s ID.
2. Gets the post’s data from the database.
3. Removes the post’s category and image IDs from the returned data.
4. Returns the post’s data. */
{
    $postDb = new PostDB(array("pid" => $pid, "uid" => "", "title" => "", "content" => "", "date" => "", "iid" => "", "cid" => ""));
    $post = $postDb->get_StorPidData();
    unset($post['cid'], $post['iid']);
    return $post;
}
function get_comment($cid)
/* 
1. Create a commentDB object, which stores the information of the comment.
2. Use the get_StorCidData() function to get the information of the comment.
3. Return the information of the comment. */
{
    $commentDb = new commentDB(array("cid" => "$cid", "uid" => "", "pid" => "", "content" => "", "date" => ""));
    $comment = $commentDb->get_StorCidData();
    return $comment;
}
function get_image($iid)
/* 
1. Creates an imageDB object.
2. Calls the get_StoreUid_iidData() function to retrieve the image data from the database.
3. Returns the image data to the calling function. */
{
    $imagedb = new imageDB("", $iid, "", "");
    $image = $imagedb->get_StoreUid_iidData();
    return $image;
}
function add_attachment($pid, $iidNewAd)
/* 
1. Creates a new post object.
2. Gets the number of posts already in the database and adds one to this number to get the new post ID.
3. Gets the path and date of the new image.
4. Creates a new imageDB object and stores the new image in the database. */
{
    $post = new Post("", "", "", "", "", "");
    $iidT = $post->Count(dirname(__DIR__) . '/dataBaseFiles/iidData') + 1;
    $iidA = get_image($iidNewAd);

    $iidDAtaB = new imageDB("", $iidT, $iidA['date'], $pid);
    $iidDAtaB->StoreiidData($iidA['path']);
}

function delete_attachment(int $pid, int $iid)
/* 
1. Delete the data from pidData.txt
2. Delete the data from iidData.txt
3. Rename the iidData.txt files
4. Update the iidData.txt files */
{



    $iidDb = new imageDB("", $iid, "", "");

    $iidDb->deleteiidData();




    $dir = dirname(__DIR__) . '/dataBaseFiles/iidData';

    $fileToDelete = $iid . '.txt';

    $files = scandir($dir);


    $files = array_diff($files, array('.', '..'));


    sort($files,SORT_NATURAL);


    $indexToDelete = array_search($fileToDelete, $files);



    

    

    foreach ($files as $index => $file) {

        $newIndex = $index + 1;

        rename($dir . '/' . $file, $dir . '/' . $newIndex . '.txt');


        $data = file_get_contents($dir . '/' . $newIndex . '.txt');
        $array = unserialize($data);
        $array['iid'] = $newIndex;
        $data = serialize($array);
        file_put_contents($dir . '/' . $newIndex . '.txt', $data);
    }
}

function testURlnew($imgURl,$pid)
/* 
1. Get the iids of all images in the database
2. Loop through all the iids and check if the image path is the same as the current image path
3. If the image path is the same, return the iid
4. If the image path is not the same return true */
{

    if (!isset($pid)) {
       
        $iids = get_iids();
        
    }else{$iids=get_iids_by_pid($pid);}
        
        foreach ($iids as $iid) {
            
            $databaseiid = new imageDB($imgURl, $iid, "", "");
            
            if ($databaseiid->sortiid("path")) {
                
                return $iid;
            }
        }
  


    return true;
}
function delete_comment($cid)
/*
1. It creates a commentDB object with the cid of the comment to be deleted.
2. It deletes the comment from the database.
3. It renames all the comments in the database to have their cid's in order.
4. It updates the cid of every comment in the database. */

{
    $commentDb = new commentDB(array("cid" => "$cid", "uid" => "", "pid" => "", "content" => "", "date" => ""));
    $commentDb->deleteCidData();
    $dir = dirname(__DIR__) . '/dataBaseFiles/comments';
    $fileToDelete = $cid . '.txt';
    $files = scandir($dir);
    $files = array_diff($files, array('.', '..'));
    sort($files,SORT_NATURAL);
    
    $indexToDelete = array_search($fileToDelete, $files);
    //unset($files[$indexToDelete]);
    foreach ($files as $index => $file) {
        $newIndex = $index + 1;
        rename($dir . '/' . $file, $dir . '/' . $newIndex . '.txt');
        $data = file_get_contents($dir . '/' . $newIndex . '.txt');
        $array = unserialize($data);
        $array['cid'] = $newIndex;
        $data = serialize($array);
        file_put_contents($dir . '/' . $newIndex . '.txt', $data);
    }
}

function updateFiles($folder)
//is not used in the program
{
    $files = scandir($folder);
    $count = count($files);
    $i = 1;
    foreach ($files as $file) {
        if ($file != "." && $file != "..") {
            rename($folder . "/" . $file, $folder . "/" . $i);
            $i++;
        }
    }
}
function make_post_array()
/* 
1. Get all the pids
2. Get all the iids and cids associated with a pid
3. Get all the image and comment data associated with the iids and cids
4. Makes a postDB object for each pid
5. Sets the postDB object to have the following array structure:
    $post = array(
        "pid" => "",
        "uid" => "",
        "title" => "",
        "content" => "",
        "date" => "",
        "iid" => array(),
        "cid" => array()
    );
6. Returns an array of all the postDB objects */
{
    $pids = get_pids();

    foreach ($pids as $pid) {
        $iids = get_iids_by_pid($pid);
        $iidArryFromPid = array();
        $cids = get_cids_by_pid($pid);
        $cidArryFromPid = array();
        foreach ($iids as $iid) {
            $iidArryFromPid[$pid][$iid] = get_image($iid);
        }
        foreach ($cids as $cid) {
            $cidArryFromPid[$pid][$cid] = get_comment($cid);
        }

        $post = new postDB(array("pid" => $pid, "uid" => "", "title" => "", "content" => "", "date" => "", "iid" => "", "cid" => ""));

        $pids[$pid] = $post->get_StorPidData();
        if (isset($iidArryFromPid[$pid])) {

            $pids[$pid]["iid"] = $iidArryFromPid[$pid];
        } else {
            $pids[$pid]["iid"] = array();
        }
        if (isset($cidArryFromPid[$pid])) {
            $pids[$pid]["cid"] = $cidArryFromPid[$pid];
        } else {
            $pids[$pid]["cid"] = array();
        }
    }
    return $pids;
}
