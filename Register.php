<?php
include_once("./Database.php");

class Register{
    protected $db;

    function view(){ ?>
        <h2>-JOIN-</h2>

        <form action="" method="POST">
        <p><?php echo "ID " ?><input type="text" name="ID"></p>
        <p><?php echo "NICKNAME " ?><input type="text" name="NICKNAME"></p>
        <p><?php echo "PASSWORD " ?><input type="password" name="PASSWORD">
        <input type="submit" value="JOIN"></p>
        </form>
        <a href="./index.php">HOME</a>
        
    <?php
    }

    function process(){
        $this->db = new Database();
        $this->db->connect();

        $ID = $_POST['ID'];
        $PASSWORD = $_POST['PASSWORD'];
        $NICKNAME = $_POST['NICKNAME'];
        $this->db->join($ID,$NICKNAME,$PASSWORD);
        
    }
}


$main = new Register();

if(empty($_POST['ID']) || empty($_POST['NICKNAME']) || empty($_POST['PASSWORD'])) {
    $main->view();
}

else {
    $main->process();
}
?>