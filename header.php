<!DOCTYPE html>
<html lang="ja">

<head>
	<meta chiaset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $siteTitle; ?> | CAR　SERECT　SHOP</title>
	<!--    スタイルシート-->
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="new.css">
	<link rel="stylesheet" href="mypage.css">
	<script src="main.js"></script>
	<!--    フォントアイコン（CDN)-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.10/font-awesome-animation.css" type="text/css" media="all" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.3/css/swiper.min.css">
	<!-- スライドショー -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
	<script src="https://kit.fontawesome.com/9f2d205bbb.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">


	<!-- スマホナビの表示・非表示 -->



</head>

<body>

	<!-- ヘッダー -->
	<header>
		<div class="header-logo">
			<a href="index.php"><img src="img/logo.jpg"></a>
		</div>
		<nav class="header-nav">
			<ul>
				<li><a href="ProductList.php?c_id=1" class="button">MAZDA3</a></li>
				<li><a href="ProductList.php?c_id=2" class="button">CX-5</a></li>
				<li><a href="ProductList.php?c_id=3" class="button">N-BOX</a></li>
				<li><a href="mypage.php" class="button">マイページ</a></li>
				<li><a href="signup.php" class="button">新規登録</a></li>
				<li><a href="login.php" class="button">ログイン</a></li>
				<li><a href="log_outo.php" class="button">ログアウト</a></li>
			</ul>
		</nav>
		<nav class="hamburger-nav">
			<ul>
				<li><a href="ProductList.php?c_id=1" class="button">MAZDA3</a></li>
				<li><a href="ProductList.php?c_id=2" class="button">FIT</a></li>
				<li><a href="ProductList.php?c_id=3" class="button">N-BOX</a></li>
				<li><a href="mypage.php" class="button">マイページ</a></li>
				<li><a href="signup.php" class="button">新規登録</a></li>
				<li><a href="login.php" class="button">ログイン</a></li>
				<li><a href="log_outo.php" class="button">ログアウト</a></li>
				<li class="close"><span>閉じる</span></li>
			</ul>
		</nav>
		<div id="hamburger">
			<span></span>
		</div>
	</header>

	<!-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->
