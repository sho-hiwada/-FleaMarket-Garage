<!-- ヘッダー -->
<header>
	<?php
			$siteTitle = 'メイン画面';
			include ( dirname(__file__) . '/header.php');
			?>
</header>


<!-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->

<body>


	<!-- Swiper START -->
	<div class="wrapper">
		<ul class="slider">
			<li class="slider-item slider-item01"></li>
			<li class="slider-item slider-item02"></li>
			<li class="slider-item slider-item03"></li>
		</ul>
		<!--/wrapper-->
	</div>
	<!-- Swiper END -->

	<!-- メインコンテンツ -->
	<div id="main">

		<!-- TOPIC -->
		<section id="favorite">
			<div>
				<p><a>・マツダ車のお届け遅延のお詫びとお知らせ</a></p>
				<p><a>・新型コロナウイルス感染拡大に伴う対応について</a></p>
				<p><a>・タカタ製エアバッグリコールに関する大切なお知らせ</a></p>
			</div>
		</section>

		<!-- ラインナップ -->
		<section id="new">
			<h1>LINE UP</h1>
			<div>
				<img>
				<img>
				<img>
			</div>
			<p>３種類の車を比較して、自分にピッタリな１台を見つけよう！<br>詳しくは<span><a>コチラ</a></span></p>

		</section>

		<!-- トピック -->
		<section id="topic">
			<p id="tabcontrol">
				<a href="#tabpage1">タブ1</a>
				<a href="#tabpage2">タ2</a>
				<a href="#tabpage3">タブ3</a>
			</p>
			<div id="tabbody">
				<div id="tabpage1">…… タブ1の中身 ……</div>
				<div id="tabpage2">…… タブ2の中身 ……</div>
				<div id="tabpage3">…… タブ3の中身 ……</div>
			</div>
		</section>

		<!-- ニュース -->
		<section id="news">
			<div>
				<div>
					<img>
					<p></p>
				</div>
				<div>
					<img>
					<p></p>
				</div>
				<div>
					<img>
					<p></p>
				</div>
				<div>
					<img>
					<p></p>
				</div>
			</div>
		</section>

		<!--		TOPICS-->
		<section>
			<h1>TOPICS</h1>
			<div class="swiper-container">
				<!-- メイン表示部分 -->
				<div class="swiper-wrapper">
					<!-- 各スライド -->
					<div class="swiper-slide">Slide 1</div>
					<div class="swiper-slide">Slide 2</div>
					<div class="swiper-slide">Slide 3</div>
					<div class="swiper-slide">Slide 4</div>
					<div class="swiper-slide">Slide 1</div>
					<div class="swiper-slide">Slide 2</div>
					<div class="swiper-slide">Slide 3</div>
					<div class="swiper-slide">Slide 4</div>
				</div>
				<div class="swiper-scrollbar"></div>
			</div>
		</section>

	</div>


	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

	<!--
	<script>
		// [.syncer-acdn]にクリックイベントを設定する
		$('.syncer-acdn').click(function() {
			// [data-target]の属性値を代入する
			var target = $(this).data('target');

			// [target]と同じ名前のIDを持つ要素に[slideToggle()]を実行する
			$('#' + target).slideToggle();
		});
	</script>
-->

	<script>
		$('.slider').slick({
			fade: true, //切り替えをフェードで行う。初期値はfalse。
			autoplay: true, //自動的に動き出すか。初期値はfalse。
			autoplaySpeed: 3000, //次のスライドに切り替わる待ち時間
			speed: 1000, //スライドの動きのスピード。初期値は300。
			infinite: true, //スライドをループさせるかどうか。初期値はtrue。
			slidesToShow: 1, //スライドを画面に3枚見せる
			slidesToScroll: 1, //1回のスクロールで3枚の写真を移動して見せる
			arrows: true, //左右の矢印あり
			prevArrow: '<div class="slick-prev"></div>', //矢印部分PreviewのHTMLを変更
			nextArrow: '<div class="slick-next"></div>', //矢印部分NextのHTMLを変更
			dots: true, //下部ドットナビゲーションの表示
			pauseOnFocus: false, //フォーカスで一時停止を無効
			pauseOnHover: false, //マウスホバーで一時停止を無効
			pauseOnDotsHover: false, //ドットナビゲーションをマウスホバーで一時停止を無効
		});

		//スマホ用：スライダーをタッチしても止めずにスライドをさせたい場合
		$('.slider').on('touchmove', function(event, slick, currentSlide, nextSlide) {
			$('.slider').slick('slickPlay');
		});

		var tabs = document.getElementById('tabcontrol').getElementsByTagName('a');
		var pages = document.getElementById('tabbody').getElementsByTagName('div');

		function changeTab() {
			// ▼href属性値から対象のid名を抜き出す
			var targetid = this.href.substring(this.href.indexOf('#') + 1, this.href.length);

			// ▼指定のタブページだけを表示する
			for (var i = 0; i < pages.length; i++) {
				if (pages[i].id != targetid) {
					pages[i].style.display = "none";
				} else {
					pages[i].style.display = "block";
				}
			}

			// ▼クリックされたタブを前面に表示する
			for (var i = 0; i < tabs.length; i++) {
				tabs[i].style.zIndex = "0";
			}
			this.style.zIndex = "10";

			// ▼ページ遷移しないようにfalseを返す
			return false;
		}

		// ▼すべてのタブに対して、クリック時にchangeTab関数が実行されるよう指定する
		for (var i = 0; i < tabs.length; i++) {
			tabs[i].onclick = changeTab;
		}

		// ▼最初は先頭のタブを選択
		tabs[0].onclick();

	</script>
</body>
<!-- ーーー
ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー -->
<!--フッターメニュー-->
<?php include ( dirname(__FILE__) . '/footer.php'); ?>
