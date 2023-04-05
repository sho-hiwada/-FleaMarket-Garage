<?php

//共通変数・関数の読み込み
require('function.php');

//エラー表示用
ini_set("display_errors", 1);
error_reporting(E_ALL);

debug('==================================================');
debug('マイページ');
debug('==================================================');
debugLogStart();

//ログイン認証
require('auto.php');

//画面表示用データ取得
//＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
$u_id = $_SESSION['user_id'];
//DBから商品データを取得
$productData = getMyProducts($u_id);
//DBから連絡掲示板データを取得
$bordData = getMyMsgsAndBord($u_id);

// // ユーザーIDに対応するユーザー情報を取得する
// $viewData = getUser($u_id);
// debug('取得した自分の情報：' . print_r($viewData, true));
// //パラメータに不正な値が入っているかチェック
// if (empty($viewData)) {
// 	error_log('エラー発生：指定のページに不正な値が入りました。');
// 	header("Location:mypage.php"); //マイページへ遷移
// }

// //viewDataから相手のユーザーIDを取り出す(自分のIDを取ってきてunsetで取り除く)
// $dealUserIds[] = $bordData[0]['sale_user'];
// $dealUserIds[] = $bordData[0]['buy_user'];
// if (($key = array_search($_SESSION['user_id'], $dealUserIds)) !== false) {
// 	unset($dealUserIds[$key]);
// }
// //相手のユーザーIDを格納
// $partnerUserId = array_shift($dealUserIds);
// debug('取得した相手のユーザーID：' . $partnerUserId);
// //DBから取引相手のユーザー情報を取得
// if (isset($partnerUserId)) {
// 	$partnerUserInfo = getUser($partnerUserId);
// }
// debug('取得した相手のユーザー情報：' . print_r($partnerUserInfo, true));
// //相手のユーザー情報が取れたかチェック
// if (empty($partnerUserInfo)) {
// 	error_log('エラー発生：相手のユーザー情報が取得できませんでした。');
// 	header("Location:mypage.php"); //マイページへ遷移
// }

//DBから相手の名前を取得
// $partnerData = getUser($u_id);

//DBからお気に入りデータを取得
$likeData = getMyLike($u_id);


//DBからきちんとデータが全て取れているかのチェックを行わず、取れていなければ何も表示しない

debug('取得した商品データ：' . print_r($productData, true));
debug('取得した掲示板データ：' . print_r($bordData, true));
debug('取得したお気に入りデータ：' . print_r($likeData, true));

debug('画面処理終了============================================');

?>

<!--HTML作成-->

<!--ヘッダーメニュー-->
<?php
$siteTitle = 'マイページ';
include(dirname(__file__) . '/header.php');
?>

<p id="js-show-msg" style="display:none" class="msg-slide">
	<?php echo getSessionFlash('msg_success'); ?>
</p>

<!-- メインコンテンツ -->
<div class="site-width">
	<div class="title">
		<h1>マイページ</h1>
	</div>

	<div id="wrapper">
		<div id="main">
			<!--		登録者一覧-->
			<div class="mypage-section">
				<div class="mypage-title">
					<h2>登録車一覧</h2>
				</div>

				<div class="panel-list">
					<?php if (!empty($productData)) : foreach ($productData as $key => $val) : ?>
							<a href="registProduct.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>" class="panel">
								<div class="panel-head">
									<img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
								</div>
								<div class="panel-body">
									<p class="panel-title"><?php echo sanitize($val['name']); ?><span class="price">¥<?php echo sanitize(number_format($val['price']));  ?></span></p>
								</div>
							</a>
					<?php
						endforeach;
					endif;
					?>
				</div>
			</div>

			<!-- 連絡掲示板-->
			<section class="mypage-section">
				<h2 class="mypage-title">
					連絡掲示板一覧
				</h2>
				<div class="mypage-section-container">
					<table class="table">
						<thead>
							<tr>
								<th>最新送信日時</th>
								<th>取引相手</th>
								<th>メッセージ</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if (!empty($bordData)) {
								foreach ($bordData as $key => $val) {
									if (!empty($val['msg'])) {
										$msg = array_shift($val['msg']);
							?>
										<tr>
											<td><?php echo sanitize(date('Y.m.d H:i:s', strtotime($msg['send_date']))); ?></td>

											<td><?php echo sanitize($val['username']); ?></td>

											<td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>"><?php echo mb_substr(sanitize($msg['msg']), 0, 40); ?>...</a></td>
										</tr>
									<?php
									} else {
									?>
										<tr>
											<td>-- --</td>
											<td>○○　○○</td>
											<td><a href="">メッセージのやりとりはありません</a></td>
										</tr>
							<?php
									}
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</section>

			<!--                  お気に入り-->
			<div class="mypage-section" style="background: #fafafa;">
				<div class="mypage-title">
					<h2>お気に入り一覧</h2>
				</div>

				<div class="panel-list">
					<?php
					if (!empty($likeData)) :
						foreach ($likeData as $key => $val) :
					?>
							<a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam() . '&p_id=' . $val['id'] : '?p_id=' . $val['id']; ?>" class="panel">

								<div class="panel-head">
									<img src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
								</div>
								<div class="panel-body">
									<p class="panel-title"><?php echo sanitize($val['name']); ?><span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
								</div>


							</a>

					<?php
						endforeach;
					endif;
					?>
				</div>
			</div>
		</div>

		<!-- サイドバー-->
		<?php require('sidebar.php'); ?>


	</div>
</div>

<!-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->

<!-- フッター -->
<!--フッターメニュー-->
<?php include(dirname(__FILE__) . '/footer.php'); ?>