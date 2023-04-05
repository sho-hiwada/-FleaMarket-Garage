<?php

//共通関数の読み込み
require('function.php');

debug('======================================');
debug('商品登録ページ');
debug('======================================');
debugLogStart();

//ログイン認証
require('auto.php');

//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
//画面処理
//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

//画面表示用データ取得
//======================================
//GETデータ格納
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
//DBから商品データを取得
$dbFormData = (!empty($p_id)) ? getProduct($_SESSION['user_id'], $p_id) : '';
//新規登録画面か編集画面か判別用フラグ
$edit_flg = (empty($dbFormData)) ? false : true;
debug('判別用フラグ結果：' . print_r($edit_flg, true));
//DBからカテゴリデータを取得
$dbCategoryData = getCategory();
debug('商品ID:' . $p_id);
debug('フォーム用DBデータ：' . print_r($dbFormData, true));
debug('カテゴリーデータ：' . print_r($dbCategoryData, true));

//パラメーター改ざんチェック
//========================================
//	GETパラメーターはあるが、改ざんされている（URLをいじった）場合、正しい商品データが読み取れないため、マイページへ遷移させる
if (!empty($p_id) && empty($dbFormData)) {
	debug('GETパラメーターの商品IDが違います。マイページへ遷移します。');
	header("Location:mypage.php"); //マイページへ
}

//POST送信があった時
if (!empty($_POST)) {
	debug('POST送信があります');
	debug('POST情報：' . print_r($_POST, true));
	debug('FILE情報：' . print_r($_FILES, true));

	//	変数にユーザー情報を代入
	$name = $_POST['name'];
	$category = $_POST['category_id'];
	$price = (!empty($_POST['price'])) ? $_POST['price'] : 0; //0や空文字の場合は、0を入れる。デフォルトでは0
	$comment = $_POST['comment'];
	//	画像をアップロードし、パスを格納
	$pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'], 'pic1') : '';
	//	画像をPOSTしてない（登録してない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないから。そうしないと、以前に登録した画像が空として更新されてしまう。）
	$pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1'] : $pic1;
	$pic2 = (!empty($_FILES['pic2']['name'])) ? uploadImg($_FILES['pic2'], 'pic2') : '';
	$pic2 = (empty($pic2) && !empty($dbFormData['pic2'])) ? $dbFormData['pic2'] : $pic2;
	$pic3 = (!empty($_FILES['pic3']['name'])) ? uploadImg($_FILES['pic3'], 'pic3') : '';
	$pic3 = (empty($pic3) && !empty($dbFormData['pic3'])) ? $dbFormData['pic3'] : $pic3;

	//	更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
	//	新規登録画面の場合
	if (empty($dbFormData)) {
		debug('新規登録バリデーション');
		//		未入力チェック
		validRequired($name, 'name');
		//		最大文字数チェック
		validMaxlen($name, 'name');
		//		セレクトボックスチェック
		validSelect($category, 'category_id');
		//		最大文字数チェック
		validMaxlen($comment, 'comment', 500);
		//		未入力チェック
		validRequired($price, 'price');
		//		半角数字チェック
		validNumber($price, 'price');
	} else { //編集画面の場合
		debug('更新登録バリデーション');
		if ($dbFormData['name'] !== $name) {
			//			未入力チェック
			validRequired($name, 'name');
			//			最大文字数チェック
			validMaxlen($name, 'name', 500);
		}
		if ($dbFormData['category_id'] !== $category) {
			//			セレクトボックスチェック
			validSelect($category, 'category_id');
		}
		if ($dbFormData['comment'] !== $comment) {
			//			最大文字数チェック
			validMaxlen($comment, 'comment');
		}
		if ($dbFormData['price'] != $price) {
			//			未入力チェック
			validRequired($price, 'price');
			//			半角数字チェック
			validNumber($price, 'price');
		}
	}

	if (empty($err_msg)) {
		debug('バリデーションOK');

		//		例外処理
		try {
			//			DB接続
			$dbh = dbConnect();
			//			SQL文作成
			//			編集画面＝UPDATE文・新規登録画面＝INSERT文
			if ($edit_flg) {
				debug('DB更新です');
				$sql = 'UPDATE product SET name = :name, category_id = :category, price = :price, comment = :comment, pic1 = :pic1, pic2 = :pic2, pic3 = :pic3 WHERE user_id = :u_id AND id = :p_id';
				$data = array(':name' => $name, ':category' => $category, ':price' => $price, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
			} else {
				debug('DB新規登録です');
				$sql = 'INSERT INTO product (name, category_id, price, comment, pic1, pic2, pic3, user_id, create_date ) VALUES (:name, :category_id, :price, :comment, :pic1, :pic2, :pic3, :u_id, :date)';
				$data = array(':name' => $name, ':category_id' => $category, ':price' => $price, ':comment' => $comment, ':pic1' => $pic1, ':pic2' => $pic2, ':pic3' => $pic3, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
			}
			debug('SQL：' . $sql);
			debug('流し込みデータ：' . print_r($data, true));
			//			クエリ実行
			$stmt = queryPost($dbh, $sql, $data);

			//			クエリ成功の場合
			if ($stmt) {
				$_SESSION['msg_success'] = SUC04;
				debug('マイページへ遷移します');
				header("Location:mypage.php"); //マイページへ
			}
		} catch (Exception $e) {
			error_log('エラー発生：' . $e->getMessage());
			$err_msg['common'] = MSG07;
		}
	}
}
debug('画面処理表示終了＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜')

?>


<!--HTML作成-->

<!--ヘッダーメニュー-->
<?php
$siteTitle = (!$edit_flg) ? '商品登録' : '商品編集';
include(dirname(__file__) . '/header.php');
?>

<!-- メインコンテンツ -->
<div class="site-width">
	<div class="title">
		<h1><?php echo (!$edit_flg) ? '商品を出品する' : '商品を編集する'; ?></h1>
	</div>
	<!--エラーエッセージ-->
	<div class="area-msg">
		<?php
		if (!empty($err_msg['common'])) echo $err_msg['common'];
		?>
	</div>

	<div id="wrapper">
		<div id="main">

			<div class="box">
				<form class="form" action="" method="post" enctype="multipart/form-data">

					<div>
						<label class="profirl-contents">
							商品名<span class="label-require">必須</span>
							<input class="inputs" type="text" name="name" value="<?php echo getFormData('name'); ?>">
						</label>
						<div class="area-msg">
							<?php
							if (!empty($err_msg['name'])) echo $err_msg['name'];
							?>
						</div>
					</div>

					<!--カテゴリー-->
					<div>
						<label>
							カテゴリ<span class="label-require" id="">必須</span>
							<select class="inputs" name="category_id">
								<option value="0" <?php if (getFormData('category_id') == 0) {
																		echo 'selected';
																	} ?>>選択してください</option>
								<?php
								foreach ($dbCategoryData as $key => $val) {
								?>
									<option value="<?php echo $val['id'] ?>" <?php if (getFormData('category_id') == $val['id']) {
																															echo 'selected';
																														} ?>>
										<?php echo $val['name']; ?>
									</option>
								<?php
								}
								?>
							</select>
						</label>
						<div class="area-msg">
							<?php
							if (!empty($err_msg['category_id'])) echo $err_msg['category_id'];
							?>
						</div>
					</div>

					<!--詳細-->
					<div>
						<label>
							詳細
							<textarea class="inputs" name="comment" id="js-count" cols="30" rows="10" style="height:150px; font-size: 14px;"><?php echo getFormData('comment'); ?></textarea>
						</label>
						<p class="counter-text"><span id="js-count-view">0</span>/500文字</p>
						<div class="area-msg">
							<?php
							if (!empty($err_msg['comment'])) echo $err_msg['comment'];
							?>
						</div>
					</div>
					<!--金額-->

					<div>
						<label>
							金額<span class="label-require">必須</span>
							<div class="form-group">
								<input class="inputs" type="text" name="price" style="width:150px;" placeholder="50,000" value="<?php echo (!empty(getFormData('price'))) ? getFormData('price') : 0; ?>"><span class="option">円</span>
							</div>
						</label>
						<div class="area-msg">
							<?php
							if (!empty($err_msg['price'])) echo $err_msg['price'];
							?>
						</div>
					</div>

					<!--画像アップロード-->

					<div style="overflow:hidden;">

						<div class="imgDrop-container">
							画像１
							<label class="area-drop">
								<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
								<input type="file" name="pic1" class="input-file">
								<img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if (empty(getFormData('pic1'))) echo 'display:none;' ?>">
								ドラッグ＆ドロップ
							</label>
							<div class="area-msg">
								<?php
								if (!empty($err_msg['pic1'])) echo $err_msg['pic1'];
								?>
							</div>
						</div>

						<div class="imgDrop-container">
							画像２
							<label class="area-drop">
								<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
								<input type="file" name="pic2" class="input-file">
								<img src="<?php echo getFormData('pic2'); ?>" alt="" class="prev-img" style="<?php if (empty(getFormData('pic2'))) echo 'display:none;' ?>">
								ドラッグ＆ドロップ
							</label>
							<div class="area-msg">
								<?php
								if (!empty($err_msg['pic2'])) echo $err_msg['pic2'];
								?>
							</div>
						</div>

						<div class="imgDrop-container">
							画像３
							<label class="area-drop">
								<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
								<input type="file" name="pic3" class="input-file">
								<img src="<?php echo getFormData('pic3'); ?>" alt="" class="prev-img" style="<?php if (empty(getFormData('pic3'))) echo 'display:none;' ?>">
								ドラッグ＆ドロップ
							</label>
							<div class="area-msg">
								<?php
								if (!empty($err_msg['pic3'])) echo $err_msg['pic3'];
								?>
							</div>
						</div>
					</div>

					<div>
						<input class="btn" type="submit" name="submit" value="<?php echo (!$edit_flg) ? '出品する' : '更新する'; ?>">
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