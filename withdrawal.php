<?php

//共通変数・関数の読み込み
require('function.php');

debug('======================================');
debug('退会ページ');
debug('======================================');
debugLogStart();

//ログイン認証
require('auto.php');

//=========================================
//	画面処理
//=========================================
	
//POST送信されていた場合
if(!empty($_POST)){
	debug('POST送信があります');
	
//	例外処理
	try{
//		DB接続
		$dbh = dbConnect();
//		SQL文作成
		$sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
		$sql2 = 'UPDATE product SET delete_flg = 1 WHERE user_id = :us_id';
		$sql3 = 'UPDATE like SET delete_flg = 1 WHERE user_id = :us_id';
//		データ流し込み
		$data =array(':us_id' => $_SESSION['user_id']);
//		クエリ実行
		$stmt1 = queryPost($dbh, $sql1, $data);
		$stmt2 = queryPost($dbh, $sql2, $data);
		$stmt3 = queryPost($dbh, $sql3, $data);
		
//		クエリ実行成功の場合(最悪userテーブルのみ削除成功してれば良しとする)
		if($stmt1){
//			セッション削除
			session_destroy();
			debug('セッション変数の中身：'.print_r($_SESSION, true));
			debug('トップページへ遷移します');
			header("Location:index.php");
		}else{
			debug('クエリが失敗しました');
			$err_msg['common'] = MSG07;
		}
		
	}catch (Exception $e){
		error_log('エラー発生：' . $e->getMessage());
		$err_msg['common'] = MSG07;
	}
}

debug('画面処理表示終了　＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜');

?>


<!--HTML作成-->

<!--ヘッダーメニュー-->
<?php include ( dirname(__file__) . '/header.php'); ?>

<!-- メインコンテンツ -->
<div class="site-width">
	
		<div class="title">
			<h1>退会</h1>
		</div>
		<!--エラーエッセージ-->
		<div class="area-msg">
			<?php
				if(!empty($err_msg['common'])) echo $err_msg['common'];
			?>
		</div>
		
	<div id="main">
		<div class="box">
			<form class="form" method="post" action="">

				<div class="withdrawal-item">
					<input class="btn" type="submit" value="退会する" name="submit">
				</div>
			</form>
			<a href="mypage.php">マイページへ戻る</a>
		</div>

	</div>
</div>


<!--フッターメニュー-->
<?php include ( dirname(__FILE__) . '/footer.php'); ?>
