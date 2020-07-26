<?php
//データベースへ接続
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>
PDO::ERRMODE_WARNING));
?>

<?php

//テーブルを作成
$sql="CREATE TABLE IF NOT EXISTS tbnewdata"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."com TEXT,"
."date datetime,"
."password char(255) NOT NULL"
.");";
$stmt=$pdo->query($sql);

//変数の初期化
 if(isset($_POST["name"]))
 {$name=$_POST["name"];}
 if(isset($_POST["com"]))
 {$com=$_POST["com"];}
 if(isset($_POST["delnum"]))
 {$delid=$_POST["delnum"];}
 if(isset($_POST["editnum"]))
 {$editid=$_POST["editnum"];}
 if(isset($_POST["editnum2"]))
 {$editnum2=$_POST["editnum2"];}
 if(isset($_POST["pass"]))
 {$pass=$_POST["pass"];}
 if(isset($_POST["pass2"]))
 {$pass2=$_POST["pass2"];}
 if(isset($_POST["pass3"]))
 {$pass3=$_POST["pass3"];}
 
 
 
 $date=date('Y-m-d h:i:s');
 
//編集処理
if(isset($_POST["send_edit"])&& isset($editid))
{
    $sql= "SELECT password FROM tbnewdata WHERE id = $editid";
    $stmt=$pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach($results as $row)
    {}
    if($pass3==$row['password'])
        { 
            
            
          $sql= "SELECT id,name,com FROM tbnewdata WHERE id = $editid";
          $stmt=$pdo->query($sql);
          $results= $stmt -> fetchAll();
          foreach($results as $row)
            {
                $num2=$row['id'];
                $name2=$row['name'];
                $com2=$row['com'];
            }
          }
     else
      {echo "パスワード不一致";}
}

//INSERT文でデータ書き込み
if(isset($_POST["submit"]))
{if(empty($editnum2))
  {if(isset($pass))
    {
      $sql=$pdo->prepare("INSERT INTO tbnewdata
      (name,com,date,password)
       VALUES (:name,:com,:date,:password)");
       $sql->bindParam(':name',$name,PDO::PARAM_STR);
       $sql->bindParam(':com',$com,PDO::PARAM_STR);
       $sql->bindParam(':date',$date,PDO::PARAM_STR);
       $sql->bindParam(':password',$pass,PDO::PARAM_STR);
       $sql-> execute();
    }
  }
    
//データ編集
  elseif(!empty($editnum2))
   {
    $sql="UPDATE tbnewdata SET name=:name,com=:com,
    date=:date, password=:password WHERE id= $editnum2";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam(':name',$name,PDO::PARAM_STR);
    $stmt->bindParam(':com',$com,PDO::PARAM_STR);
    $stmt->bindParam(':date',$date,PDO::PARAM_STR);
    $stmt->bindParam(':password',$pass,PDO::PARAM_STR);
    $stmt->execute();
  }
 }



//DELETE文で指定された番号を消す
if(isset($_POST["send_delete"]))
      {
          $sql= "SELECT password FROM tbnewdata WHERE 
          id = $delid";
          $stmt=$pdo->query($sql);
          $results = $stmt->fetchAll();
          foreach($results as $row)
          {}
          
          if($pass2==$row['password'])
          {
           $sql='DELETE FROM tbnewdata WHERE id=:id';
           $stmt=$pdo -> prepare($sql);
           $stmt -> bindParam(':id',$delid,PDO::PARAM_INT);
           $stmt->execute();
          }
      else
        {echo "パスワード不一致";}
      }
      



//投稿フォーム
?>

<html>
    <head>
        <meta charset="utf-8"/>
    </head>
    <body>
        <form method="POST" action="">
            
         <入力フォーム><br>
         名前：<input type="name" name="name" 
         value="<?php if(isset($_POST["send_edit"])
         && isset($name2))
         {echo $name2;} ?>" required> <br>
         
         コメント：<input type="comment" name="com"
         value="<?php if(isset($_POST["send_edit"])
         && isset($com2))
         {echo $com2;} ?>" required><br>
         
         <input type="hidden" name="editnum2"
         value="<?php if(isset($_POST["send_edit"])
         && isset($num2))
         {echo $num2;} ?>">
         
         パスワード：<input type="text" name="pass" required><br>
         
         <input type="submit" name="submit" value="送信">
         </form><br>
         
         <form method="POST" action="">
          <削除フォーム><br>
         削除対象番号：<input type="number" name="delnum" 
         placeholder="削除対象番号" required><br>
         
         パスワード：<input type="text" name="pass2" required><br>
         <input type="submit" name="send_delete" value="削除"><br>
         </form><br>
         
         <form method="POST" action="">
         <編集フォーム><br>
         編集対象番号：<input type="number" name="editnum"
         placeholder="編集対象番号"><br>
         
         パスワード：<input type="text" name="pass3" required><br>
         <input type="submit" name="send_edit" value="編集">
         </form>
         </body>
</html>
<?php 




     //INSERT内容をブラウザで確認
        $sql='SELECT*FROM tbnewdata';
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        foreach($results as $row)
        {
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['com'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
}
 ?>