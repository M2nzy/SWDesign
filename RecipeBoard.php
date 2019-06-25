<?php
include_once('./Database.php'); 
include("./style.html");

class RecipeBoard{

    protected $db;

    function view(){
        if (empty($_GET['no'])) {
            ?><script> alert("Not Found!");
            window.location.href="./index.php";
        </script><?php
        } 
        else {
            $NO = $_GET['no'];
            $this->db = new Database();
            $this->db->connect();
            $this->db->boardView($NO);
            ?>
            
            <a href="./recipeboard.php?no=<?php echo $NO; ?>&remember=true">LIKE!</a>
            <a href="./index.php">MAIN</a>
            <a href="./recipeboard.php?no=<?php echo $NO; ?>&update=true">MODIFY</a>
            <a href="./recipeboard.php?no=<?php echo $NO; ?>&delete=true">DELETE</a><?php
        } 

        if(isset($_GET['remember'])){
            $this->preremember($NO);
        }

        if(isset($_GET['update'])){
            $this->update();
        }

        if(isset($_GET['delete'])){
            $this->delete();
        }
    }

    function write(){
        session_start();
        # login check
        if (empty($_SESSION['ID'])) {
            ?> <script> alert("Login plz!");
            window.location.href="./login.php";
        </script>
        <?php 
        } 

        else {
            $ID = $_SESSION['ID'];
            $this->db = new Database();
            $this->db->connect();
            $NICKNAME = $this->db->getNickname($ID);
            ?>
            
            <form method="POST">
            <?php echo "TITLE " ?>
            <input type="text" name="TITLE"><br />
    
            <strong><?php echo "NICKNAME : " . $NICKNAME ?><br /></strong>
    
            <?php echo "CONTENT " ?><br />
            <textarea name="CONTENT" rows="10" cols="60"></textarea><br />
            <input type="submit" value="WRITE" >
            </form>

            <a href="./index.php">MAIN</a>
            
            <?php # after user write, insert into db
            if(array_key_exists('TITLE',$_POST) && array_key_exists('CONTENT',$_POST)){
                $_POST['TITLE'] = nl2br($_POST['TITLE']);
                $_POST['CONTENT'] = nl2br($_POST['CONTENT']);
                $this->db->boardWrite($ID,$_POST['TITLE'],$_POST['CONTENT']);
            }
           
        }

    }
    
    function update(){
        session_start();
        if(isset($_GET['no'])) {
            if(isset($_SESSION['ID']) && isset($_SESSION['PASSWORD'])){
                $ID = $_SESSION['ID'];
                $PASSWORD = $_SESSION['PASSWORD'];
                $NO = $_GET['no'];

                $this->db = new Database();
                $this->db->connect();
                $this->db->boardUpdate1($NO, $ID); # get board info
            }
            else {
                ?><script>alert('Login plz!');
                window.location.href="./login.php";
                </script><?php
            }
        }      
    }
        

    function delete(){
        session_start();
        if(isset($_GET['no'])) {
            if(isset($_SESSION['ID']) && isset($_SESSION['PASSWORD'])){
                $ID = $_SESSION['ID'];
                $PASSWORD = $_SESSION['PASSWORD'];
                $NO = $_GET['no'];
        
                $this->db = new Database();
                $this->db->connect();
                $this->db->boardDelete($NO, $ID); # delete
            }
            else {
                ?><script>alert('Login plz!');
                window.location.href="./login.php";
                </script><?php
            }
        }    
    }

    function preremember($NO){
        session_start();
        # login check
        if (empty($_SESSION['ID'])) {
            ?> <script> alert("Login plz!");
            window.location.href="./login.php";
        </script>
        <?php 
        } 

        else {
            $ID = $_SESSION['ID'];
            $this->db = new Database();
            $this->db->connect();
            $this->db->remember($NO, $ID);
            
        }
    }
}


$main = new RecipeBoard();


if(isset($_GET['no'])) {
    $main->view();
}
else {
    $main->write();
}

?>