<?php
    //  echo $_GET['area_id'];
    define('PDO_DNS', 'mysql:dbname=myfriends;host=localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');

    $dbh = new PDO(PDO_DNS, DB_USERNAME, DB_PASSWORD);
    $dbh->query('SET NAMES utf8');


    $sql = sprintf('SELECT * FROM areas WHERE area_id = %s', $_GET['area_id']);
//    $sql = 'SELECT * FROM areas WHERE id = '.$_GET['area_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    $area_name = $rec['area_name'];

    // 友達リストを表示するためのSQL文
    $sql = sprintf('SELECT * FROM `friends` WHERE `area_id` = %d', $_GET['area_id']);
    // SQL文の実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    // 取得データ格納用Array
    $friends = array();
    // 男女カウント用変数
    $male = 0;
    $female = 0;

    while (1) {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rec == false) break;

      // データ格納
      $friends[] = $rec;

      if($rec['gender'] == 1) $male++;
      else $female++;
    }
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>myFriends</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>


  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
      <legend><?php echo $area_name; ?>の友達</legend>
      <div class="well">男性: <?php echo $male ?>名　女性： <?php echo $female ?>名</div>
        <table class="table table-striped table-hover table-condensed">
          <thead>
            <tr>
              <th><div class="text-center">名前</div></th>
              <th><div class="text-center"></div></th>
            </tr>
          </thead>
          <tbody>
            <!-- 友達の名前を表示 -->
            <?php foreach ($friends as $friend) { ?>
              <tr>
                <td><div class="text-center"><?php echo $friend['friend_name']; ?></div></td>
                <td>
                  <div class="text-center">
                    <a href="edit.php"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="javascript:void(0);" onclick="destroy();"><i class="fa fa-trash"></i></a>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>

        <input type="button" class="btn btn-default" value="新規作成" onClick="location.href='new.php'">
      </div>
    </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
