<?php include_once("./Database.php");
include("./style.html");

class Mypage {
        protected $db;

        function view(){
            session_start();
            if(empty($_SESSION['ID']) || empty($_SESSION['PASSWORD'])) {
                ?><script>alert('Login plz!');
                window.location.href="./login.php"</script><?php
            }
            else {
                $ID = $_SESSION['ID'];
                $PASSWORD = $_SESSION['PASSWORD'];
                $this->db = new Database();
                $this->db->connect();
                $this->db->myPageView($ID, $PASSWORD); ?>
                <p><a href="./index.php">MAIN</a></p>
                <p><a href="./mypage.php?nickname=true">NICKNAME MODIFY</a>
                <a href="./mypage.php?password=true">PASSWORD MODIFY</a></p>
                <p><a href="./mypage.php?out=true">!!!WITHDRAWAL!!!</a></p>

                <table>
                <p><caption>My RecipeBoard</caption></p>
                <tr>
                        <th>NO</th>
                        <th>제목</th>
                        <th>닉네임</th>
                        <th>날짜</th>
                        <th>조회수</th>
                </tr>
                </caption>
                <?php $this->myBoard($ID); ?>

                <table>
                <p><caption>My Remember Board</caption></p>
                <tr>
                        <th>NO</th>
                        <th>제목</th>
                        <th>닉네임</th>
                        <th>날짜</th>
                        <th>조회수</th>
                </tr>
                </table>
                <?php
                $this->myRemember($ID);
                
            }

            if(isset($_GET['out'])){
                $ID = $_SESSION['ID'];
                $this->withdrawal($ID);
            }

            if(isset($_GET['nickname'])){
                $ID=$_SESSION['ID'];
                $this->nickProcess($ID);
            }

            if(isset($_GET['password'])){
                $ID = $_SESSION['ID'];
                $this->pwProcess($ID);
            }
        }

        function nickProcess($ID){
            ?><form method="POST">
                <input type='text' placeholder="ENTER NICKNAME" name="MODIFYNICKNAME">
                <input type='submit' value="MODIFY">
            </form><?php

            # After user write, modify nickname
            if(array_key_exists('MODIFYNICKNAME', $_POST)){
                $this->db->myPageNick($ID,$_POST['MODIFYNICKNAME']);
            }
        }

        function pwProcess($ID){
            ?><form method="POST">
                <input type='password' placeholder="ENTER PASSWORD" name="MODIFYPW">
                <input type='submit' value="MODIFY">
            </form><?php

            # After user write, modify nickname
            if(array_key_exists('MODIFYPW', $_POST)){
                $this->db->myPagePW($ID,$_POST['MODIFYPW']);
            }
        }

        function withdrawal($ID){
            $this->db->myPageDrop($ID);
        }

        function myBoard($ID){
            $this->db->myPageBoard($ID);
        }

        function myRemember($ID){
            $this->db->myPageRemember($ID);
        }

} 


$main = new Mypage();
$main->view();


?>


