<?php
include_once("./Database.php");
class Login{

    protected $db;
    
    function view() { ?>
    
        <h2>~LOGIN~</h2>

        <form action="" method="POST">
        <?php echo "ID " ?><input type="text" name="ID">
        <input type="submit" value="login"><br>
        <?php echo "PASSWORD " ?><input type="password" name="PASSWORD">
        </form>
        <a href="./register.php">JOIN</a>
        <a href="./index.php">HOME</a>

        <?php
    }

    function process(){
        $this->db = new Database();
        $this->db->connect();

        $ID = $_POST['ID'];
        $PASSWORD = $_POST['PASSWORD'];
        $this->db->login($ID,$PASSWORD);
    }

}

$main = new Login();

if(empty($_POST['ID']) || empty($_POST['PASSWORD'])) {
    $main->view();
}

else {
    $main->process();
}
?>