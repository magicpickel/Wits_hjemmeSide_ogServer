<?php
require_once(dirname(__DIR__) . '/mainApiLogic/postLogic.php');
require_once(dirname(__DIR__) . '/dataBeseLogic/dataBasePost.php');
require_once(dirname(__DIR__) . '/dataBeseLogic/dataBaseImage.php');



function get_uids()
{
    $user = new User("", array("uid" => "", "firstname" => "", "lastname" => "", "date" => ""), "");
    return $user->get_uidList();
}
function get_pids()
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
{            //teeeeeeeeeeeeesssssssssst
    $post = new Post("", "", "", "", "", "");
    $count = $post->Count(dirname(__DIR__) . '/dataBaseFiles/comments') - 1;
    $cids = array();
    for ($i = 0; $i <= $count; $i++) {
        $cids[$i] = $i + 1;
    }

    return $cids;
}
function get_iids()
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
{ //teeeeeeeeeeeeesssssssssst
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
{ //teeeeeeeeeeeeesssssssssst
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
{
    $userdb = new UserDB(array("uid" => $uid, "firstname" => "", "lastname" => "", "date" => ""));
    $user = $userdb->get_StorUsrData();
    return $user;
}


function get_post($pid)
{
    $postDb = new PostDB(array("pid" => $pid, "uid" => "", "title" => "", "content" => "", "date" => "", "iid" => "", "cid" => ""));
    $post = $postDb->get_StorPidData();
    unset($post['cid'], $post['iid']);
    return $post;
}
function get_comment($cid)
{
    $commentDb = new commentDB(array("cid" => "$cid", "uid" => "", "pid" => "", "content" => "", "date" => ""));
    $comment = $commentDb->get_StorCidData();
    return $comment;
}
function get_image($iid)
{
    $imagedb = new imageDB("", $iid, "", "");
    $image = $imagedb->get_StoreUid_iidData();
    return $image;
}
function add_attachment($pid, $iidNewAd)
{
    $post = new Post("", "", "", "", "", "");
    $iidT = $post->Count(dirname(__DIR__) . '/dataBaseFiles/iidData') + 1;
    $iidA = get_image($iidNewAd);

    $iidDAtaB = new imageDB("", $iidT, $iidA['date'], $pid);
    $iidDAtaB->StoreiidData($iidA['path']);
}

function delete_attachment(int $pid, int $iid)
{



    $iidDb = new imageDB("", $iid, "", "");

    $iidDb->deleteiidData();




    $dir = dirname(__DIR__) . '/dataBaseFiles/iidData';

    $fileToDelete = $iid . '.txt';

    $files = scandir($dir);


    $files = array_diff($files, array('.', '..'));


    sort($files);


    $indexToDelete = array_search($fileToDelete, $files);



    //unset($files[$indexToDelete]);

    

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
{
    $commentDb = new commentDB(array("cid" => "$cid", "uid" => "", "pid" => "", "content" => "", "date" => ""));
    $commentDb->deleteCidData();
    $dir = dirname(__DIR__) . '/dataBaseFiles/comments';
    $fileToDelete = $cid . '.txt';
    $files = scandir($dir);
    $files = array_diff($files, array('.', '..'));
    sort($files);
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
