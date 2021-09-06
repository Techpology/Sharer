<?php
  session_start();

?>

<!doctype html>
<html lang="en">
  <head >
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/Library.css" />
    <link rel="stylesheet" href="../css/MainUi.css" />
    <script type="text/javascript" src="../js/SidebarNavigate.js"></script>

    <title>Friends</title>
  </head>
  <body>
  <script>
        function showMenu()
        {
          var sidebar = document.getElementById("Menu");
            if(sidebar.style.width=="0px")
            {
              sidebar.style.marginLeft = "0px";
              sidebar.style.width = "200px";
            }
            else
            {
              sidebar.style.width = "0px";
              sidebar.style.marginLeft = "-300px";
            }
        }
     </script>

    <div class="header" ></div>
        
    <!-- Sidebar  -->

    <div id="Menu" onmouseleave="showMenu()" class="sidebar col" >
      <div class="row" style="text-align: right; margin-right: 20px; margin-top: 100px; " >
      <a onmousedown="navigate(this);" id="home" class="sidebarText" style="margin-top: 20px;" >Home</a><hr>
        <a onmousedown="navigate(this);" id="Upload" class="sidebarText" style="margin-top: 20px;" >Upload</a><hr>
        <a onmousedown="navigate(this);" id="Library" class="sidebarText" style="margin-top: 20px;" >Library</a><hr>
        <a onmousedown="navigate(this);" id="Friends" class="sidebarText" style="margin-top: 20px;" >Friends</a>
        <a onmousedown="navigate(this);" id="index" class="sidebarText" style="margin-top: 20px; bottom: 120px; position: absolute; " >Logout</a><hr>
        <script>
          //Set the selected page in menu
          //document.getElementById(window.location.pathname);
          var url = window.location.pathname;
          var array = url.split('/');
    
          var lastsegment = array[array.length-1];
          var el = document.getElementById(lastsegment.split('.')[0]);
          el.style.color = "rgba(75, 144, 194, 1)";
        </script>
      </div>      
      <footer style="position: absolute;" class="footer" style="bottom: 0;">
      <img style="width:50px;height: 50px;" src="<?php
      
      include('../PHP/DataBase.php');
      $db = new DataBase();
      $result = $db->get("SELECT profilepicture FROM Users WHERE Username='"."{$_SESSION['username']}"."'");

      echo '../Resorces/Images/HappyPeople2.png'
      
      ?>" alt="">
      <?php  echo $_SESSION['username']?>
      </footer>
    </div>
    <img onclick="showMenu()" onmouseenter="showMenu()" class="HamburgerButton" src="../Resorces/Icons/icons8-menu-384-blue.png" alt="">

    <!-- Top  -->

    <div style=" text-align: center; " >
      <div class="topbar" >
        <div>
          <img class="UpperLogo" src="../Resorces/Images/Logo.png" alt="">
        </div>
      </div>
    </div>

    <!-- From here  -->
    <div style="text-align: center;"  >
      
      <div class="middle" style="width: 50%; display:inline-block;margin-top: 20px;" class="btn btn-primary" >  
      <nav class="nav nav-pills nav-fill">
        <a class="nav-link" href="Friends.php">Friends</a>
        <a class="nav-link" href="AddFriend.php">Add Friend</a>
        <a class="nav-link active" aria-current="page" href="#">Requests</a>
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Blocked</a>
      </nav>

        <div>
          <table name="table" class="table table-striped">
              <tbody name="tablebody">
              <?php
              //Search for the users friends
              include_once('../PHP/DataBase.php');
              include_once('../PHP/User.php');

              $dB = new DataBase();   
              $userfriendsresult = $dB->sqli()->query("SELECT * FROM `{$_SESSION['username']}friends` WHERE FriendRequest=1");
              while($row = $userfriendsresult->fetch_assoc())
              {
                  echo '<tr><th scope="row">1</th>';
                  $result = $dB->sqli()->query("SELECT * FROM `Users` WHERE user={$row['FriendUserid']}");
                  while($user = $result->fetch_assoc())
                  {                                 

                      $Myuser = new User();
                      $Myuser->setUsername($user['Username']);
                      echo "<form action='FriendRequest.php' method='POST' ><td><img name='img' style='width: 40px; height: 40px;' src='{$Myuser->getUserProfilePicture()}' /></td> \n";
                      echo "<td><input type='text' class='indexInputHidden' readonly='readonly' name='FriendUsername' value='{$user['Username']}' ></td>\n";
                      echo '<td><input type="submit" name="Accept" value="Accept" class="btn btn-primary" ><input class="btn btn-danger" type="submit" name="Decline" value="Decline" ></td></form></tr>';
                  }
              }
              if(array_key_exists('Accept', $_POST)) {
                    Accept();
                }
                else if(array_key_exists('Decline', $_POST)) {
                    Decline();
                }
                function Accept() {
                    //Set friendrequest to 0
                    if(isset($_POST['FriendUsername'])){
                        $dB = new DataBase();
                        $friendidrs =$dB->sqli()->query("SELECT * FROM `Users` WHERE Username='{$_POST['FriendUsername']}' ");
                        while($row = $friendidrs->fetch_assoc())
                        {
                            $friendid = $row['user'];
                        }
                        $res = $dB->sqli()->query("UPDATE `{$_SESSION['username']}friends` SET `FriendRequest`='0' WHERE FriendUserid = '{$friendid}'");
                    }
                }
                function Decline() {
                    //Remove from friend from user 1 and two
                    if(isset($_POST['FriendUsername'])){
                        $dB = new DataBase();
                        $friendidrs =$dB->sqli()->query("SELECT * FROM `Users` WHERE Username='{$_POST['FriendUsername']}' ");
                        while($row = $friendidrs->fetch_assoc())
                        {
                            $friendid = $row['user'];
                        }
                        $useridrs =$dB->sqli()->query("SELECT * FROM `Users` WHERE Username='{$_SESSION['username']}' ");
                        while($row = $useridrs->fetch_assoc())
                        {
                            $userid = $row['user'];
                        }
                        //                                              sagafriends                                       13
                        $userRem = $dB->sqli()->query("DELETE FROM `{$_SESSION['username']}friends` WHERE FriendUserid='{$friendid}' ");
                        //                                              spynet                                            12
                        echo $_POST['FriendUsername'];
                        $friendRem = $dB->sqli()->query("DELETE FROM `{$_POST['FriendUsername']}friends` WHERE FriendUserid='{$userid}' ");
                    }
                }
            ?>
              </tbody>            
            </table>
          </div>  
      </div>
    </div>
    <!-- To here is the middle of the page -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>  
  
  </body>
 
</html>
