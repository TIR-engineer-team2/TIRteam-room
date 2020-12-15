<?php
session_start();
require_once("config/config.php");
require_once("model/User.php");

// ログイン画面経由を確認
if(!isset($_SESSION['User'])){
  header('Location:/selfmade/login.php');
  exit;
}elseif(empty($_POST)){
  header('Location:/selfmade/adduser.php');
  exit;
}

// HTML特殊関数エスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$_SESSION['user'] = h($_POST['user']);
$_SESSION['password'] = h($_POST['password']);
$_SESSION['mail'] = h($_POST['mail']);
$_SESSION['role'] = h($_POST['role']);
$_SESSION['shop'] = h($_POST['shop']);

try{
  $user = new User($host,$dbname,$user,$pass);
  $user->connectDB();

  if($_POST){
    $message = $user->validate($_POST);
    if($message){
      $_SESSION['error'] = $message;
      header('Location:'.$_SERVER['HTTP_REFERER']);
      exit();
    }else{
      $_SESSION['error'] = array();
    }
  }

}catch(PDOException $e){
  echo "エラー！" .$e ->getMessage();
  die();
}
 ?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ユーザ登録確認｜Handbook</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <link rel = "stylesheet" href = "css/bootstrap.css">
    <link rel = "stylesheet" href = "css/base.css">
    <link rel = "stylesheet" href = "css/confirm.css">
    <script type = "text/javascript" src = "js/jquery-3.5.1.js"></script>
    <script type = "text/javascript" src = "js/bootstrap.js"></script>
  </head>
  <body>
    <?php require("require/myheader.php") ?>

    <div class = "wrapper">
      <h1>新規ユーザ登録</h1>
      <p>この内容でよろしいですか</p>


        <table>
          <tr>
            <th>ユーザID</th>
            <td><?=$_SESSION['user']?></td>
          </tr>
          <tr>
            <th>パスワード</th>
            <td><?=$_SESSION['password']?></td>
          </tr>
          <tr>
            <th>メールアドレス</th>
            <td><?=$_SESSION['mail']?></td>
          </tr>
          <tr>
            <th>所属</th>
            <td><?php if($_SESSION['role'] == 0):?>店舗
            <?php elseif($_SESSION['role'] == 1):?>商品課
            <?php endif?></td>
          </tr>
          <!-- 所属が店舗だった時表示 -->
          <tr>
            <th>店舗名</th>
            <td><?=$_SESSION['shop']?></td>
          </tr>
        </table>

      <form action = "usercomp.php" method = "POST">
        <!-- 送信用隠しフォーム -->
        <input type = "hidden" name = user value = "<?=$_SESSION['user']?>">
        <input type = "hidden" name = password value = "<?=$_SESSION['password']?>">
        <input type = "hidden" name = mail value = "<?=$_SESSION['mail']?>">
        <input type = "hidden" name = role value = "<?=$_SESSION['role']?>">
        <input type = "hidden" name = shop value = "<?=$_SESSION['shop']?>">
        <div class = "center">
          <input type = "submit" value = "登録" class="btn btn-primary">
        </div>
      </form>

      <div class = "center">
        <a href = "adduser.php">戻る</a>
      </div>

    </div>

    <?php require("require/footer.php") ?>
  </body>
</html>
