<?php

include_once('DataBase.php');
function getUserId($username)
{
    $dbb = new DataBase();
    $result = $dbb->sqli()->query("SELECT user FROM  `users` WHERE Username='{$username}'"); 
    //
    while($row = $result->fetch_assoc())
    {
        $userid = $row['user'];
    }
    return $userid;
}

class User
{
    public $userid = 0;
    public $email = "";
    public $username = "";
    public function setUsername($username_)
    {
        $this->username = $username_;
    }
    public function getUserName($_userid)
    {
        $this->userid = $_userid;
        $db = new DataBase();
        $result = $dbb->sqli()->query("SELECT Username FROM  `users` WHERE user='{$_userid}'"); 
        //
       while($row = $result->fetch_assoc())
       {
            $Username = $row['Username'];
            echo $Username;
            $username = $Username;
        }
       return $Username;
    }

    public function getUserProfilePicture()
    {
        $dbb = new DataBase();                      
        // Get image data from database 

        $result = $dbb->sqli()->query("SELECT ProfilePicture FROM  `users` WHERE Username='{$this->username}'"); 
         //
        while($row = $result->fetch_assoc())
        {
            if(empty($row['ProfilePicture']))
            {
                $img = "../Resorces/Images/UploadProfilepictureTemplate.png";
            }
            else
                $img = "data:image/jpg;charset=utf8;base64,".base64_encode($row['ProfilePicture']);
        }
        return $img;
    }

}
