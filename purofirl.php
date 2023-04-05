	<?php

	require('function.php');

	debug('=====================================');
	debug('プロフィール編集画面');
	debug('=====================================');
	debugLogStart();

	require('auto.php');

	//=====================================
	//画面処理開始
	//=====================================
	//DBからユーザー情報の取得
	$dbFormData = getUser($_SESSION['user_id']);

	debug('取得したユーザー情報：'.print_r($dbFormData,true));

	//POST送信されていた場合
	if(!empty($_POST)){
		debug('POST送信があります');
		debug('POST情報：'.print_r($_POST,true));
		debug('POST情報：'.print_r($_FILES,true));

	//	変数にユーザー情報を代入
		$username = $_POST['username'];
		$tel = $_POST['tel'];
		$zip = (!empty($_POST['zip'])) ? $_POST['zip'] : 0; //空の場合0を入れる
		$addr = $_POST['addr'];
		$age = (!empty($_POST['age'])) ? $_POST['age'] : 0; //空の場合0を入れる
		$email = $_POST['email'];
		//画像をアップロードし、パスを格納
		$pic = ( !empty($_FILES['pic']['name']) ) ? uploadImg($_FILES['pic'],'pic') : '';
		$pic = ( empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $pic;

		//DBの情報と入力情報が異なる場合にバリデーションを行う

		if($dbFormData['username'] !== $username){
			//	名前の最大文字数チェック
			validMaxLen($username, 'username');
			debug('名前OK');
		}

		if(!empty($_POST['tel']) && $dbFormData['tel'] !== $tel){ //dbのTELと違う場合
			//	TEL形式チェック
			validTel($tel, 'tel');
			debug('電話番号OK');
		}

		if(!empty($_POST['tel']) && $dbFormData['addr'] !== $addr){
			//	住所の最大文字数チェック
			validMaxLen($addr, 'addr');
			debug('住所OK');
		}

		if(!empty($_POST['zip']) && (int)$dbFormData['zip'] !== $zip){//DBデータをint型にキャスト（型変換）して比較
			//	郵便番号形式チェック
			validZip($zip, 'zip');
			debug('郵便番号OK');
		}

		if(!empty($_POST['age']) && (int)$dbFormData['age'] !== $age){
			//	年齢の最大文字数チェック
			validMaxLen($age, 'age');
			//	年齢の半角数字チェック
			validNumber($age, 'age');
			debug('年齢OK');
		}

		if($dbFormData['email'] !== $email){
			//	emailの最大文字数チェック
			validMaxLen($email, 'email');
			if(empty($err_msg['email'])){
				//		emailの重複チェック
				validEmailDup($email);
			}
			//	emailの形式チェック
			validEmail($email, 'email');
			//	emailの未入力チェック
			validRequired($email, 'email');
			debug('email OK');
		}

		if(empty($err_msg)){
			debug('バリデーションOKです');

	//		例外処理
			try {
	//			DBへ接続
				$dbh = dbConnect();
				debug('db接続');
	//			SQL文作成
				$sql = 'UPDATE users SET username = :u_name, tel = :tel, zip = :zip, addr = :addr, age = :age, email = :email, pic = :pic WHERE id = :u_id';
				$data = array(':u_name' => $username, ':tel' => $tel, ':zip' => $zip, ':addr' => $addr, ':age' => $age, ':email' => $email,':pic' => $pic, ':u_id' => $dbFormData['id']);
	//			クエリ実行
				$stmt = queryPost($dbh, $sql, $data);

	//			クエリ成功の場合
				if($stmt){
					debug('マイページへ遷移します');
					header("Location:mypage.php");//マイページへ
				}

			} catch (Exception $e) {
				error_log('エラー発生：' . $e->getMessage());
				$err_msg['common'] = MSG07;
			}
		}else {
			debug('バリデーションNG');
		}
	}
	debug('画面処理終了<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

	?>

	<!--HTML作成-->

	<!--ヘッダーメニュー-->
<?php
$siteTitle = 'プロフィール画面';
include ( dirname(__file__) . '/header.php');
?>

	<!-- メインコンテンツ -->
	<div class="site-width">
		<div class="title">
			<h1>プロフィール編集</h1>
		</div>
		<!--								エラーエッセージ-->
		<div class="area-msg">
			<?php
					if(!empty($err_msg['common'])) echo $err_msg['common'];
				?>
		</div>

		<div id="wrapper">
			<div id="main">

				<div class="box">
					<form class="form" action="" method="post" enctype="multipart/form-data">

						<div class="prof_item">
							<label class="profirl-contents" for="name">
								名前
								<input class="inputs" type="text" name="username" id="name" value="<?php echo getFormData('username'); ?>">
							</label>
							<div class="err_msg">
								<?php if(!empty($err_msg['username'])) echo $err_msg['username']; ?>
							</div>
						</div>

						<div class="prof_item">
							<label class="profirl-contents">
								電話番号<span style="font-size: 12px;margin-left:5px;">※ハイフン無しでご入力ください</span>
								<input class="inputs" type="text" name="tel" value="<?php if(!empty(getFormData('tel')) ){ echo getFormdata('tel'); }?>">
							</label>
							<div class="err_msg">
								<?php if(!empty($err_msg['tel'])) echo $err_msg['tel']; ?>
							</div>
						</div>

						<div class="prof_item">
							<label class="profirl-contents">
								郵便番号<span style="font-size: 12px;margin-left:5px;">※ハイフン無しでご入力ください</span>
								<input class="inputs" type="text" name="zip" value="<?php if(!empty(getFormData('zip')) ){ echo getFormData('zip'); } ?>">
							</label>
							<div class="err_msg">
								<?php if(!empty($err_msg['zip'])) echo $err_msg['zip']; ?>
							</div>
						</div>

						<div class="prof_item">
							<label class="profirl-contents">
								住所
								<input class="inputs" type="text" name="addr" value="<?php if(!empty(getFormData('addr')) ) { echo getFormData('addr'); } ?>">
							</label>
							<div class="err_msg">
								<?php if(!empty($err_msg['addr'])) echo $err_msg['addr']; ?>
							</div>
						</div>

						<div class="prof_item">
							<label class="profirl-contents">
								年齢<br>
								<input style="width:100px;" class="inputs" type="number" name="age" value="<?php ?>"><span class="age-text">歳</span>
							</label>
							<div class="err_msg">
								<?php if(!empty($err_msg['age'])) echo $err_msg['age']; ?>
							</div>
						</div>

						<div class="prof_item">
							<label class="profirl-contents">
								E-mail
								<input class="inputs" type="email" name="email" value="<?php echo getFormData('email'); ?>">
							</label>
							<div class="err_msg">
								<?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
							</div>
						</div>

						<div class="prof_item">
							<div class="profirl-contents">
								プロフィール画像
								<label class="area-drop" style="width:370px;height:370px;line-height:370px;">>
									<input type="hidden" name="MAX_FILE_SIZE" value="3145728">
									<input type="file" name="pic" class="input-file" style="height:370px;">
									<img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
									ドラッグ＆ドロップ
								</label>

								<div class="area-msg">
									<?php
										if(!empty($err_msg['pic'])) echo $err_msg['pic'];
									?>
								</div>
							</div>
							<div class="area-msg">
								<?php
	if(!empty($err_msg['pic'])) echo $err_msg['pic'];
									?>
							</div>

						</div>
						<div class="prof_item">
							<input class="btn" type="submit" value="変更する">
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
