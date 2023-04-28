<?php
/* 
1. this class is responsible for creating a post object that is stored in the database.
2. the constructor accepts the following parameters: the user's uid (unique identification), the title of the post, the content of the post, the image url of the post, the date of the post and the pid of the post.
3. the constructor initializes the post object.
4. the createPost() function is responsible for creating a new post object in the database.
5. the createImg() function is responsible for creating a new image object in the database.
6. the createComment() function is responsible for creating a new comment object in the database.
7. the Count() function is responsible for counting the number of files in a given folder.
8. the pidArray is an array that contains the pid, the uid, the title, the content, the date, the iid and the cid of the post.
9. the postDB is the database of the post object.
 */

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
