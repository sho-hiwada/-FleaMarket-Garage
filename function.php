<?php

//===================================
//	ログ
//===================================

//ログとるか
ini_set('log_errors','on');
//ログの出力ファイルの指定
ini_set('error_log','php.log');

//===================================
//	デバッグ
//===================================

//デバッグフラグ（リリース時はfalseに変更）
$debug_flg = true;
//デバッグロゴ関数
function debug($str){
	global $debug_flg;
	if(!empty($debug_flg)){
		error_log('デバッグ:'.$str);
	}
}

//===================================
//	セッションの準備・セッションの有効期限を延ばす
//===================================

//セッションファイルの置き場所を変更する
session_save_path("/var/tmp/");
//ガーページコレクションが削除するセッションの有効期限を変更
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザが閉じても削除されないようクッキーの有効期限を延ばす
ini_set('session.cookie_lifetime', 60*60*24*30);
//セッションスタート
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすまし対策）
session_regenerate_id();

//===================================
//	画面処理表示開始ログ吐き出し関数
//===================================

function debugLogStart(){
	debug('>>>>>>>>>>>>>>>>>>>>>>>>画面表示処理開始');
	debug('セッションID：'.session_id());
	debug('セッション変数の中身：'.print_r($_SESSION, true));
	debug('現在日時タイムスタンプ：'.time());
	if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
		debug('ログイン期限日時タイムスタンプ：'.($_SESSION['login_date'] + $_SESSION['login_limit']));
	}
}


//===================================
//	定数
//===================================



//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02','Emailの形式で入力してください');
define('MSG03','パスワード(再入力)が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','256文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってから再度お試しください');
define('MSG08','そのEmailは既に登録されています');
define('MSG09','メールアドレスまたはパスワードが違います');
define('MSG10','電話番号の形式が違います');
define('MSG11','郵便番号の形式が違います');
define('MSG12','古いパスワードが違います');
define('MSG13','古いパスワードが同じです');
define('MSG14','文字でご入力ください');
define('MSG15','正しくありません');
define('MSG16','有効期限が切れています。再度発行してください');
define('MSG17','正しくありません');
define('MSG18','有効期限が切れています');
define('MSG19','半角英数字のみご利用できます');
define('MSG20','選択してください');
define('SUC01','パスワードを変更しました');
define('SUC02','プロフィールを変更しました');
define('SUC03','メールを送信しました');
define('SUC04','登録しました');
define('SUC05','購入しました。出品者と連絡を取りましょう。');



//エラーメッセージ格納用の配列
$err_msg = array();

//バリデーション関数（未入力チェック）
function validRequired($str, $key){
	if($str === ''){ //金額のフォームを考慮して、０はOK、空文字NGにする
		global $err_msg;
		$err_msg[$key] = MSG01;
	}
}

//バリデーション関数（Email形式チェック）
function validEmail($str, $key){
	if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
		global $err_msg;
		$err_msg[$key] = MSG02;
	}
}

//バリデーション関数(Email重複チェック)
function validEmailDup($email){
	global $err_msg;
	//例外処理
	try {
		//DBへ接続
		$dbh =dbConnect();
		//SQL文作成
		$sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
		$data = array(':email' => $email);
		//クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		//クエリ結果の値を取得
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty(array_shift($result))){
			$err_msg['email'] = MSG08;
		}
	} catch (Exception $e) {
		error_log('エラー発生:' . $e->getMessage());
		$err_msg['common'] = MSG07;
	}
}

//バリデーション関数（同数チェック）
function validMatch($str1, $str2, $key){
	if($str1 !== $str2){
		global $err_msg;
		$err_msg[$key] = MSG03;
	}
}

//バリデーション関数（最小文字数チェック）
function validMinlen($str, $key, $min = 6){
	if(mb_strlen($str) < $min){
		global $err_msg;
		$err_msg[$key] = MSG05;
	}
}

//バリデーション関数（最大文字数チェック）
function validMaxlen($str, $key, $max = 255){
	if(mb_strlen($str) > $max){
		global $err_msg;
		$err_msg[$key] = MSG06;
	}
}

//バリデーション関数（半角チェック）
function validHalf($str, $key){
	if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
		global $err_msg;
		$err_msg[$key] = MSG04;
	}
}

//電話番号形式チェック
function validTel($str, $key){
	if(!preg_match("/0\d{1,4}\d{1,4}\d{4}/",$str)){
		global $err_msg;
		$err_msg[$key] = MSG10;
	}
}

//郵便番号形式チェック
function validZip($str, $key){
	if(!preg_match("/^\d{7}$/",$str)){
		global $err_msg;
		$err_msg[$key] = MSG11;
	}
}

//半角英数字チェック
function validNumber($str, $key){
	if(!preg_match("/^[0-9]+$/",$str)){
		global $err_msg;
		$err_msg[$key] = MSG19;
	}
}


//文字数チェック
function validLength($str, $key, $len = 8){
	if(mb_strlen($str) !== $len){
		global $err_msg;
		$err_msg[$key] = $len. MSG14;
	}
}

//パスワードチェック
function validPass($str, $key){
//	半角英数字チェック
	validHalf($str, $key);
//	最大文字数チェック
	validMaxLen($str, $key);
//	最小文字数チェック
	validMinLen($str, $key);
}

//セレクトボックスチェック
function validSelect($str, $key){
	if(!preg_match("/^[1-9]+$/", $str)){
		global $err_msg;
		$err_msg[$key] = MSG20;
	}
}

//エラーメッセージ表示
function getErrMsg($key){
	global $err_msg;
	if(!empty($err_msg[$key])){
		return $err_msg[$key];
	}
}

//===========================================
//	ログイン認証
//===========================================
//auto.php と基本同じだが、未ログインユーザーをマイページへ遷移しない処理

function isLogin(){
//	ログインしている場合
	if(!empty($_SESSION['login_date'])){
		debug('ログイン済ユーザーです。');
		
//		現在日時が最終ログイン日時＋有効期限を超えていた場合
		if(($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
			debug('ログイン有効期限オーバーです');
			
//			セッションを削除（ログアウト）
				session_destroy();
			return false;
		}else {
			debug('ログイン有効期限内です');
			return true;
		}
		
	}else{
		debug('未ログインユーザーです');
		return false;
	}
}


//===========================================
//	データベース
//===========================================

//DB接続開始
function dbConnect(){
	//    DBへの接続準備
	$dsn= 'mysql:dbname=freemarket;localhosto;charset=utf8';
	$user = 'root';
	$password = 'root';
	$options = array(
		//SQL実行失敗時にはエラーコードのみ設定
		PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
		//デフォルトフェッチモードを連想配列形式に設定
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		//        バッファードクエリを使う（一度に結果セットをすべて取得し、サーバー負荷を軽減）
		//SELECTで得た結果に対してもrowCountメソッドを使えるようにする。
		PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
	);
	//    PDOオブジェクト生成（DBへ接続）
	$dbh = new PDO($dsn, $user, $password, $options);
	return $dbh;
}

function queryPost($dbh, $sql, $data){
	//クエリー作成
	$stmt = $dbh->prepare($sql);
	//    プレースホルダに値をセットし、SQL分を実行
	if(!$stmt->execute($data)){
		debug('SQL:'.$sql);
		debug('クエリに失敗しました');
		$err_msg['common'] = MSG07;
		return 0;
}
	debug('クエリ成功');
	return $stmt;
}
	

function getUser($u_id){
	debug('ユーザー情報を取得します。');
	//例外処理
	try {
		// DBへ接続
		$dbh = dbConnect();
		// SQL文作成
		$sql = 'SELECT * FROM users  WHERE id = :u_id';
		$data = array(':u_id' => $u_id);
		// クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
	
//		クエリ結果のデータを１レコード返却
		if($stmt){
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else {
			return false;
		}
	} catch (Exception $e) {
		error_log('エラー発生:' . $e->getMessage());
	}
}



function getProduct($u_id, $p_id){
	debug('商品情報を取得します。');
	debug('ユーザーID：'.$u_id);
	debug('商品ID：'.$p_id);
//	例外処理
	try{
//		DB接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'SELECT * FROM product WHERE user_id = :u_id AND id = :p_id AND delete_flg = 0';
		$data = array(':u_id' => $u_id, ':p_id' => $p_id);
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
//			クエリ結果のデータを１レコード返却
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else {
			return false;
		}
		
	}catch (Exception $e) {
		error_log('エラー発生：' . $e->getMessage());
	}
}

function getProductList($currentMinNum = 1,$category, $sort, $span = 20) {
	debug('商品情報を取得します');
	//	例外処理へ
	try {
		//		DB接続
		$dbh = dbConnect();
		//		検索用SQL文作成
		$sql = 'SELECT id FROM product';
//		if(!empty($category)) $sql.='WHERE category_id ='.$category;
//		if(!empty($sort)){
//			switch($sort){
//				case 1:
//					$sql.='ORDER BY price ASC';
//					break;
//				case 2:
//					$sql.='ORDER BY price DESC';
//					break;
//			}
//		}
		$data = array();
		//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		$rst['total'] = $stmt->rowCount(); //総レコード数
		debug('総レコード数：'.print_r($rst['total'], true));
		$rst['total_page'] = ceil($rst['total']/$span); //総ページ数(総レコード数から$spanで割る。ceil関数で四捨五入切り上げ)
		debug('総ページ数：'.print_r($rst['total_page'], true));
		if(!$stmt){
			return false;
		}
		//		ペーシング用のSQL文作成
		$sql = 'SELECT * FROM product';
		if(!empty($category)) $sql .= ' WHERE category_id = '.$category;
		if(!empty($sort)){
			switch($sort){
				case 1:
					$sql .= ' ORDER BY price ASC'; //昇順
					break;
				case 2:
					$sql .= ' ORDER BY price DESC'; //降順
					break;
			}
		}
		$sql .=' LIMIT '.$span.' OFFSET '.$currentMinNum;
		$data = array();
		debug('SQL:'.$sql);
		//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);

		if($stmt){
			//			クエリ結果のレコードを格納
			$rst['data'] = $stmt->fetchAll();
			return $rst;
		}else {
			return false;
		}

	} catch(Exception $e) {
		error_log('エラー発生：'.getMesage());
	}
}

function getProductOne($p_id){
	debug('商品情報を取得します');
	debug('商品ID:'.$p_id);
//	例外処理
	try {
//		DB接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'SELECT p.id, p.name, p.comment, p.price, p.pic1, p.pic2, p.pic3, p.user_id, p.create_date, p.update_date, c.name AS category FROM product AS p LEFT JOIN category AS c ON p.category_id = c.id WHERE p.id = :p_id AND p.delete_flg = 0 AND c.delete_flg = 0';
		$data = array(':p_id' => $p_id);
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
//			クエリ結果のデータを１レコード返却
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}else {
			return false;
		}
		
	} catch (Exception $e) {
		error/log('エラー発生：' . $e->getMessage());
	}
}

function getMyProducts($u_id){
	debug('自分の商品情報を取得します');
	debug('ユーザーID：'.$u_id);
//	例外処理
	try {
//		DBへ接続
		$dbh = dbConnect();
//		SQL文
		$sql = 'SELECT * FROM product WHERE user_id = :u_id AND delete_flg = 0';
		$data = array(':u_id' => $u_id);
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
//			クエリ結果んおデータを全レコード返却
			return $stmt->fetchAll();
		}else {
			return false;
		}
		
	} catch (Exception $e) {
		error_log('エラー発生：' . $e->getMessage());
	}
}

function getMsgsAndBord($id){
	debug('msg情報を取得します');
	debug('掲示板ID:'.$id);
//	例外処理
	try {
//		DBへ接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'SELECT m.id AS m_id, product_id, bord_id, send_date, to_user, from_user, sale_user, buy_user, msg, b.create_date FROM message AS m RIGHT JOIN bord AS b ON b.id = m.bord_id WHERE b.id = :id ORDER BY send_date ASC';
		$data = array(':id' => $id);
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
//			クエリ結果の全データを返却
			return $stmt->fetchAll();
		}else{
			return false;
		}
		
	} catch (Exception $e) {
		error_log('エラー発生：' . $e->getMessage());
	}
}

function getMyMsgsAndBord($u_id){
	debug('自分のmsg情報を取得します。');
	//例外処理
	try {
		// DBへ接続
		$dbh = dbConnect();

		// まず、掲示板レコード取得
		// SQL文作成
		$sql = 'SELECT *
        FROM users AS u
        LEFT JOIN bord AS b ON (b.buy_user = u.id OR b.sale_user = u.id)
        WHERE (b.buy_user = :id OR b.sale_user = :id) AND b.delete_flg = 0 AND u.id <> :id
				ORDER BY update_date DESC';
		$data = array(':id' => $u_id);
		// クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		$rst = $stmt->fetchAll();
		if (!empty($rst)) {
			foreach ($rst as $key => $val) {
					// SQL文作成
					$sql = 'SELECT * FROM message WHERE bord_id = :id AND delete_flg = 0 ORDER BY send_date DESC';
					$data = array(':id' => $val['id']);
					// クエリ実行
					$stmt = queryPost($dbh, $sql, $data);
					$msgResult = $stmt->fetchAll(); // メッセージ結果を格納
					if (!empty($msgResult)) {
							// メッセージがある場合は、$rstの該当する要素に'msg'というキーを追加して、メッセージ結果を格納
							$rst[$key]['msg'] = $msgResult;
					}
			}
	}
		if($stmt){
//			クエリ結果の全データを返却
			return $rst;
		}else {
			return false;
		}
		
	} catch (Exception $e) {
		error_log('エラー発生：' . $e->getMessage());
	}
}


function getCategory(){
	debug('カテゴリー情報を取得します');
//	例外処理
	try {
//		DB接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'SELECT * FROM category';
		$data = array();
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
//			クエリ結果の全データを返却
			return $stmt->fetchAll();
		}else{
			return false;
		}
	}catch ( Exception $e){
		error_log('エラー発生：'. $e->getMessage());
	}
}

//お気に入り登録
function isLike($u_id, $p_id){
	debug('お気に入り登録を判別します');
	debug('ユーザーID：'.$u_id);
	debug('商品ID：'.$p_id);
//	例外処理
	try {
//		DB接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'SELECT * FROM `like` WHERE product_id = :p_id AND user_id = :u_id';
		$data = array(':u_id' => $u_id, ':p_id' => $p_id);
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt->rowCount()){
			debug('お気に入り登録あり');
			return true;
		}else {
			debug('お気に入り登録なし');
			return false;
		}
		
	} catch (Exception $e) {
		error_log('エラー発生：' . $e->getMessage());
	}
}

function getMyLike($u_id){
	debug('自分のお気に入り情報を取得します');
	debug('ユーザーID:'.$u_id);
//	例外処理
	try {
//		DBへ接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'SELECT * FROM `like` AS l LEFT JOIN product AS p ON l.product_id = p.id WHERE l.user_id = :u_id';
		$data = array(':u_id' => $u_id);
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
		if($stmt){
//			クエリ結果の全データを返却
			return $stmt->fetchAll();
		}else {
			return false;
		}
		
	} catch (Exception $e){
		error_log('エラー発生：' . $e->getMessage());
	}
}


//メール送信
function sendMail($from, $to, $subject, $comment){
	if(!empty($to) && !empty($subject) && !empty($comment)){
		//		文字化けしないよう設定
		mb_language("Japanese");//現在使っている言語を使用
		mb_internal_encoding("UTF-8");//内部の日本語をどうコーディング（機械に認識されるよう変換）するか設定

		//			メールを送信（送信結果はtrueかfalseで返ってくる）
		$result = mb_send_mail($to, $subject, $comment, "From: ".$from);
		//		送信結果を判定
		if($result){
			debug('メールを送信しました');
		}else {
			debug('【エラー発生】メールの送信に失敗しました');
		}
	}
}

//サニタイズ
function sanitize($str) {
return htmlspecialchars($str, ENT_QUOTES);
}
//フォームの入力保持
function getFormData($str, $flg = false){
	//GETかPOSTか判別する
	if($flg){
		$method = $_GET;
	}else {
		$method = $_POST;
	}
	global $dbFormData;
//	ユーザーデータがある場合
	if(!empty($dbFormData)){
//		フォームにエラーがある場合
		if(!empty($err_msg[$str])){
//			POSTにデータがある場合
			if(isset($method[$str])){//金額や郵便番号などのフォームで数字や数値が入っている場合もあるため、issetを使う
				return sanitize($method[$str]);
			}else {
//				ない場合はDBの情報を表示
				return sanitize($dbFormData[$str]);
			}
		}else {
//				POSTにデータがあり、DBの情報と違う場合
			if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
				return sanitize($method[$str]);
				}else {
					return sanitize($dbFormData[$str]);
			}
		}
	}else {//そもそも変更していない場合
		if(isset($method[$str])){
			return sanitize($method[$str]);
		}
	}
}




//SISSIONを１回だけ取得
function getSessionFlash($key){
	if(!empty($_SESSION[$key])){
		$data = $_SESSION[$key];
		$_SESSION[$key] = ''; //空にする。
		return $data;
	}
}

//認証キー作成
function makeRandKey($length = 8) {
	static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$str = '';
	for ($i = 0; $i < $length; ++$i) {
		$str .= $chars[mt_rand(0, 61)];
	}
	return $str;
}

//画像処理
function uploadImg($file, $key){
	debug('画像アップロード処理開始');
	debug('FILE情報：'.print_r($file, true));
	
	if(isset($file[error]) && is_int($file['error'])) {
		try{
//			バリデーション
//			$file[error]の値を確認。配列内に「UPLOAD_ERR_OK」などの定数が入っている。
//			「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
			switch ($file['error']){
				case UPLOAD_ERR_OK: //OK
					 break;
				case UPLOAD_ERR_NO_FILE: //ファイル未選択の場合
					 throw new RuntimeException('ファイルが選択されていません');
				case UPLOAD_ERR_INI_SIZE: //php.ini定義の最大サイズが超過した時
				case UPLOAD_ERR_FORM_SIZE: //フォーム定義の最大サイズを超過した時
					 throw	new RuntimeException('ファイルサイズが大きすぎます');
				default: //その他の場合
					throw new RuntimeException('その他のエラーが発生しました');
			}
			
//			$file['mime']の値はブラウザ側で偽装可能なので、MINEタイプを自前でチェック
//			exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
			$type = @exif_imagetype($file['tmp_name']);
			if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){ //第三引数にはtrueを設定すると厳密にチェックする。必ずつける。
				throw new RuntimeException('画像形式が未対応です');
			}
			
//			ファイルデータからSHA-1ハッシュをとってファイル名を設定し、ファイルを保存する
//			ハッシュ化しておかないとアップロードされたファイル名そのまま保存してしまうと同じファイル名がアップロードされる可能性があり、
//				DBにパスを保存した場合、どっちの画像パスなのか判別つかなくなってします。
//				image_type_to_extension関数はファイルの拡張子を取得するもの
			$path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
			
			if(!move_uploaded_file($file['tmp_name'], $path)){ //ファイルを移動する
				throw new RuntimeException('ファイル保存時にエラーが発生しました');
			}
//			保存したファイルパスのパーミッション（権限）を変更する。
			chmod($path, 0644);
			
			debug('ファイルは正常に保存されました');
			debug('ファイルパス：'.$path);
			return $path;
			
		}catch (RuntimeException $e){
			
			debug($e->getMessage());
			global $err_msg;
			$err_msg[$key] = $e->getMessage();
		}
	}
}

//ページング
//$currentPageNum : 現在のページ数
//$totalPageNum : 総ページ数
//$link : 検索用GETパラメータリンク
//$pageColNum : ページネーション表示用

function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
	//	現在のページが、総ページ数と同じ　かつ　総ページ数が表示項目数以上なら、左にリンク４個出す
	if( $currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
		$minPageNum = $currentPageNum - 4;
		$maxPageNum = $currentPageNum;
		//	現在のページが、総ページ数の１つページ前なら、左にリンク３個、右に１出す。
	}elseif( $currentPageNum == ($totalPageNum-1) && $totalPageNum >= $pageColNum){
		$minPageNum = $currentPageNum - 3;
		$maxPageNum = $currentPageNum + 1;
		//	現在のページが2の場合、左にリンク１個、右にリンク３個出す。
	}elseif( $currentPageNum == 2 && $totalPageNum >= $pageColNum){
		$minPageNum = $currentPageNum - 1;
		$maxPageNum = $currentPageNum + 3;
		//	現在のページが1の場合、左に何も出さない。右に５個出す
	}elseif( $currentPageNum == 1 && $totalPageNum >= $pageColNum){
		$minPageNum = $currentPageNum;
		$maxPageNum = 5;
		//	総ページが表示項目数より少ない場合は、総ページ数をループのMAX、ループのMinを1に設定
	}elseif($totalPageNum < $pageColNum){
		$minPageNum = 1;
		$maxPageNum = $totalPageNum;
		//	それ以降は左に２個出す
	}else{
		$minPageNum = $currentPageNum - 2;
		$maxPageNum = $currentPageNum + 2;
	}

	echo'<div class="pagination">';
		echo'<ul class="pagination-list">';
	if($currentPageNum != 1){ //１ページ以外のページに、”＜”ボタンを設置
		echo'<li class="list-item"><a href="?p=1'.$link.'">&lt;</a></li>';
	}
		for($i = $minPageNum; $i <= $maxPageNum; $i++){
			echo'<li class="list-item';
			if($currentPageNum == $i){ echo ' active'; }
			echo'"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
		} 
		if($currentPageNum != $maxPageNum && $maxPageNum > 1){ //現在のページがMAXではない　かつ　総ページ数が１ページじゃない場合、”＞”ボタンを設置
			echo'<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
		}
	echo'</ul>';
	echo'</div>';
}

//画面表示用関数(登録画像がない場合はnoneimg.jpgを表示させる)
function showImg($path){
	if(empty($path)){
		return 'uploads/noneimg.jpg';
	}else {
		return $path;
	}
}



//GETパラメータ付与
//$del_key : 付与から取り除きたいGETパラメータのキー
function appendGetParam($arr_del_key = array()){
	if(!empty($_GET)){
		$str = '?';
		foreach($_GET as $key => $val){
			if(!in_array($key, $arr_del_key, true)){ //取り除きたいパラメータじゃない場合にurlにくっつけるパラメータを生成
				$str .= $key.'='.$val.'&';
			}
		}
		$str = mb_substr($str, 0, -1, "UTF-8");
		return $str;
	}
}


?>
