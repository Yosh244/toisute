<?php

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/review.php';
require_once __DIR__ . '/../app/functions/user.php';
require_once __DIR__ . '/../app/functions/product.php';

?>
<?php


if(!empty($_SESSION['user_id'])) {
	$user_id = (int)$_SESSION['user_id'];
}





//2重ポスト防止―ワンタイムチケット生成
$ticket = md5(uniqid(rand(), true));
$_SESSION['ticket'] = $ticket;

// 書名を取得する
$product_id = $_GET['id'];
$product_id = (int)$mysqli->real_escape_string($product_id);
$sql = "SELECT * FROM materials WHERE material_id = $product_id";
$res = $mysqli->query($sql);

// URL誤入力時のリダイレクト
if(mysqli_num_rows($res) == 0) {
		header("Location: index.php");
		exit;
}
?>





<!-- 書名＆出版社＆書影 -->
<div class="wrapper-review px-3 pt-3">


	<div class="container-fluid">
		<div class="row justify-content-center mb-2">
			<div class="col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">

				<?php
					foreach ($res as $row); ?>
							<!-- 書影 -->
									<img src="<?php 
									echo $row['material_img']?>" alt="book_cover" width="120px" height="160px"/>


<?php 		if(!empty($user_id)) {
		 //ログイン済みの場合
			// .-1 投稿済みでなければボタン表示
					$review_check_res = reviewExists($product_id, $user_id, $mysqli);
					if ($review_check_res == false ) { ?>
						<button type="button" name="editStart" class=" btn btn-warning btn-sm mt-2" onclick="location.href='detail_post.php?id=<?php echo $_GET['id'];?>'"><i class="fas fa-pen-alt p-0"></i>レビューする</button>
					<?php 
						} 
					}
?>









							</div>
						<div class="col-sm-5">
							<!-- 書名 -->

									<label style="font-size: 1.2rem;"><?php echo $row['material_name'].'（'.$row['material_publisher'].'）'; ?></label>
					<?php 
					$res->close();
					?>
										<script>
													const images = document.querySelectorAll('img');
													images.forEach((image) => {
													　image.addEventListener('error',() => {
													　　image.setAttribute('src', 'https://aws-and-infra-wp-toisute.s3-ap-northeast-1.amazonaws.com/self-upload/website/no_img_02.png');
													　});
													});
										</script>


									</div> <!-- end of col -->
									</div> <!-- end of row -->
									</div> <!-- end of container -->



<div class="container-fluid justify-content-center " style=" text-align:right ;">
	<div class="row justify-content-center">
		<div class="col-sm-7 text-left" >



<?php 
		// ①未ログインならログインを促す
		if(!isset($user_id)) {
			echo '<div class="text-center;">レビューを投稿するには、<a href="login.php">ログイン</a>してください。</div>';
			$_SESSION['return'] = $_SERVER['REQUEST_URI'];
		}

		if(!empty($user_id)) {
			if ($review_check_res != false ) { 
				echo '<div style="text-align: center";>レビューをすでに投稿済みです。<a href="user_profile.php?id='.$user_id.'">投稿一覧</a>から編集できます。</div>'; 
			} 
		}
?>
</div>
</div>
</div>

								<?php
								$reviews_data = fetch_reviews($product_id, $mysqli);
								// レビューがなければ
								if ( $reviews_data == false ) {
							?>
													<div class="container-fluid py-2">
														<div class="text-center">
																<?php echo 'まだレビューがありません。<br>最初のレビューを投稿してみましょう！'; ?>
														</div>
													</div>
<?php } ?>



<?php 
if ( $reviews_data != false ) {?>
<div class="container-fluid pb-1">
<div class="row justify-content-center" >
<div class=" col-sm-10 col-lg-8 col-xl-7">
	<h4 class="h3-line m-0 mt-3">みんなのレビュー</h4>
</div>
</div>
</div>
<?php }; ?>



<?php 

	if($reviews_data) {

			foreach ($reviews_data as $review_data ) {
			?>

<div class="container-fluid p-0">
					<div class="row no-gutters justify-content-center pb-4" >
						<div class="col-sm-10 col-sm-8 col-lg-8 col-xl-7 review">
							<div class="card review">
								<!-- <div class="card-header"></div> -->
									<div class="card-body pt-4 px-5" style="
background-color: #FFFBF7;">

												<div id="review-no-<?php echo $review_data['review_id']; ?>" class="d-inline-block review_user_name align-bottom" >


															<?php 
															// ユーザー
															if ($review_data['delete_flg'] === "1" ) {echo '<span style="font-size: 0.8rem;">削除されたユーザー</span>' ; } 
															else { 
															?>
																	<a class="align-bottom" href="user_profile.php?id=<?php echo $review_data['review_user_id']; ?>" >
														<!-- // プロフィール画像 -->
																	<img src="<?php if ($review_data['user_img'] === "0") { echo fetch_defaultUserImg() ;} else { echo $review_data['user_img'];}; ?>" alt="user_profile_img" class="rounded-circle user_profile_img " width="45px" height="45px"  oncontextmenu="return false;" style="vertical-align: text-top;"/></a>	
										<div class="d-block">
													<!-- ユーザー名 -->
														<a href="user_profile.php?id=<?php echo $review_data['review_user_id']; ?>" ><span  style="color: #505050"><?php echo $review_data['user_name'];			}	 ?></span></a>
										</div>
	</div>

	<div class="d-inline-block align-top font-weight-bold " style="width:80%;">
							<p class=""><?php echo $review_data['review_user_title']; ?></p>
				
	</div>

		<!-- 詳細 -->
							<div>
								<label class="d-inline">◆教材の使用期間：</label>
									<div class="material-desc-text d-inline">
										<?php echo $review_data['review_user_study_period']; ?>
									</div>
							</div>
							<div>
								<label class="d-inline">◆学習前のスコア：</label>
									<div class="material-desc-text d-inline">
												<span class="mr-2">L<?php echo $review_data['review_user_before_L']; ?>点</span>
												<span>R<?php echo $review_data['review_user_before_R']; ?>点</span>
									</div>
							</div>
								<label class=" d-inline">◆学習後のスコア：</label>
									<div class="material-desc-text d-inline">
												<span class="mr-2">L<?php echo $review_data['review_user_after_L']; ?>点</span>
												<span>R<?php echo $review_data['review_user_after_R']; ?>点</span>
									</div>
								<div>
									<label class="">◆詳細：</label>
										<div class="material-desc-text d-inline">
													<p class="pl-3">
														<?php 
															// 文字列の改行コードを<br>に変換
															$review_data['review_comment'] = str_replace(array('\r\n', '\r', '\n'), '<br>', $review_data['review_comment']);
															echo $review_data['review_comment']; 
														?>
													</p>
										</div>
								</div>


<div class="text-right">

										<span style="color: #999; font-size: 12px;" class="pl-2"><i class="far fa-clock mx-1"></i><?php echo $review_data['review_date']; ?><?php if ($review_data['review_update_date'] != NULL ) {echo '（更新：'.$review_data['review_update_date'].'）'; } ?></span>


					<!-- いいねボタン -->
					<?php $review_id = (int)$review_data['review_id']; ?>
								<?php
									// <!-- 本人じゃなければいいねできるようにする -->
									if (isset($user_id)){

											if ($user_id != $review_data['user_id']) { 
													?>
															<section  style="margin: 0;" class="d-inline w-20 post" data-postid="<?php echo $review_id; ?>">
																		<div class="btn-good 
																				<?php 
																					$res_check_already_good = check_already_good($review_id, $user_id, $mysqli);
																							// すでにいいね済ならハートのスタイルを常に赤色にする -->
																							if ($res_check_already_good == true) {
																								echo 'active';} ?>">
																						<i class="fa-heart fa-lg px-16
																							<?php //いいね押したらハートが塗りつぶされる

																									if ($res_check_already_good == true) {
																											echo ' active fas';
																									} else { //いいねを取り消したらハートのスタイルが取り消される
																											echo ' far';
																									}
																				?>">
																			</i><span><?php echo good_number($review_id, $mysqli); ?></span>
																		</div>						
															</section>

																								</div>
		<!-- 本人の場合 -->
		<?php } elseif($user_id == $review_data['user_id']){ ?>
				<i class="fa-heart far fa-lg px-16 color:#505050" style="pointer-events: none;"></i><?php echo good_number($review_id, $mysqli); ?>
<?php		} 
}else {?> 
			<div class="btn-good-non-user">
				<i class="fa-heart far fa-lg px-16"></i><?php echo good_number($review_id, $mysqli); ?>
			</div>

		<?php } ?>
		</div>
		</div>
			</div>
			</div>
			</div>
<?php 

	} ?> 




<?php	}	?>  <!-- // end of foreach -->



</div> <!-- end of wrapper -->

<?php
require_once __DIR__ . '/view/footer.php';