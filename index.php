<?php include_once("./Database.php");
include("./style.html");

class Index {
        protected $db;

        function on(){
                session_start();
                $this->db = new Database();
                $this->db->connect();
                ?>

                <h2>~Main~</h2>
                
                <form method='get' action='./searchrecipe.php'>
                        <input type="text" name="TEXT" placeholder="SEARCH RECIPE">
                        <input type="submit" value="SEARCH">
                </form>
                <table>
                        <tr>
                                <th>NO</th>
                                <th>제목</th>
                                <th>닉네임</th>
                                <th>날짜</th>
                                <th>조회수</th>
                        </tr>
                </table>
                <?php
                $this->db->index();

                ?>
                <a href="./register.php">JOIN</a>
                <a href="./login.php">LOGIN</a>
                <a href="./recipeboard.php">WRITE</a>
                <a href="./recommrecipe.php">RECOMMEND RECIPE</a>
                

                <?php
                if(isset($_SESSION['ID'])) {
                        ?>        
                        <a href="./Mypage.php">MYPAGE</a>
                        <a href="./index.php?LOGOUT=true">LOGOUT</a>
                        <?php
                        
                        if($_SESSION['ID']=='admin'){
                                ?><a href="./administrator.php">ADMIN</a>
                                <?php
                        }
                }

                if(isset($_GET['LOGOUT'])){
                        if(isset($_SESSION['ID']) && isset($_SESSION['PASSWORD'])){
                                session_destroy(); ?>
                                <script> 
                                alert('Logout Success!');
                                window.location.href="./index.php"; </script> <?php
                        }
 
                        else {
                                ?><script>
                                alert('Login Plz!');
                                window.location.href="./login.php";
                                </script>
                                <?php
                        }
                }
        }

} 


$main = new Index();
$main->on();


?>


