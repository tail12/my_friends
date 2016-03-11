<?php
    define('PDO_DNS', 'mysql:dbname=myfriends;host=localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');

    $dbh = new PDO(PDO_DNS, DB_USERNAME, DB_PASSWORD);
    $dbh->query('SET NAMES utf8');

    if(isset($_POST) && !empty($_POST)){
        // INSERT文
        $sql = sprintf(
        "INSERT INTO `friends`(`friend_id`, `friend_name`, `area_id`, `gender`, `age`, `created`)
        VALUES ('NULL', '%s', '%s', '%s', '%s', 'now();')",
        $_POST['name'], $_POST['area_id'], $_POST['gender'],$_POST['age']);

        $stmt = $dbh->prepare($sql);
        $stmt->execute();
    }

    $sql = 'SELECT * FROM `areas`';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    while (1) {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rec == false) break;
      $areas[] = $rec;
      // echo $rec['area_name'];
    }
    //var_dump($areas);
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
        <legend>友達の登録</legend>
        <form method="post" action="" class="form-horizontal" role="form">
            <!-- 名前 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">名前</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" placeholder="例：山田　太郎">
              </div>
            </div>
            <!-- 出身 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">出身</label>
              <div class="col-sm-10">
                <select class="form-control" name="area_id">
                  <option value="0">出身地を選択</option>
                  <?php
                      for ($i=0; $i <= count($areas) ; $i++) {
                          echo '<option value="'.($i+1).'">'.$areas[$i].'</option>';
                      }
                  ?>

                </select>
              </div>
            </div>
            <!-- 性別 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">性別</label>
              <div class="col-sm-10">
                <select class="form-control" name="gender">
                  <option value="0">性別を選択</option>
                  <option value="1">男性</option>
                  <option value="2">女性</option>
                </select>
              </div>
            </div>
            <!-- 年齢 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">年齢</label>
              <div class="col-sm-10">
                <input type="text" name="age" class="form-control" placeholder="例：27">
              </div>
            </div>

          <input type="submit" class="btn btn-default" value="登録">
        </form>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
