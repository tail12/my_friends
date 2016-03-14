<?php
    define('PDO_DNS', 'mysql:dbname=myfriends;host=localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    #var_dump($_GET);

    $dbh = new PDO(PDO_DNS, DB_USERNAME, DB_PASSWORD);
    $dbh->query('SET NAMES utf8');

    # if(!empty($_GET) && isset($_GET)){
      $sql = sprintf("SELECT * FROM `friends` WHERE friend_id='%s'", $_GET['friend_id']);
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $friend = $stmt->fetch(PDO::FETCH_ASSOC);
    # }
    echo "<pre>";
    var_dump($friend);
    echo "</pre>";

    # HW：ここをUpdate文の実行に変更しましょう
    # POST 送信された情報を取得
    # POST送信されたら、友達データを追加
    if(isset($_POST) && !empty($_POST)){
      var_dump($_POST);
      $sql = sprintf("UPDATE `friends` SET `friend_name`='%s',`area_id`=%d,`gender`=%d,`age`=%d WHERE `friend_id` = %s",
            $_POST['name'], $_POST['area_id'], $_POST['gender'], $_POST['age'], $_GET['friend_id']);
      # SQL実行
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      var_dump($friend['friend_id']);
    }

    $sql = 'SELECT * FROM `areas`';
    $areas = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    while (1) {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if($rec == false) break;
        $areas[] = $rec;
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
    <!-- WARNING: Respond.js doesn't work if you view the page via file:# -->
    <!--[if lt IE 9]>
      <script src="https:#oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https:#oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>


  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
        <legend>友達の編集</legend>
        <form method="post" action="" class="form-horizontal" role="form">
            <!-- 名前 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">名前</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" placeholder="<?php  ?>" value="<?php echo $friend['friend_name']; ?>">
              </div>
            </div>
            <!-- 出身 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">出身</label>
              <div class="col-sm-10">
                <select class="form-control" name="area_id">
                  <option value="0">出身地を選択</option>
                  <?php
                    foreach ($areas as $area) { ?>
                      <?php if ($area['area_id'] == $friend['area_id']) { ?>
                      <option value="<?php echo $area['area_id']; ?>" selected><?php echo $area['area_name']; ?></option>
                      <?php }else{ ?>
                      <option value="<?php echo $area['area_id']; ?>"><?php echo $area['area_name']; ?></option>
                      <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <!-- 性別 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">性別</label>
              <div class="col-sm-10">
                <select class="form-control" name="gender">
                  <?php
                      if($friend['gender'] == 1){ ?>
                        <option value="1" selected>男性</option>
                        <option value="2">女性</option>
                  <?php }else{ ?>
                      <option value="1">男性</option>
                      <option value="2" selected>女性</option>
                  <?php } ?>>
                </select>
              </div>
            </div>
            <!-- 年齢 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">年齢</label>
              <div class="col-sm-10">
                <input type="text" name="age" class="form-control" placeholder="例：27" value="<?php echo $friend['age']; ?>">
              </div>
            </div>

          <input type="submit" class="btn btn-default" value="更新" onclick="location.href=''" >
        </form>
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https:#ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
