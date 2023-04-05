<?php

//	新規登録機能
//
//	１。POSTされているかチェック
//	２。バリデーションチェック
//	３。DB接続
//	４。レコード挿入
//	５。マイページへ遷移


//ログとるか
ini_set('log_errors', 'on');
//ログの出力ファイルの指定
ini_set('error_log', 'php.log');

//共通関数の読み込み
require('function.php');

debug('======================================');
debug('ログインページ');
debug('＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝');
debugLogStart();

////ログイン認証
//require('auto.php');

//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//ログイン画面処理
//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

////ログとるか
//ini_set('log_errors','on');
////ログの出力ファイルの指定
//ini_set('error_log','php.log');
//
////エラーメッセージを定数に設定
//define('MSG01','入力必須です');
//define('MSG02','Emailの形式で入力してください');
//define('MSG03','パスワード(再入力)が合っていません');
//define('MSG04','半角英数字のみご利用いただけます');
//define('MSG05','6文字以上で入力してください');
//define('MSG06','256文字以内で入力してください');
//define('MSG07','エラーが発生しました。しばらく経ってから再度お試しください');
//define('MSG08','そのEmailは既に登録されています');
//
////エラーメッセージ格納用の配列
//$err_msg = array();
//
////バリデーション関数（未入力チェック）
//function validRequired($str, $key){
//				if(empty($str)){
//								global $err_msg;
//								$err_msg[$key] = MSG01;
//				}
//}
//
////バリデーション関数（Email形式チェック）
//function validEmail($str, $key){
//				if(!preg_match("/^([a-zA-Z0-9])+([a-zA-z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
//								global $err_msg;
//								$err_msg[$key] = MSG02;
//				}
//}
//
////バリデーション関数(Email重複チェック)
//function validEmailDup($email){
//				global $err_msg;
//				//例外処理
//				try {
//								//DBへ接続
//								$dbh =dbConnect();
//								//SQL文作成
//								$sql = 'SELECT count(*) FROM users WHERE email = :email limit 1';
//								$data = array(':email' => $email);
//								//クエリ実行
//								$stmt = queryPost($dbh, $sql, $data);
//								//クエリ結果の値を取得
//								$result = $stmt->fetch(PDO::FETCH_ASSOC);
//								if(!empty(array_shift($result))){
//												$err_msg['email'] = MSG08;
//								}
//				} catch (Exception $e) {
//								error_log('エラー発生:' . $e->getMessage());
//								$err_msg['common'] = MSG07;
//				}
//}
//
////バリデーション関数（同数チェック）
//function validMatch($str1, $str2, $key){
//				if($str1 !== $str2){
//								global $err_msg;
//								$err_msg[$key] = MSG03;
//				}
//}
//
////バリデーション関数（最小文字数チェック）
//function validMinlen($str, $key, $min = 6){
//				if(mb_strlen($str) < $min){
//								global $err_msg;
//								$err_msg[$key] = MSG05;
//				}
//}
//
////バリデーション関数（最大文字数チェック）
//function validMaxlen($str, $key, $max = 255){
//				if(mb_strlen($str) > $max){
//								global $err_msg;
//								$err_msg[$key] = MSG06;
//				}
//}
//
////バリデーション関数（半角チェック）
//function validHalf($str, $key){
//				if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
//								global $err_msg;
//								$err_msg[$key] = MSG04;
//				}
//}
//
////DB接続開始
//function dbconnect(){
//				//    DBへの接続準備
//				$dsn= 'mysql:dbname=freemarket;localhosto;charset=utf8';
//				$user = 'root';
//				$password = 'root';
//				$options = array(
//								//SQL実行失敗時にはエラーコードのみ設定
//								PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
//								//デフォルトフェッチモードを連想配列形式に設定
//								PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//								//        バッファードクエリを使う（一度に結果セットをすべて取得し、サーバー負荷を軽減）
//								//SELECTで得た結果に対してもrowCountメソッドを使えるようにする。
//								PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
//				);
//				//    PDOオブジェクト生成（DBへ接続）
//				$dbh = new PDO($dsn, $user, $password, $options);
//				return $dbh;
//}
//
//function queryPost($dbh, $sql, $data){
//				//クエリー作成
//				$stmt = $dbh->prepare($sql);
//				//    プレースホルダに値をセットし、SQL分を実行
//				$stmt->execute($data);
//				return $stmt;
//}

//post送信されていた場合
if (!empty($_POST)) {

	//変数にユーザー情報を代入
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$pass_re = $_POST['pass_re'];

	//未入力チェック
	validRequired($email, 'email');
	validRequired($pass, 'pass');
	validRequired($pass_re, 'pass_re');

	if (empty($err_msg)) {

		//emailの形式チェック
		validEmail($email, 'email');
		//emailの最大文字数チェック
		validMaxLen($email, 'email');
		//email重複チェック
		validEmailDup($email);

		//パスワードの半角英数字チェック
		validHalf($pass, 'pass');
		//パスワードの最大文字数チェック
		validMaxLen($pass, 'pass');
		//パスワードの最小文字数チェック
		validMinLen($pass, 'pass');

		//パスワード（再入力）の最大文字数チェック
		validMaxLen($pass_re, 'pass_re');
		//パスワード（再入力）の最小文字数チェック
		validMinLen($pass_re, 'pass_re');

		if (empty($err_msg)) {

			//パスワードとパスワード再入力が合っているかチェック
			validMatch($pass, $pass_re, 'pass_re');

			if (empty($err_msg)) {
				//例外処理
				try {
					// DBへ接続
					$dbh = dbConnect();
					// SQL文作成
					$sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES(:email,:pass,:login_time,:create_date)';
					$data = array(
						':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
						':login_time' => date('Y-m-d H:i:s'),
						':create_date' => date('Y-m-d H:i:s')
					);
					// クエリ実行
					$stmt = queryPost($dbh, $sql, $data);

					//																	クエリ成功の場合、そのままセッションに格納（ユーザー登録後、すぐマイページへ遷移）
					if ($stmt) {
						//																					ログイン有効期限を１時間
						$sesLimit = 60 * 60;
						//																					最終ログイン日時を現在時刻へ
						$_SESSION['login_date'] = time();
						$_SESSION['login_limit'] = $sesLimit;
						//																					ユーザーIDを格納
						$_SESSION['user_id'] = $dbh->lastInsertId();

						debug('セッション変数の中身：' . print_r($_SESSION, true));
						header("Location:mypage.php"); //マイページへ
					} else {
						error_log('クエリに失敗しました');
						$err_msg['common'] = MSG07;
					}
				} catch (Exception $e) {
					error_log('エラー発生:' . $e->getMessage());
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
$siteTitle = '新規登録画面';
include(dirname(__file__) . '/header.php');
?>



<!-- メインコンテンツ -->
<div class="site-width">

	<div class="title">
		<h1>新規登録画面</h1>
	</div>
	<!--エラーエッセージ-->
	<div class="area-msg">
		<?php
		if (!empty($err_msg['common'])) echo $err_msg['common'];
		?>
	</div>

	<div id="main">
		<div class="box">
			<form class="form" method="post">

				<div class="item">
					<input class="inputs" type="text" name="email" placeholder="メールアドレス">
					<div class="err_msg">
						<?php if (!empty($err_msg['email'])) echo $err_msg['email']; ?>
					</div>
				</div>

				<div class="item">
					<input class="inputs" type="password" name="pass" placeholder="パスワード">
					<div class="err_msg">
						<?php if (!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
					</div>
				</div>

				<div class="item">
					<input class="inputs" type="password" name="pass_re" placeholder="パスワードを確認">
					<div class="err_msg">
						<?php if (!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?>
					</div>
				</div>

				<div class="item">
					<input class="btn" type="submit" value="登録">
				</div>

			</form>

		</div>
	</div>
</div>


<!--フッターメニュー-->
<?php include(dirname(__FILE__) . '/footer.php'); ?>