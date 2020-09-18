

// ページ移動時に警告（フォーム入力画面）

$(function(){
	changeFlg = false;
	$(window).on('beforeunload', function() {
		    if (changeFlg) {
      return "ページを閉じようとしています。入力した情報が失われますがよろしいですか？";
    }
	});
		
  $("form input, form textarea, form select").change(function() {
    changeFlg = true;
	});
	

  $("input[type=submit]").click(function() {
    changeFlg = false;
  });
  $("button[type=submit]").click(function() {
    changeFlg = false;
  });

});



// 「いいね」機能
$(function(){
	var $good = $('.btn-good'), //いいねボタンセレクタ
							goodPostId; //投稿ID
	$good.on('click',function(e){
			e.stopPropagation();
			var $this = $(this);
			//カスタム属性（postid）に格納された投稿ID取得
			goodPostId = $this.parents('.post').data('postid'); 
			$.ajax({
					type: 'POST',
					url: 'goodAjax.php', //post送信を受けとるphpファイル
					data: { postId: goodPostId} //{キー:投稿ID}
			}).done(function(data){
					console.log('Ajax Success');

					// いいねの総数を表示
					$this.children('span').html(data);
					// いいね取り消しのスタイル
					$this.children('i').toggleClass('far'); //空洞ハート
					// いいね押した時のスタイル
					$this.children('i').toggleClass('fas'); //塗りつぶしハート
					$this.children('i').toggleClass('active');
					$this.toggleClass('active');
			}).fail(function(msg) {
					console.log('Ajax Error');
			});
	});
});



// フォロー機能
$(function(){
	var $follow = $('.btn-follow'), //フォローボタンセレクタ
							followId; //投稿ID
	$follow.on('click',function(e){
			e.stopPropagation();
			var $this = $(this);
			//カスタム属性（followid）に格納された投稿ID取得
			followId = $this.parents('.post').data('followid'); 
			$.ajax({
					type: 'POST',
					url: 'followAjax.php', //post送信を受けとるphpファイル
					data: { postFollowId: followId} //{キー:投稿ID}
			}).done(function(data){
					console.log('Ajax Success');

					// フォローの総数を表示
					$("#num-followers").find('a').html(data);
					// $this.children('span').html(data);
					// フォロー取り消し
					$this.toggleClass('active');
					// 文言変更
					if ($this.find('span').hasClass('follow')) {
						$this.find('span').toggleClass('follow');
						$this.find('span').text('フォローする');
					} else {
						$this.find('span').toggleClass('follow');
						$this.find('span').text('フォロー中');
					}
			}).fail(function(msg) {
					console.log('Ajax Error');
			});
	});
});



// プロフィール編集ボタン押下（モーダルウィンドウ）
$(function(){
	$('.js-profile-modal-open').each(function() {
		$(this).on('click',function(){
			var target = $(this).data('target');
      var modal = document.getElementById(target);
			$(modal).fadeIn();
			return false;
		});
	});
	$('.js-profile-modal-close').on('click',function(){
			$('.js-profile-modal').fadeOut();
			return false;
	});
});

// プロフィール画像右クリック禁止
// document.oncontextmenu = function(){ return false; };
// document.body.oncontextmenu = "return false;"



// プロフィール画像アップ
// アイコンをクリックした場合は、ファイル選択をクリックした挙動とする.
$('#file_select_icon').on('click', function() {
	$('#file_select').click();
});

// ファイル選択時に表示用テキストボックスへ値を連動させる.
// ファイル選択値のクリア機能の実装により、#file_select がDOMから消されることがあるので親要素からセレクタ指定でイベントを割り当てる.
$('#file_select').parent().on('change', '#file_select', function() {
	// $('#file_name').val($(this).val());
	$('#file_name').val($('#file_select').prop('files')[0].name);
});



