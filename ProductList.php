<?php

//共通変数・関数の読み込み
require('function.php');

//エラー表示用
//ini_set("display_errors", 1);
//error_reporting(E_ALL);


debug('================================================');
debug('商品検索ページ');
debug('================================================');
debugLogStart();

//================================================
//	画面処理開始
//================================================

//画面表示用データ取得
//================================================
//$_GETパラメータを取得
//=================================================
//	カレントページ(現在のページを取得している)
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは1ページに設定
//カテゴリー
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';


	
//パラメータに不正な値が入っているかチェック
if(!is_int((int)$currentPageNum)){
	error_log('エラー発生：指定ページに不正な値が入りました');
	header("Location:index.php"); //TOPページへ
}

//表示件数
$listSpan = 20;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //1ページ目（1-1)*20=0,2ページ目　(2-1)*20 = 20
//DBから商品データを取得
$dbProductData = getProductList($currentMinNum, $category, $sort);
//DBからカテゴリーデータを取得
$dbCategoryData = getCategory();
debug('現在のページ：'.$currentPageNum);
//商品画像を判別。なければnoneimg表示
$productImgNone = 'uploads/noneimg.jpg';
//debug('取得したユーザー情報：'.print_r($productImgNone,true));
debug('画面処理終了＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜＜');

?>



<!--HTML作成-->

<!--ヘッダー-->
<?php
$siteTitle = '商品一覧';
include ( dirname(__file__) . '/header.php');
?>

<!--メインコンテンツ-->
<div class="site-width">
	<div class="title">
		<h1>商品一覧</h1>
	</div>
	
		<div class="wrapper">
		
		
<!--		検索バー-->
			<div class="product-search-bar">
				<form name="" method="get">
				
					<h1 class="search-bar-title">カテゴリー</h1>
					<div class="selectbox">
						<span class="icn_select"></span>
						<select class="search-bar-select" name="c_id" id="">
							<option value="0" <?php if(getFormData('c_id', true) == 0){ echo 'selected'; } ?>>選択してください</option>
							<?php
								foreach($dbCategoryData as $key => $val){
							?>
								<option value="<?php echo $val['id'] ?>"<?php if(getFormData('c_id', true) == $val['id'] ){ echo 'selected';}?>><?php echo $val['name']; ?></option>
								<?php
								 }
								?>
						</select>
					</div>
						<h1 class="search-bar-title">表示順</h1>
						<div class="selectbox">
							<span class="icn_select"></span>
							<select  class="search-bar-select" name="sort">
								<option value="0" <?php if(getFormData('sort', true) == 0 ){ echo 'selected'; }?> >選択してください</option>
								<option value="1" <?php if(getFormData('sort', true) == 1 ){ echo 'selected'; }?> >金額が安い順</option>
								<option value="2" <?php if(getFormData('sort', true) == 2 ){ echo 'selected'; }?> >金額が高い順</option>
							</select>
						</div>
					<input  class="search-bar-button" type="submit" value="検索">
				</form>
			</div>
			
			
			
<!--			メイン-->
			<div id="main">
				<div class="search-title">
					<div class="search-title-left">
						<span class="total-num"><?php echo sanitize($dbProductData['total']); ?></span>件の商品が見つかりました
					</div>
					<div class="search-title-right">
						<span class="num"><?php echo (!empty($dbProductData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num"><?php echo $currentMinNum+count($dbProductData['data']); ?></span>件 / <span class="num"><?php echo sanitize($dbProductData['total']); ?></span>件中
					</div>
				</div>
				
				
					<div class="panel-list">
						<?php
							foreach($dbProductData['data'] as $key => $val):
						?>
							<a href="productDetail.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&p_id='.$val['id'] : '?p_id='.$val['id']; ?>" class="panel">
								<div class="panel-head">
									<img src="<?php (!empty($val['pic1'])) ? print sanitize($val['pic1']) : print $productImgNone;  ?>" alt="<?php echo sanitize($val['name']); ?>">
								</div>
								<div class="panel-body">
									<p class="panel-title"><?php echo sanitize($val['name']); ?><span class="price">¥<?php echo sanitize(number_format($val['price'])); ?></span></p>
								</div>
							</a>
							<?php
							 endforeach;
							?>
					</div>
					
<!--					ページング（ページ数）表示-->

				<?php pagination($currentPageNum, $dbProductData['total_page']); ?>
					
				</div>
	</div>
</div>



<!--フッターメニュー-->
<?php include ( dirname(__FILE__) . '/footer.php'); ?>