	<?php

	//ログとるか
	ini_set('log_errors','on');
	//ログの出力ファイルの指定
	ini_set('error_log','php.log');

	//共通関数の読み込み
	require('function.php');

	debug('======================================');
	debug('ログインページ');
	debug('======================================');
	debugLogStart();

	//ログイン認証
	require('auto.php');

	//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
	//ログイン画面処理
	//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

	//POST送信されていた場合
	if(!empty($_POST)){
		debug('POST送信があります');

		//	変数にユーザー情報に代入
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$pass_save = (!empty($_POST['pass_save'])) ? true : false; //ショートハンドでの書き方


		//emailの形式チェック
		validEmail($email, 'email');
		//emailの最大文字数チェック
		validMaxLen($email, 'email');

		//パスワードの半角英数字のチェック
		validHalf($pass, 'pass');
		//パスワードの最大文字数チェック
		validMaxLen($pass, 'pass');
		//パスワードの最小文字数チェック
		validMinLen($pass, 'pass');

		//未入力チェック
		validRequired($email, 'email');
		validRequired($pass, 'pass');

		if(empty($err_msg)){
			debug('バリデーションOKです');

			//	例外処理
			try{
				//		DB接続
				$dbh = dbConnect();
				//		SQL文作成
				$sql = 'SELECT password,id FROM users WHERE email = :email';
				$data = array(':email' => $email);
				//		クエリ実行
				$stmt = queryPost($dbh, $sql, $data);
				//		クエリ結果の値を取得
				$result = $stmt->fetch(PDO::FETCH_ASSOC);

				debug('クエリ結果の中身：'.print_r($result,true));

				//		パスワードの照合
				if(!empty($result) && password_verify($pass, array_shift($result))){
					debug('パスワードがマッチしました');

					//	ログイン有効期限（デフォルトを１時間に設定）
					$sesLimit = 60*60;
					//	最終ログイン日時を現在日時に更新
					$_SESSION['login_date'] = time();

					//	ログイン保持にチェックがあった場合
					if($pass_save){
						debug('ログイン保持にチェックがあります。');
						//		ログイン有効期限を30日に設定
						$_SESSION['login_limit'] = $sesLimit * 24 * 30;
					}else{
						debug('ログイン保持にチェックはありません');
						//		次回からログイン保持しないので、ログイン有効期限を1時間後にセット
						$_SESSION['login_limit'] = $sesLimit;
					}
					//	ユーザーIDを格納
					$_SESSION['user_id'] = $result['id'];

					debug('セッション変数の中身：'.print_r($_SESSION, true));
					debug('マイページへ遷移します');
					header("Location:mypage.php");
				}else{
					debug('パスワードがアンマッチです');
					$err_msg['common'] = MSG09;
				}
			}catch (Exception $e){
				error_log('エラー発生：'.$e->getMessage());
				$err_msg['common'] = MSG07;
			}
		}
	} 

	debug('画面表示処理終了＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜')

	?>
<!--	タイトル-->
<?php
		$siteTitle = 'ログイン';
		include ( dirname(__file__) . '/header.php');
?>


	<!--HTML作成-->

	<!--ヘッダーメニュー-->
	<?php  ?>



	<!-- メインコンテンツ -->
	<div class="site-width">
		<div id="main">

			<div class="title">
				<h1>ログイン</h1>
			</div>
			<!--								エラーエッセージ-->
			<div class="area-msg">
				<?php
				if(!empty($err_msg['common'])) echo $err_msg['common'];
				?>
			</div>
			<div class="guest">
				<p class="guest-text"><br>メールアドレス： guest@gmail.com<br>パスワード：111111<br></p>
			</div>



			<div class="box">
				<form class="form" method="post">

					<div class="item">
						<input class="inputs" type="text" name="email" placeholder="メールアドレス"><br>
						<div class="err_msg">
							<?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
						</div>
					</div>

					<div class="item">
						<input class="inputs" type="password" name="pass" placeholder="パスワード"><br>
						<div class="err_msg">
							<?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
						</div>
					</div>

					<div class="auto_login">
						<input type="checkbox" id="auto_login" name="pass_save">
						<label for="auto_login">次回から自動でログインする</label>
					</div>

					<div class="form-btn">
						<input class="btn" type="submit" value="ログイン">
					</div>

					<div class="item">
						<a href="passReminder.php">パスワードを忘れですか？</a>
					</div>
				</form>
			</div>
		</div>
	</div>


	<!--フッターメニュー-->
	<?php include ( dirname(__FILE__) . '/footer.php'); ?>
