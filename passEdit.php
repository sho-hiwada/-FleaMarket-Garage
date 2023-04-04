		<?php

		//共通変数・関数の読み込み
		require('function.php');

		debug('=============================================');
		debug('パスワード変更画面');
		debug('=============================================');
		debugLogStart();

		//ログイン認証
		require('auto.php');

		//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
		//画面処理開始
		//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
		//DBからユーザー情報を取得
		$userData = getUser($_SESSION['user_id']);
		debug('取得したユーザー情報：'.print_r($userData, true));

		//POST送信されていた場合
		if(!empty($_POST)){
			debug('POST送信がありました。');
			debug('POST情報：'.print_r($_POST,true));

			//	変数にユーザー情報を代入
			$pass_old = $_POST['pass_old'];
			$pass_new = $_POST['pass_new'];
			$pass_new_re = $_POST['pass_new_re'];

			//	未入力チェック
			validRequired($pass_old, 'pass_old');
			validRequired($pass_new, 'pass_new');
			validRequired($pass_new_re, 'pass_new_re');

			if(empty($err_msg)){
				debug('未入力チェックOK');

				//		古いパスワードのチェック
				validPass($pass_old, 'pass_old');
				//		新しいパスワードのチェック
				validPass($pass_new, 'pass_new');

				//		DBのパスワードと古いパスワードを照合（バリデーションチェックは省略）
				if(!password_verify($pass_old, $userData['password'])){
					$err_msg['pass_old'] = MSG12;
				}

				//		古いパスワードと新しいパスワードが重複してないかチェック
				if($pass_old === $pass_new){
					$err_msg['pass_new'] = MSG13;
				}
				//		新しいパスワードと再入力パスワードが同じかチェック
				validMatch($pass_new, $pass_new_re, 'pass_new_re');

				if(empty($err_msg)){
					debug('バリデーションOKです');

					//			例外処理
					try {
						//				DB接続
						$dbh = dbConnect();
						//				SQL文作成
						$sql = 'UPDATE users SET password = :pass WHERE id = :id';
						$data = array(':id' => $_SESSION['user_id'], ':pass' => password_hash($pass_new, PASSWORD_DEFAULT));
						//				クエリ実行
						$stmt = queryPost($dbh, $sql, $data);

						//				クエリ成功の場合
						if($stmt){
							debug('クエリ成功');
							$_SESSION['msg_success'] = SUC01;

							//					メールを送信
							$username = ($userData['username']) ? $userData['username'] : '名無し';
							$from = 'hiwada.sho.1111@gmail.com';
							$to = $userData['email'];
							$subject = 'パスワード変更通知 | FREEMARKET';
							$comment = <<<EOT
		{$username}　さん

		パスワードが変更されました。

		//////////////////////
		url   http://localhost/sho/index.php
		email hiwada.sho.1111@gmail.com
		//////////////////////
		EOT;
							sendMail($from, $to, $subject, $comment);
							debug('パスワード変更メールを送りました');
							debug('マイページへ遷移します');
							header("Location:mypage.php");//マイページへ
						}

					} catch(Exception $e){
						error_log('エラー発生：' . $e->getMessage());
						$err_msg['common'] = MSG07;
					}
				}
			}
		}

		?>

		<!--HTML作成-->

		<!--ヘッダーメニュー-->
<?php
$siteTitle = 'パスワード変更';
include ( dirname(__file__) . '/header.php');
?>

		<!-- メインコンテンツ -->
		<div class="site-width">
			<div class="title">
				<h1>パスワード変更画面</h1>
			</div>
			<div class="area-msg">
				<?php
														echo getErrMsg('common');
														?>
			</div>
			<div id="wrapper">
				<div id="main">

					<div class="box">
						<form class="form" action="" method="post">
							<div class="item">
								<input class="inputs" type="password" name="pass_old" placeholder="古いパスワード">
							</div>
							<div class="err_msg">
								<?php echo getErrMsg('pass_old') ?>
							</div>
							<div class="item">
								<input class="inputs" type="password" name="pass_new" placeholder="新しいパスワード">
							</div>
							<div class="err_msg">
								<?php echo getErrMsg('pass_new') ?>
							</div>
							<div class="item">
								<input class="inputs" type="password" name="pass_new_re" placeholder="新しいパスワード（再入力）">
							</div>
							<div class="err_msg">
								<?php echo getErrMsg('pass_new_re') ?>
							</div>
							<div class="item">
								<input class="btn" type="submit" name="登録" value="変更する">
							</div>
						</form>
					</div>

				</div>

				<!-- サイドバー-->
				<?php require('sidebar.php'); ?>

			</div>
		</div>


		<!--フッターメニュー-->
		<?php include ( dirname(__FILE__) . '/footer.php'); ?>
