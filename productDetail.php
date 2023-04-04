<?php

//ini_set("display_errors", 1);
//error_reporting(E_ALL);

//共通変数・関数の読み込み
require('function.php');

debug('========================================');
debug('商品詳細画面');
debug('========================================');
debugLogStart();

//=============================================
//	画面処理開始
//=============================================

//画面表示用データの取得
//=============================================
//商品IDのGETパラメータを取得
$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
//DBから商品データを取得
$viewData = getProductOne($p_id);
//var_dump($viewData);
//パラメータに不正な値が入っているかチェック
if(empty($viewData)){
	error_log('エラー発生：指定ページに不正な値が入りました');
	header("Location:index.php"); //TOPページへ遷移する。
}
debug('商品ID：'.print_r($_GET['p_id'],true));

//商品画像を判別。なければnoneimg表示
$productImgNone = 'uploads/noneimg.jpg';

//POST送信されていた場合
if(!empty($_POST['submit'])){
	debug('POST情報があります。');
	
//	ログイン認証
	require('auto.php');
	
//	例外処理
	try {
//		DB接続
		$dbh = dbConnect();
//		SQL文作成
		$sql = 'INSERT INTO bord (sale_user, buy_user, product_id, create_date) VALUES (:s_uid, :b_uid, :p_id, :date)';
		$data = array(':s_uid' => $viewData['user_id'], ':b_uid' => $_SESSION['user_id'], ':p_id' => $p_id, ':date' => date('Y-m-d H:i:s'));
//		クエリ実行
		$stmt = queryPost($dbh, $sql, $data);
		
//		クエリ成功の場合
		if($stmt){
			$_SESSION['msg_success'] = SUC05;
			debug('連絡掲示板へ遷移します。');
			header("Location:msg.php?m_id=".$dbh->lastInsertID()); //連絡掲示板へ
		}
		
	}catch (Exception $e) {
		error_log('エラー発生：' . $e->getMessage());
		$err_msg['common'] = MSG07;
	}
}

debug('画面処理終了＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜');
?>



<!--HTML作成-->

<!--ヘッダーメニュー-->
<?php
$siteTitle = '商品詳細';
include ( dirname(__file__) . '/header.php');
?>

<!-- メインコンテンツ -->
<div class="site-width">
	<div class="title">
		<h2>商品詳細画面</h2>
	</div>

	<div class="product-title">

		<div style="height: 80px;">
			<h2>商品タイトル</h2>
			<span class="debug"><?php echo sanitize($viewData['category']); ?></span>
			<?php echo sanitize($viewData['name']); ?>
			<i class="far fa-heart icn-like js-click-like <?php if(isLike($_SESSION['user_id'], $viewData['id'])){echo 'active';} ?>" aria-hidden="true" data-productid="<?php echo sanitize($viewData['id']); ?>"></i>
		</div>

	</div>
	<div class="product-img-box">
		<!--                 メイン画像-->
		<div class="img-main">
			<img src="<?php (!empty($viewData['pic1'])) ? print showImg(sanitize($viewData['pic1'])) : print $productImgNone; ?>" alt="メイン画像：<?php echo sanitize($viewData['name']); ?>" id="js-switch-img-main">
		</div>

		<!--                  サブ画像-->
		<div class="img-sub">
			<img src="<?php (!empty($viewData['pic1'])) ? print showImg(sanitize($viewData['pic1'])) : print $productImgNone; ?>" alt="画像１：<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
			<img src="<?php (!empty($viewData['pic2'])) ? print showImg(sanitize($viewData['pic2'])) : print $productImgNone; ?>" alt="画像２：<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
			<img src="<?php (!empty($viewData['pic3'])) ? print showImg(sanitize($viewData['pic3'])) : print $productImgNone; ?>" alt="画像３：<?php echo sanitize($viewData['name']); ?>" class="js-switch-img-sub">
		</div>
	</div>

	<!--                 商品詳細-->
	<div class="product-comment">
		<p><?php echo sanitize($viewData['comment']); ?></p>
	</div>


	<div class="product-buy">
		<div class="item-left">
			<a href="ProductList.php<?php echo appendGetParam(array('p_id')); ?>"> >&lt;商品一覧へ戻る</a>
		</div>

		<form action="" method="post">
			<div class="item-right">
				<input type="submit" value="購入" name="submit" class="btn">
			</div>
		</form>

		<div class="item-right">
			<p class="price">¥<?php echo sanitize(number_format($viewData['price'])); ?>-</p>
		</div>
	</div>
</div>


<!--フッターメニュー-->
<?php include ( dirname(__FILE__) . '/footer.php'); ?>
