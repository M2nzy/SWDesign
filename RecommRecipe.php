<?php

class RecommRecipe{
    
    function view(){
        ?>
        <form method="POST">
        <input type="text" name="FOOD" placeholder="ENTER FOOD">
        <input type="submit" value="search">
        </form>
        <a href="./index.php">MAIN</a>
        
        <?php
        if(array_key_exists('FOOD', $_POST)){
            $this->naverAPI($_POST['FOOD']);
        }
    }


    function naverAPI($FOOD){
        $client_id = "KHX02RTV4btlpZdDkD_o";
        $client_secret = "R2nCyschPZ";
        $SEARCH = $FOOD." 만드는 법";
        $encText = urlencode("$SEARCH");
        
        $url = "https://openapi.naver.com/v1/search/encyc.xml?query=".$encText; // json 결과과
        $is_post = false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = "X-Naver-Client-Id: ".$client_id;
        $headers[] = "X-Naver-Client-Secret: ".$client_secret;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close ($ch);
        
        if($status_code == 200) {
            $result = simplexml_load_string($response);
            ?><h2><?php echo "검색하신 [$FOOD] 에 대한 레시피 입니다."; ?></h2>
            <h3><?php echo "클릭하면 해당 레시피로 이동합니다."; ?></h3><?php
            for($i = 0; $i < 10; $i = $i + 1){
                $img_src = $result->channel->item->$i->thumbnail;
                
                ?>
                <p><a href="<?php echo $result->channel->item->$i->link; ?>"><?php echo $result->channel->item->$i->title; ?></a></p>
                <p><img src="<?php echo $img_src ?>"></p>
                <p><?php echo nl2br($result->channel->item->$i->description); ?></p>
                <?php
            }

        } else {
            echo "Error 내용:".$response;
        }
    }
}

$main = new RecommRecipe();
$main->view();

?>