<?php

//共通関数の読み込み
require('function.php');

debug('====================================');
debug('パスワード再発行ページ');
debug('====================================');
debugLogStart();

//ログイン認証はなし(ログインできない人用)


//SESSIONに認証キーがあるか確認、なければリダイヤル
if (empty($_SESSION['auth_key'])) {
	debug('セッションキーがないため、リダイヤルします');
	header('Location:passReminder.php'); //認証キー送信ページへ
}

//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//	画面処理開始
//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//POST送信されていた場合
if (!empty($_POST)) {
	debug('POST送信がありました');
	debug('POST情報：' . print_r($_POST, true));

	//	変数に認証キーを代入
	$auth_key = $_POST['token'];

	//	未入力チェック
	validRequired($auth_key, 'token');

	if (empty($err_msg)) {
		debug('未入力チェックOKです');

		//		文字数チェック
		validLength($auth_key, 'token');
		//	 半角チェック
		validHalf($auth_key, 'token');

		if (empty($err_msg)) {
			debug('バリデーションOKです');

			if ($auth_key !== $_SESSION['auth_key']) {
				$err_msg['common'] = MSG15;
			}
			if (time() > $_SESSION['auth_key_limit']) {
				$err_msg['common'] = MSG16;
			}

			if (empty($err_msg)) {
				debug('認証OK。パスワード再発行');

				$pass = makeRandKey(); //パスワード生成
				debug('新パスワード：' . print_r($pass, true));

				//				例外処理
				try {
					//					DB接続
					$dbh = dbConnect();
					//					SQL文作成
					$sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg = 0';
					$data = array(':email' => $_SESSION['auth_email'], ':pass' => password_hash($pass, PASSWORD_DEFAULT));
					//					クエリ実行
					$stmt = queryPost($dbh, $sql, $data);

					//					クエリ成功の場合
					if ($stmt) {
						debug('クエリ成功');

						//						メール送信
						$from = 'hiwada.sho.1111@gmail.com';
						$to = $_SESSION['auth_email'];
						$subject = '【パスワード再発行完了】　｜　FreeMarket';
						$comment = <<<EOT
本メールアドレス宛にパスワード再発行が完了しました。
下記のURLにて再発行パスワードをご入力いただき、ログインください。

ログインページ：
再発行パスワード：{$pass}
※ログイン後、パスワードの変更をお願いいたします。

					//////////////////////
url   http://localhost/sho/index.php
email hiwada.sho.1111@gmail.com
//////////////////////
EOT;
						sendMail($from, $to, $subject, $comment);
						debug('新しいパスワードをメールしました');

						//						セッション削除
						session_unset();
						$_SESSION['msg_success'] = SUC03;
						debug('セッション変数の中身：' . print_r($SESSION, true));
						debug('マイページへ遷移します');
						header('Location:login.php'); //ログインページへ

					} else {
						debug('クエリ失敗しました。');
						$err_msg['common'] = MSG07;
					}
				} catch (Exception $e) {
					error_log('エラー発生：' . $e->getMessage());
					$err_msg['common'] = MSG07;
				}
			}
		}
	}
}

?>

<!--HTML作成-->

<!--ヘッダーメニュー-->
<?php
$siteTitle = 'パスワード変更　認証キー入力画面';
include(dirname(__file__) . '/header.php');
?>

<p id="js-show-msg" style="display:none" class="msg-slide">
	<?php echo getSessionFlash('msg_success'); ?>
</p>

<!-- メインコンテンツ -->
<div class="site-width">
	<div class="title">
		<h1>パスワード変更認証キー入力画面</h1>
	</div>
	<div class="area-msg">
		<?php if (!empty($err_msg['common'])) echo $err_msg['common']; ?>
	</div>
	<div id="wrapper">
		<div id="main">

			<div class="box">
				<form class="form" action="" method="post">
					<p class="pass-reminder-text">
						認証キーをご入力いただきますと、<br>
						新しいパスワードをメールアドレス宛に<br>
						お送りいたします。
					</p>
					<label class="btn-name">
						認証キー
						<input class="inputs" type="text" name="token" valuer="<?php echo getFormData('token'); ?>">
					</label>
					<div class="area-msg">
						<?php if (!empty($err_msg['token'])) echo $err_msg['token']; ?>
					</div>
					<input class="btn" type="submit" name="登録" value="再発行する">
					<div style="margin-top: 50px;">
						<a href="passReminder.php">&lt;再度認証キーを発行する</a>
					</div>
				</form>

			</div>

		</div>

		<!-- サイドバー-->
		<?php require('sidebar.php'); ?>
	</div>
</div>

<!--フッターメニュー-->
<?php include(dirname(__FILE__) . '/footer.php'); ?>