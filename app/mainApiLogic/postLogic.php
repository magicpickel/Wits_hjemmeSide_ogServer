<?php

require_once(dirname(__DIR__).'/dataBeseLogic/dataBasePost.php');

require_once(dirname(__DIR__).'/dataBeseLogic/dataBaseImage.php');

require_once(dirname(__DIR__).'/dataBeseLogic/dataBaseComments.php');

class Post
{


    private $postDB;
    private $title;
    private $content;
    private $usersUid;
    private $imgUrl;
    private $date;
    private $pid;
    private $iid;
    private $cid;
    private array $pidArray;


    public function __construct($uid, $title, $content, $imgUrl, $date, $pid)
    {

        $this->usersUid = $uid;
        $this->title = $title;
        $this->content = $content;
        $this->imgUrl = $imgUrl;
        $this->date = $date;
        $this->pid = $pid;
        $this->iid ="";
        $this->cid ="";
        $this->pidArray = array("pid" => "$this->pid", "uid" => "$this->usersUid", "title" => "$this->title", "content" => "$this->content", "date" => "$this->date", "iid" => "", "cid" => "");
        $this->postDB = new postDB($this->pidArray);
    }
    public function Count($pathIIDorPID)
    {
        $files = scandir($pathIIDorPID);
        $num_files = count($files) - 2;
        return $num_files;
    }

    public function createPost()
    {   
        if($this->pid==""){
        $this->pid = $this->Count(dirname(__DIR__).'/dataBaseFiles/post') + 1;
        }
        $this->pidArray['pid'] = $this->pid;
        

        $this->postDB = new postDB($this->pidArray);

        $this->postDB->StorPidData();
    }


    public function createImg()
    {   
        if($this->pid==""){
        $this->pid = $this->Count(dirname(__DIR__).'/dataBaseFiles/post') + 1;
        }
        $iidT = $this->Count(dirname(__DIR__).'/dataBaseFiles/iidData') + 1;

        $img = new imageDB($this->imgUrl, $iidT, $this->date,$this->pid);
        $img->StoreImage();
        $img->StoreiidData("");
    }
    public function createComment($comContent){
        $this->cid = $this->Count(dirname(__DIR__).'/dataBaseFiles/comments') + 1;
        $commentAry=array("cid"=>$this->cid,"uid"=>$this->usersUid,"pid"=>$this->pid,"content"=>$comContent,"date"=>$this->date);
        $comment = new commentDB($commentAry);
        $comment->StorCidData();
    }
}
