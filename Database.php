<?php
include("./style.html");

class Database {
    protected $conn, $sql, $NICKNAME;

    public function connect() {
        $this->conn = mysqli_connect('localhost','root','password','swdesign')
        or die("connect error");
        return $this->conn;
    }

    public function index() { 
        $this->sql = "SELECT * FROM board ORDER BY no DESC";
        $result = mysqli_query($this->conn, $this->sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                        $NO = $row['no'];
                        $NICKNAME = $row['nickname'];
                        $TITLE = $row['title'];
                        $DATE = $row['date'];
                        $HIT = $row['hit'];
                        ?>
                <table>
                    <tr>
                        <td><?php echo $NO ?></td>
                        <td><a href="./recipeboard.php?no=<?php echo $NO ?>"><?php echo $TITLE ?></a></td>
                        <td><?php echo $NICKNAME ?></td>
                        <td><?php echo $DATE ?></td>
                        <td><?php echo $HIT ?></td>
                    </tr>
                </table>
        <?php                            
            }
        } 
    }

    public function join($ID, $NICKNAME, $PASSWORD){
        $this->sql = "SELECT * FROM member where id='$ID'";
        $result = mysqli_query($this->conn, $this->sql) or die("sql error");

        if (mysqli_num_rows($result) != 0) {
            ?>
            <script>alert("This ID already exists!");
            history.back();</script>
            <?php
        } 
        
        else {
            $this->sql = "INSERT INTO member (id, nickname, password) VALUES ('$ID', '$NICKNAME', '$PASSWORD')";
            $result = mysqli_query($this->conn, $this->sql) or die("sql error");
            if ($result) {
                ?><script>alert("Join Success!");
                location.replace('./index.php');
                </script>
                <?php
            } else {
                ?><script>alert("Join Fail!");
                location.replace('./register.php');
                </script>
        <?php }
        }
    }

    public function login($ID, $PASSWORD){
        $this->sql = "SELECT * FROM member where id='$ID'";
        $result = mysqli_query($this->conn, $this->sql) or die("query error");

        if (mysqli_num_rows($result) == 0) {
            ?><script>
            alert("Login fail! ID Not Found");
            window.location.href="./login.php";
            </script>
            <?php

        } else {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['password'] === $PASSWORD) {
                    session_start();
                    $_SESSION['ID'] = $ID;
                    $_SESSION['PASSWORD'] = $PASSWORD;

                    ?><script> alert("Login Success!");
                    window.location.href = "./index.php";
                    </script>
                    <?php

                } else {
                    ?><script> alert("Login fail! Incorrect Password");
                    window.location.href="./login.php";
                    </script><?php
                }
            }
        }
    }

    public function getNickname($ID){
        $this->sql = "SELECT * FROM member where id='$ID'";
        $result = mysqli_query($this->conn, $this->sql) or die("query error");
        if (mysqli_num_rows($result) >= 1) {
            $row = mysqli_fetch_assoc($result);
            $this->NICKNAME = $row['nickname'];
            return $this->NICKNAME;
        }
        else {
            ?><script> alert("inaccessible!");
            history.back();</script><?php
        }
    }

    public function boardView($NO){
        $this->sql = "SELECT * FROM board WHERE no=$NO";
        $result = mysqli_query($this->conn, $this->sql) or die("query");
        if (mysqli_num_rows($result) >= 1) {
            $row = mysqli_fetch_assoc($result);
            ?><p><?php echo "TITLE : " . $row['title']; ?></p>
            <p><?php echo "DATE : " . $row['date']; ?> </p>
            <p><?php echo "NICKNAME : " . $row['nickname'];
            echo "\tHIT : " . $row['hit']; ?> </p>
            <p><?php echo $row['content']; ?> </p>
            <?php $this->sql = "UPDATE board SET hit=hit+1 WHERE no=$NO";
            $result = mysqli_query($this->conn, $this->sql);
        }
        else {
            ?><script> alert("Not Found!");
            window.location.href="./index.php";
            </script><?php
        }

    }

    public function boardWrite($ID, $TITLE, $CONTENT){
        $ID = $_SESSION['ID'];
        $PASSWORD = $_SESSION['PASSWORD'];

        #LAST NO check
        $this->sql = "SELECT max(no)+1 FROM board";
        $result = mysqli_query($this->conn, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['max(no)+1'] == null)
            $NO = 1;
        else
            $NO = $row['max(no)+1'];

        $this->sql = "INSERT INTO board (no, id, nickname, password, title, content, date) VALUES ($NO,'$ID','$this->NICKNAME','$PASSWORD','$TITLE','$CONTENT',now())";

        $result = mysqli_query($this->conn, $this->sql) or die("query error");

        if ($result) {
            ?><script> alert("WRITE SUCCESS!");
                        window.location.href="./index.php";
        </script><?php
        }

    }

    public function boardUpdate1($NO, $ID){
        # screen clear
        ob_get_clean();

        # get board info
        $this->sql = "SELECT * FROM board WHERE no=$NO";
        $result = mysqli_query($this->conn, $this->sql);

        if (mysqli_num_rows($result) >= 1) {
            $row = mysqli_fetch_assoc($result);
            if (($row['id'] === $ID) or ($ID ==="admin")) {
                $TITLE = $row['title'];
                $CONTENT = $row['content'];
                $NICKNAME = $row['nickname'];
                
                ?><form method="POST">
                <?php echo "TITLE " ?>
                <input type="text" name="MODIFYTITLE" value="<?php if (isset($TITLE)) echo $TITLE; ?>"><br />
                <strong><?php echo "NICKNAME : " . $NICKNAME ?><br /></strong>
                <?php echo "CONTENT " ?><br />
                <textarea name="MODIFYCONTENT" rows="10" cols="60"><?php if (isset($CONTENT)) echo $CONTENT; ?></textarea><br />
                <input type="submit" value="WRITE">
                </form>

                <?php # update
                if(array_key_exists('MODIFYTITLE',$_POST) && array_key_exists('MODIFYCONTENT',$_POST)){
                    $_POST['MODIFYTITLE'] = nl2br($_POST['MODIFYTITLE']);
                    $_POST['MODIFYCONTENT'] = nl2br($_POST['MODIFYCONTENT']);
                    $this->boardUpdate2($NO,$_POST['MODIFYTITLE'],$_POST['MODIFYCONTENT']);
                }

            } else {
                ?><script> alert("inaccessible!");
                                history.back();
                </script><?php 
            }
        } else {
            ?><script> alert("Not Found!");
            window.location.href="./index.php";
            </script><?php 
        }
    }
        

    public function boardUpdate2($NO, $TITLE, $CONTENT){
        # update board
        $this->sql = "UPDATE board SET title='$TITLE', content='$CONTENT' WHERE no=$NO";
        $result = mysqli_query($this->conn, $this->sql);
        if($result){
            ?><script>
            alert("MODIFY SUCCESS!");
            window.location.href="./index.php";
            </script><?php
        }
    }

    public function boardDelete($NO, $ID){
        $ID = $_SESSION['ID'];
        $PASSWORD = $_SESSION['PASSWORD'];
        
        #ID check
        $this->sql = "SELECT * FROM board WHERE no=$NO";
        $result = mysqli_query($this->conn, $this->sql);
        if (mysqli_num_rows($result) >= 1) {
            $row = mysqli_fetch_assoc($result);
            if (($row['id'] === $ID) or ($ID ==="admin")) {
                #DELETE QUERY
                $this->sql = "DELETE FROM board WHERE no=$NO";
                $result = mysqli_query($this->conn, $this->sql);
                if (mysqli_affected_rows($this->conn) == 1) {
                    # DELETE REMEMBER
                    $this->sql = "DELETE FROM member WHERE no=$NO";
                    $result = mysqli_query($this->conn, $this->sql);
                    
                    ?><script>alert("DELETE SUCCESS!");
                    window.location.href="./index.php";
                    </script><?php
                } 
                else {
                    ?><script>alert("DELETE FAIL!");
                    window.location.href="./index.php";
                    </script><?php
                }
            }
            else {
                ?><script> alert("Inaccessible!");
                window.location.href="./recipeboard.php?no=<?php echo $NO; ?>";
                </script><?php 
            }
        }
        else {
        ?><script> alert("Not Found!");
        window.location.href="./index.php";
        </script><?php 
        }
        
    }

    public function remember($NO, $ID){
        $ID = $_SESSION['ID'];
        $PASSWORD = $_SESSION['PASSWORD'];
        $NICKNAME = $this->getNickname($ID);
        $this->sql = "INSERT INTO member (id, nickname, password, no) VALUES ('$ID','$NICKNAME','$PASSWORD',$NO)";
        $result = mysqli_query($this->conn, $this->sql);
        if(mysqli_affected_rows($this->conn) == 1){
            ?><script>alert("REMEMBER SUCCESS!");
            window.location.href="./recipeboard.php?no=<?php echo $NO; ?>"</script><?php
        }
    }

    public function myPageView($ID, $PASSWORD){
        $this->sql = "select * from member where id='$ID'";
        $result = mysqli_query($this->conn, $this->sql);
        if (mysqli_affected_rows($this->conn) >= 1) { # Because of Remember NO
            $row = mysqli_fetch_assoc($result);
            $ID = $row['id'];
            $NICKNAME = $row['nickname'];
            $PASSWORD = $row['password'];
        }
        ?>
                <p><b>ID</b> : <?php echo $ID ?></p>
                <p><b>NICKNAME</b> : <?php echo $NICKNAME ?></p>
                <p><b>PASSWORD</b> : <?php echo $PASSWORD?></p>
                
        <?php
    }


    public function myPageNick($ID, $MODIFYNICKNAME){
        $this->sql = "UPDATE member SET nickname='$MODIFYNICKNAME' WHERE id='$ID'";
        $result = mysqli_query($this->conn, $this->sql);
        if ($result){
            ?><script>alert('MODIFIED!')
            window.location.href="./mypage.php"</script><?php
        }
    }

    public function myPagePW($ID, $MODIFYPW){
        $this->sql = "UPDATE member SET password='$MODIFYPW' WHERE id='$ID'";
        $result = mysqli_query($this->conn, $this->sql);
        if ($result){
            ?><script>alert('MODIFIED!')
            window.location.href="./mypage.php"</script><?php
        }
    }

    public function myPageDrop($ID){
        $this->sql = "DELETE FROM member WHERE id='$ID'";
        $result = mysqli_query($this->conn, $this->sql);
        if ($result){
            ?><script>alert('WITHDRAWAL SUCCESS!')
            window.location.href="./index.php"</script><?php
        }
        session_destroy();
    }

    public function myPageBoard($ID){
        $this->sql = "SELECT * FROM board WHERE id='$ID' ORDER BY no DESC";
        $result = mysqli_query($this->conn, $this->sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $NO = $row['no'];
                $NICKNAME = $row['nickname'];
                $TITLE = $row['title'];
                $DATE = $row['date'];
                $HIT = $row['hit'];
                ?>
                
                <table>
                    <tr>
                        <td><?php echo $NO ?></td>
                        <td><a href="./recipeboard.php?no=<?php echo $NO ?>"><?php echo $TITLE ?></a></td>
                        <td><?php echo $NICKNAME ?></td>
                        <td><?php echo $DATE ?></td>
                        <td><?php echo $HIT ?></td>
                    </tr>
                </table>
                <?php
            }
        }
    }

    public function myPageRemember($ID){
        $this->sql = "SELECT * FROM member WHERE id='$ID' ORDER BY no DESC";
        $result = mysqli_query($this->conn, $this->sql);
        $row_total = mysqli_num_rows($result);
        # row_total : basic info + remember info => greater than or equal to 1

        if ($result) {
            $REMEMBERNO = array();

            for ($i = 2; $i <= $row_total; $i = $i+1) {
                $row = mysqli_fetch_assoc($result);
                $j = $i - 1;
                $REMEMBERNO[$j] = $row['no'];
            }
            
            for($i = 2; $i <= $row_total; $i = $i+1) {
                $j = $i - 1;
                $this->sql = "SELECT * FROM board WHERE no=$REMEMBERNO[$j]";
                $result = mysqli_query($this->conn, $this->sql);
                
                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    
                    $NO = $row['no'];
                    $NICKNAME = $row['nickname'];
                    $TITLE = $row['title'];
                    $DATE = $row['date'];
                    $HIT = $row['hit'];
                }
                ?>

                <table>
                    <tr>
                        <td><?php echo $NO ?></td>
                        <td><a href="./recipeboard.php?no=<?php echo $NO ?>"><?php echo $TITLE ?></a></td>
                        <td><?php echo $NICKNAME ?></td>
                        <td><?php echo $DATE ?></td>
                        <td><?php echo $HIT ?></td>
                    </tr>
                </table>
                <?php
            }
        }

    }

    public function adminPageMember(){
        $this->sql = "SELECT * FROM member WHERE no is NULL";
        $result = mysqli_query($this->conn, $this->sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ID = $row['id'];
                $NICKNAME = $row['nickname'];
                $PASSWORD = $row['password'];
                ?>
                
                <table>
                    <tr>
                        <td><?php echo $ID ?></td>
                        <td><?php echo $NICKNAME ?></td>
                        <td><?php echo $PASSWORD ?></td>
                        <td><a href="./administrator.php?adminwithdrawal=<?php echo $ID ?>">WITHDRAWAL</a></td>
                        <td><a href="./administrator.php?modifypw=<?php echo $ID ?>">MODIFY PASSWORD</a></td>
                    </tr>
                </table>
                <?php
            }   
        }

    }

    public function adminPageWithdrawal($ID){
        if($_SESSION['ID']=="admin"){
            $this->sql = "DELETE FROM member WHERE id='$ID'";
            $result = mysqli_query($this->conn, $this->sql);
            
            if($result){
                ?><script>
                alert('WITHDRAWAL SUCCESS!');
                window.location.href="./administrator.php";
                </script><?php
            }
        }
        else {
            ?><script>
            alert("Inaccessible!");
            window.location.href="./index.php";
            </script><?php
        }
    }

    public function adminPageModifyPW($ID,$PASSWORD){
        if($_SESSION['ID']=='admin'){
            $this->sql = "UPDATE member SET password='$PASSWORD' WHERE ID='$ID'";
            $result = mysqli_query($this->conn, $this->sql);

            if ($result){
                ?><script>
                alert("MODIFIED!");
                window.location.href="./administrator.php"
                </script><?php
            }
        }
        else {
            ?><script>
            alert("Inaccessible!");
            window.location.href="./index.php";
            </script><?php
        }
    }

    public function searchRecipe($TEXT){
        $this->sql = "SELECT * FROM board WHERE title like '%$TEXT%'";
        $result = mysqli_query($this->conn, $this->sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $NO = $row['no'];
                $NICKNAME = $row['nickname'];
                $TITLE = $row['title'];
                $DATE = $row['date'];
                $HIT = $row['hit'];
                ?>
                
                <table>
                    <tr>
                        <td><?php echo $NO ?></td>
                        <td><a href="./recipeboard.php?no=<?php echo $NO ?>"><?php echo $TITLE ?></a></td>
                        <td><?php echo $NICKNAME ?></td>
                        <td><?php echo $DATE ?></td>
                        <td><?php echo $HIT ?></td>
                    </tr>
                </table>
                <?php
            }
        }
    }
}