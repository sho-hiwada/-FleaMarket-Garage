 <!-- フッター -->

<link rel="stylesheet" href="style.css">


    <footer id="footer">

      <!-- SNS -->
      <div class="sns-nav">
        <ul class="sns">
          <li><a href="" class="button"><i class="fab fa-twitter fa-3x"></i><br>Twitter</a></li>
          <li><a href="" class="button"><i class="fab fa-instagram fa-3x"></i><br>Instagram</a></li>
          <li><a href="" class="button"><i class="fab fa-line fa-3x"></i><br>Line＠</a></li>
          <li><a href="" class="button"><i class="fab fa-youtube fa-3x"></i><br>YouTube</a></li>
          <li><a href="" class="button"><i class="fab fa-blogger fa-3x"></i><br>Blog</a></li>
          <li><a href="" class="button"><i class="fas fa-book-open fa-3x"></i><br>Digital Magazine</a></li>
        </ul>
      </div>
      <div class="sns-responsive">
        <ul class="sns-nav-responsive">
          <li><a href="" class="button"><i class="fab fa-twitter fa-3x"></i><br>Twitter</a></li>
          <li><a href="" class="button"><i class="fab fa-instagram fa-3x"></i><br>Instagram</a></li>
          <li><a href="" class="button"><i class="fab fa-line fa-3x"></i><br>Line＠</a></li>
        </ul>
        <ul  class="sns-nav-responsive2">
          <li><a href="" class="button"><i class="fab fa-youtube fa-3x"></i><br>YouTube</a></li>
          <li><a href="" class="button"><i class="fab fa-blogger fa-3x"></i><br>Blog</a></li>
          <li><a href="" class="button"><i class="fas fa-book-open fa-3x"></i><br>Digital Magazine</a></li>
        </ul>
      </div>


      <!-- フッターナビ -->
      <div class="footer-serect">
        <div>
          <ul class="footer-nav">
            <li class="footer-nav-title">Car line up</li>
											<li class="footer-nav-text"><a href="ProductList.php?c_id=1" class="button">MAZDA3</a></li>
											<li class="footer-nav-text"><a href="ProductList.php?c_id=2" class="button">CX-5</a></li>
											<li class="footer-nav-text"><a href="ProductList.php?c_id=3" class="button">N-BOX</a></li>
          </ul>
        </div>
        <div>
          <ul class="footer-nav">
            <li class="footer-nav-title">User</li>
            <li class="footer-nav-text"><a href="mypage.php" class="button">マイページ</a></li>
            <li class="footer-nav-text"><a href="registProduct.php" class="button">商品登録</a></li>
            <li class="footer-nav-text"><a href="" class="button">掲示板</a></li>
          </ul>
        </div>
        <div>
          <ul class="footer-nav">
            <li class="footer-nav-title">ビジネス</li>
            <li class="footer-nav-text"><a href="" class="button">購入</a></li>
            <li class="footer-nav-text"><a href="" class="button">プライバシーポリシー</a></li>
            <li class="footer-nav-text"><a href="" class="button">お問い合わせ</a></li>
          </ul>
        </div>
      </div>

      <!-- アコーディオン -->

      <ul class="accordion-box">
        <li class="accordion block active-block">
          <div class="acc-btn active">Car line up <div class="icon fa fa-plus-square"></div></div>
            <div class="acc-content current">
              <div class="content">
                <div class="text">
                  <ul id="syncer-acdn-01" class="syncer-acdn-list">
                    <li class="footer-nav-text"><a href="" class="button accordion-button">MAZDA3</a></li>
                    <li class="footer-nav-text"><a href="" class="button accordion-button">CX-5</a></li>
                    <li class="footer-nav-text"><a href="" class="button accordion-button">N-BOX</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </li>

          <li class="accordion block">
            <div class="acc-btn">User <div class="icon fa fa-plus-square"></div></div>
              <div class="acc-content">
                <div class="content">
                  <div class="text">
                    <ul id="syncer-acdn-02" class="syncer-acdn-list">
                      <li class="footer-nav-text"><a href="" class="button accordion-button">マイページ</a></li>
                      <li class="footer-nav-text"><a href="" class="button accordion-button">商品登録</a></li>
                      <li class="footer-nav-text"><a href="" class="button accordion-button">掲示板</a></li>
                    </ul>
                  </div>
                </div>
              </div>
          </li>

          <li class="accordion block">
            <div class="acc-btn">ビジネス<div class="icon fa fa-plus-square"></div></div>
            <div class="acc-content">
              <div class="content">
                <div class="text">
                  <ul id="syncer-acdn-03" class="syncer-acdn-list">
                    <li class="footer-nav-text"><a href="" class="button accordion-button">購入</a></li>
                    <li class="footer-nav-text"><a href="" class="button accordion-button">プライバシーポリシー</a></li>
                    <li class="footer-nav-text"><a href="" class="button accordion-button">お問い合わせ</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </li>
        </ul>




					<!-- コピーライト -->
					<div class="copyright">
						<p>Copyright © hiwada sho Inc. All rights reserved.</p>
					</div>

</footer>

<!-- JQueryの読み込み -->
<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script src="http://code.jquery.com/jquery.min.js"></script>

<!--フッターを下部へ固定-->
<script>
	$(function(){
		
		var $ftr = $('#footer');
		if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
			$ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
		}
		
		// メッセージ表示
		var $jsShowMsg = $('#js-show-msg');
		var msg = $jsShowMsg.text();
		if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
			$jsShowMsg.slideToggle('slow');
			setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
		}
		
		//	画像ライブプレビュー

		var $dropArea = $('.area-drop');
		var $fileInput = $('.input-file');

		$dropArea.on('dragover', function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).css('border', '3px #ccc dashed');
		});
		$dropArea.on('dragleave', function(e){
			e.stopPropagation();
			e.preventDefault();
			$(this).css('border', 'none');
		});
		$fileInput.on('change', function(e){
			$dropArea.css('border', 'none');
			var file = this.files[0],//2. fails配列にファイルが入っています
					$img = $(this).siblings('.prev-img'),  //3. JQueryのsiblingsメソッドで兄弟のimgを取得
					fileReader = new FileReader();									//4.ファイルを読み込むFileReaderオブジェクト

			//5.読み込むが完了した際のイベントハンドラ。imgのsrcにデータをセット
			fileReader.onload = function(event){
				//			読み込んだデータをimgに設定
				$img.attr('src',event.target.result).show();
			};

			//		6.画像の読み込み
			fileReader.readAsDataURL(file);
		});

		//	テキストエリアカウント
		var $countUp = $('#js-count'),
				$countView = $('#js-count-view');
		$countUp.on('keyup', function(e){
			$countView.html($(this).val().length);
		});
		
//		画面切り替え（商品詳細ページ）
		var $switchImgSubs = $('.js-switch-img-sub'),
				$switchImgMain = $('#js-switch-img-main');
		$switchImgSubs.on('click',function(e){
			$switchImgMain.attr('src',$(this).attr('src'));
		});
		
//		お気に入り登録・削除
		var $like,
				likeProductId;
		$like = $('.js-click-like') || null; //nullで変数の中身は空と明示する
		likeProductId = $like.data('productid') || null;
		//数値の0はfalseと判定される。product_idが0ということもあり得るので、0もtrueとする場合はunderfinedとnullを判定する
		if(likeProductId !== undefined && likeProductId !== null){
			$like.on('click',function(){
			var $this = $(this);
			$.ajax({
				type: "POST",
				url: "ajaxLike.php",
				data: { productId : likeProductId}
			}).done(function( data ){
				console.log('Ajax Success');
				//class属性をtoggleでつけ外しする
				$this.toggleClass('active');
			}).fail(function( msg ){
				console.log('Ajax Error');
			});
		});
	}

//<!-- ハンバーガーメニュー-->

	$(function(){
		const hum = $('#hamburger,.close')
		const nav = $('.hamburger-nav')
		hum.on('click',function(){
			nav.toggleClass('toggle');
		});
	});

//<!--スライドショー-->

	var mySwiper = new Swiper('.swiper-container', {
		effect: 'fade',
		autoplay: {
			delay: 3000,
			stopOnLastSlide: false,
			disableOnInteraction: false,
			reverseDirection: false
		},
		loop: true,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev'
		},
		pagination: {
			el: '.swiper-pagination',
			type: 'bullets',
			clickable: true
		}
	});

//<!--   フッターアコーディオンカーテン-->

	(function ($) {
		'use strict';

		if($('.accordion-box').length){
			$(".accordion-box").on('click', '.acc-btn', function() {

				var outerBox = $(this).parents('.accordion-box');
				var target = $(this).parents('.accordion');

				if($(this).hasClass('active')!==true){
					$(outerBox).find('.accordion .acc-btn').removeClass('active ');
				}

				if ($(this).next('.acc-content').is(':visible')){
					return false;
				}else{
					$(this).addClass('active');
					$(outerBox).children('.accordion').removeClass('active-block animated fadeInUp');
					$(outerBox).find('.accordion').children('.acc-content').slideUp(300);
					target.addClass('active-block animated fadeInUp');
					$(this).next('.acc-content').slideDown(300);
				}
			});
		}
	})(window.jQuery);
	});
	
</script>

</body>

</html>
