<?php

//共通関数の読み込み
require('function.php');

debug('====================================');
debug('パスワード再発行認証メール送信ページ');
debug('====================================');
debugLogStart();

//ログイン認証はなし(ログインできない人用)

//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//	画面処理開始
//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//POST送信されていた場合
if(!empty($_POST)){
	debug('POST送信がありました');
	debug('POST情報：'.print_r($_POST, true));
	
//	変数にPOST情報を代入
	$email = $_POST['email'];
	
//	未入力チェック
	validRequired($email, 'email');
	
	if(empty($err_msg)){
		debug('未入力確認OK');
		
//		email形式チェック
		validEmail($email, 'email');
//	 emailの最大文字数チェック
		validMaxlen($email, 'email');
		
		if(empty($err_msg)){
			debug('バリデーションOKです');
			
//			例外処理
			try {
//				DBへ接続
				$dbh = dbConnect();
//				SQL文作成
				$sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
				$data = array(':email' => $email);
//				クエリ実行　
				$stmt = queryPost($dbh, $sql, $data);
//				クエリ結果の値を取得
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				
//				emailが既にDBへ登録されている場合（会員である）
				if($stmt && array_shift($result)){
					debug('クエリ成功。DB登録あり');
					$_SESSION['msg_success'] = SUC03;
					
					$auth_key = makeRandKey();//認証キー生成
					
//					メール送信
					$from = 'hiwada.sho.1111@gmail.com';
					$to = $email;
					$subject = '【パスワード再発行認証】 | FreeMarket';
					$comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーのご入力いただくとパスワードがます。

パスワード再発行認証キー入力ページ：
認証キー：{$auth_key}
※認証キーの有効期限は30分になります。

認証キーの再発行を行いたい場合は下記のページより再度願いいたします。
					
					//////////////////////
url   http://localhost/sho/index.php
email hiwada.sho.1111@gmail.com
//////////////////////
EOT;
					
					sendMail($from, $to, $subject, $comment);
					debug('メールを送信しました');
					
//					認証に必要な情報をセッションに保存
					$_SESSION['auth_key'] = $auth_key;
					$_SESSION['auth_email'] = $email;
					$_SESSION['auth_key_limit'] = time()+(60*30);//現在時刻より30分後にUNIXタイムスタンプを入れる
					debug('セッション変数の中身：'.print_r($_SESSION, true));
					
					header('Location:passReminder.rivace.php');//認証キー入力画面へ
						
				}else {
					debug('クエリ失敗かDBに登録ないメールアドレスが入力されました');
					$err_msg['common'] = MSG07;
				}
			}catch (Exception $e) {
				error_log('エラー発生：'. $e->getMessage());
				$err_msg['common'] = MSG07;
			}
		}
	}
}

?>



<!--HTML作成-->

<!--ヘッダーメニュー-->
<?php
$siteTitle = 'パスワード変更　メールアドレス入力画面';
include ( dirname(__file__) . '/header.php');
?>

<!-- メインコンテンツ -->
<div class="site-width">

		<div class="title">
			<h1>パスワード変更画面</h1>
		</div>
		<div id="wrapper">
			<div id="main">

				<div class="box">
					<form class="form" action="" method="post">
						<p class="pass-reminder-text">ご指定のメールアドレス宛にパスワード再発行用の<br>URLと認証キーをお送りいたします</p>
							<label class="btn-name">
								E-mail
						<input class="inputs" type="email" name="email" placeholder="">
						</label>
						<input class="btn" type="submit" name="登録" value="送信する">
					</form>
				</div>

			</div>

			<!-- サイドバー-->
			<?php require('sidebar.php'); ?>
		</div>
</div>

<!--フッターメニュー-->
<?php include ( dirname(__FILE__) . '/footer.php'); ?>
