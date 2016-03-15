<?php
    #  echo $_GET['area_id'];
    define('PDO_DNS', 'mysql:dbname=myfriends;host=localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');

    $dbh = new PDO(PDO_DNS, DB_USERNAME, DB_PASSWORD);
    $dbh->query('SET NAMES utf8');

    # 削除の実装
    # ページ読み込み時に，URLのパラメータにactionがあれば，処理をする．
    if (isset($_GET['action']) && !empty($_GET['action'])) {
      # パラメータの値が，deleteだったら，処理をする．
      if ($_GET['action'] == 'delete') {
        # 実際の削除処理．
        $sql = sprintf("DELETE FROM `friends` WHERE `friend_id` = %d", $_GET['friend_id']);
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        # index.phpに遷移する．
        header('Location: index.php');
      }
    }


    $sql = sprintf('SELECT * FROM areas WHERE area_id = %s', $_GET['area_id']);
    # $sql = 'SELECT * FROM areas WHERE id = '.$_GET['area_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();


    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $area_name = $rec['area_name'];

    # 友達リストを表示するためのSQL文
    $sql = sprintf('SELECT * FROM `friends` WHERE `area_id` = %d', $_GET['area_id']);
    # SQL文の実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    # 取得データ格納用Array
    $friends = array();
    # 男女カウント用変数
    $male = 0;
    $female = 0;

    while (1) {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      if($rec == false) break;

      # データ格納
      $friends[] = $rec;
      # 数を1ずつ加算していく．
      if($rec['gender'] == 1) $male++;
      else $female++;
    }

    # AVG文の実装部分(male)
    $sql = sprintf("SELECT AVG(`age`) FROM `friends` WHERE `area_id`=%d AND `gender`=%d", $_GET['area_id'], 1);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    $avg_male = array();
    $avg_male = $rec['AVG(`age`)'];

    # AVG実装部分(female)
    $sql = sprintf("SELECT AVG(`age`) FROM `friends` WHERE `area_id`=%d AND `gender`=%d", $_GET['area_id'], 2);
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    $avg_female = array();
    $avg_female = $rec['AVG(`age`)'];

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

    <script type="text/javascript">
      function destroy(friend_id){
        if(confirm('削除してもよろしいでしょうか．')){
          // 許可した場合
          location.href = 'show.php?action=delete&friend_id=' + friend_id;
          return true;
        }else{
          // 拒否した場合
          return false;
        }
      }
    </script>

  </head>
  <body>


  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
      <legend><?php echo $area_name; ?>の友達</legend>
      <div class="well">
        男性: <?php echo $male; ?>名　女性： <?php echo $female; ?>名 <br>
        <?php if ($avg_male == NULL && $avg_female == NULL): ?>
            男性平均: <?php echo number_format(0, 2); ?>歳　女性平均: <?php number_format(0, 2); ?>歳
        <?php elseif($avg_male == NULL && $avg_female != NULL): ?>
            男性平均: <?php echo number_format(0, 2); ?>歳　女性平均: <?php echo number_format($avg_female, 2); ?>歳
        <?php elseif($avg_male != NULL && $avg_female == NULL): ?>
            男性平均: <?php echo number_format($avg_male, 2); ?>歳　女性平均: <?php echo number_format(0, 2); ?>歳
        <?php else: ?>
            男性平均: <?php echo number_format($avg_male, 2); ?>歳　女性平均: <?php echo number_format($avg_female, 2); ?>歳
        <?php endif; ?>
      </div>
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
                    <a href="edit.php?friend_id=<?php echo $friend['friend_id']; ?>"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="javascript:void(0);" onclick="destroy(<?php echo $friend['friend_id']; ?>);"><i class="fa fa-trash"></i></a>
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
    <script src="https:#ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
