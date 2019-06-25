<?php include_once("./Database.php");
include("./style.html");

class SearchRecipe {
    protected $db;

        function view(){
            $this->db = new Database();
            $this->db->connect(); ?>
            
            <table>
            <p><caption>Search Result</caption></p>
            
            <tr>
                <th>NO</th>
                <th>제목</th>
                <th>닉네임</th>
                <th>날짜</th>
                <th>조회수</th>
            </tr>
            </table>
            
            <?php 
            $this->db->searchRecipe($_GET['TEXT']);
            ?>

            <a href="./index.php">MAIN</a>
            
            <?php
        }
} 

$main = new SearchRecipe();
$main->view();


?>