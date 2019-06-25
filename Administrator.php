<?php include_once("./Database.php");
include("./style.html");

class Administrator {
        protected $db;

        function view(){
            session_start();
            if(empty($_SESSION['ID']) || empty($_SESSION['PASSWORD'])) {
                ?><script>alert('Login plz!');
                window.location.href="./login.php"</script><?php
            }
            else {
                $ID = $_SESSION['ID'];
                
                if($ID !='admin'){
                    ?><script>
                    alert("Inaccessible! Only administrator");
                    window.location.href="./index.php";
                    </script>
                    
                    <?php
                }
            
                $PASSWORD = $_SESSION['PASSWORD'];

                $this->db = new Database();
                $this->db->connect(); ?>
                
                <table>
                <p><caption>Member</caption></p>
                <tr>
                        <th>ID</th>
                        <th>NICKNAME</th>
                        <th>PASSWORD</th>
                        <th>WITHDRAWAL</th>
                        <th>MODIFY PASSWORD</th>
                </tr>
                <?php 
                $this->db->adminPageMember();
                ?>
                <a href="./index.php">MAIN</a>
        <?php
        }

        
        if(isset($_GET['adminwithdrawal'])){
            $this->db->adminPageWithdrawal($_GET['adminwithdrawal']);
        }

        if(isset($_GET['modifypw'])){
            $ID = $_GET['modifypw'];
    
            ?><form method="POST">
            <input type='password' placeholder="ENTER PASSWORD" name="MODIFYPW">
            <input type='submit' value="MODIFY">
            </form><?php

            # After user write, modify nickname
            if(array_key_exists('MODIFYPW', $_POST)){
                $this->db->adminPageModifyPW($ID, $_POST['MODIFYPW']);
            }
        }
    }
} 

$main = new Administrator();
$main->view();


?>