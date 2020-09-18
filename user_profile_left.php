<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/review.php';
require_once __DIR__ . '/../app/functions/product.php';
require_once __DIR__ . '/../app/functions/user.php';
require_once __DIR__ . '/../app/functions/s3.php';
require_once __DIR__ . '/../app/functions/webpage.php';

?>


<?php

$profile_user_id = (int)$_GET['id'];

// ユーザーページが存在しているかチェック
$res_check_userProfilePageExists = check_userProfilePageExists($profile_user_id, $mysqli);
	if( $res_check_userProfilePageExists == false) {
		header("Location: index.php");
		exit;
	}


// マイページか他ユーザーのページかチェック
	if (empty($_SESSION['user_id'])) {
	// フラグ0：未ログイン
	$flg_profilePage = 0; 
	} else {
		$user_id = (int)$_SESSION['user_id'];
		if( $profile_user_id !== $user_id ) {
			// フラグ1：他ユーザーページ
			$flg_profilePage = 1; 
		} elseif($profile_user_id === $user_id) {
			// フラグ2：マイページ
			$flg_profilePage = 2; 
		}
	}

// フラグ0-2：他ユーザー情報取得
$res_data_fetch_profileUser = fetch_data_profileUser($profile_user_id, $mysqli) ;
$profile_users_data = $res_data_fetch_profileUser;
foreach ($profile_users_data as $profile_user_data);

// フラグ0-2；プロフィール情報取得
$res_fetch_data_profileUserIntroduction = fetch_data_profileUserIntroduction($profile_user_id, $mysqli);
if($res_fetch_data_profileUserIntroduction) {
	foreach ($res_fetch_data_profileUserIntroduction as $user_data);
}



// フラグ2：プロフィール編集処理
	if (!empty($_POST['edit_user_name'])) {
			$edit_user_name = $_POST['edit_user_name'];

			//ユーザー名が英数字かチェック
			if(!ctype_alnum($edit_user_name)) {
			$err_msg_userName = '※ユーザー名は半角英数字を使用してください。';
			} else {
				$user_name = $edit_user_name;
				$res_userNameExists = userNameExists($user_name, $mysqli);
				if ($res_userNameExists === true) {
					$err_msg_userName = '※ユーザー名がすでに使用されているため、変更できませんでした。';
					$edit_user_name = $user_name;
					} else {
							$res_edit_profile = edit_profile($edit_user_name, $user_id, $mysqli);
							if ($res_edit_profile === true) {
								$_SESSION['user'] = $edit_user_name;
								$url = "user_profile.php?id=".$user_id;
								header("Location: $url");
								exit;
							}
					}
			}
	}



// フラグ0-2：ユーザーごとのレビュー取り出し

$profile_user_id = $mysqli->real_escape_string($profile_user_id);
	$query = "SELECT * FROM reviews WHERE review_user_id = $profile_user_id";
	$result = $mysqli->query($query);
	if( !$result ) {
		// エラーが発生した場合
		echo 'エラーが発生しました。<a href="index.php">トップ画面へ戻る</a>';
		exit;
	} else {
		// カテゴリーが存在しない場合
		if( mysqli_num_rows($result) == 0 ){
			$no_post = 'まだレビューがありません';

		} else {
			// エラーがない場合
			// 連想配列にデータを格納する
			$user_posts_data = array(); 
			while ($row = $result->fetch_assoc()) {
				$user_posts_data[] = $row;
			}
			// return $user_posts_data;
		}
	}

	// フラグ2：プロフィール画像アップロード処理
	if(!empty($_FILES['file']['error'])){
		// サーバーアップでエラーがある場合
			if($_FILES['file']['error'] === 1) {
			$msg_err_uploadFile = '※ファイルサイズが大きすぎます。20MB以内のファイルをアップロードしてください。';
			}
		// サーバーにアップができた場合
	} elseif (!empty($_FILES['file']['tmp_name'])) {
						$file = $_FILES['file']['tmp_name'];
					// s3へのアップ処理
						$res_upload_userProfileImg = upload_userProfileImg($mysqli, $file);
						if($res_upload_userProfileImg === "err01"){
							$msg_err_uploadFile = '※jpg/png形式の画像を指定してください。';
						} elseif($res_upload_userProfileImg === "suc") {
							$url = "user_profile.php?id=".$user_id;
							header("Location: $url");
							exit;
						}
	}
?>






<div class="wrapper user_info">


<!-- ユーザープロフィール -->

<div class="container-fluid">


		<div class="row">
					<div class="col-md-4 mb-4 user_profile_info"> <!-- ユーザープロフィールカラム -->
											<!-- プロフィールアイコン（クリックでモーダル） -->
											<span class="content">
												<!-- 未設定ならデフォルトアイコン -->
												<a class="js-profile-modal-open" href="" role="button" data-target="modal01"><img src="<?php
														if ($profile_user_data['user_img'] === '0') {
															echo fetch_defaultUserImg();
														} else {
												// カスタムアイコンを表示
															echo $profile_user_data['user_img'];
														}
												?>" class="rounded-circle user_profile_img m-2" alt="user_profile_img" width="80px" height="80px" oncontextmenu="return false;"/></a>
											</span>
											<div>
													<span style="font-size: 12px; color: red;">
													<!-- 画像アップロードエラーメッセージ -->
													<?php
													if(isset($msg_err_uploadFile)) {echo $msg_err_uploadFile;} 
													if(isset($_msg_suc_uploadFile)) {echo $_msg_suc_uploadFile;}
													if(isset($msg_err_postMaxSize)) {echo $msg_err_postMaxSize;}
													?>
													</span>
											</div>
											
											<p>
											<!-- ユーザー名（クリックでモーダル） -->
											<span class="content">
												<a class="js-profile-modal-open" href="" role="button" data-target="modal02"><u>@<?php echo $profile_user_data['user_name']; ?></u></a><?php if(!empty($err_msg_userName)) {echo '<div class="text-danger ml-3">'.$err_msg_userName.'</div>';} ?>
											</span>
											</p>	
	<?php if($flg_profilePage === 2) { ?>
													<!-- モーダルウィンドウ内容 -->
													<div id="modal01" class="modal js-profile-modal">
															<div class="modal__bg js-profile-modal-close"></div>

																<div class="modal__content">

																		<div class="justify-content-end mr-2">
																		<button type="button" class="close js-profile-modal-close" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																		</button>
																		</div>
																		<div>
																				<form enctype="multipart/form-data" action="" method="POST">
																							<div class="form-group form-group-sm">
																								<label class="col-lg-12">プロフィール画像</label>
																								<div class="col-lg-12 w-75 m-auto">
																									<input type="file" id="file_select" name="file" accept="image/png, image/jpg" class="form-control" style="display:none;">

																									<div class="input-group">
																										<!-- カメラアイコン -->
																										<span class="input-group-btn">
																											<button type="button" id="file_select_icon" class="btn btn-file-upload" style="border-radius: 0;"><i class="fas fa-camera" aria-hidden="true"></i></button>
																										</span>
																										<!-- テキストエリア（ファイル名） -->
																										<input type="text" id="file_name" class="form-control file-select" placeholder="画像を選択" readonly>
																												<script>$('.file-select').on('click', function() {
																												$('#file_select').click();
																												});</script>
																									</div>
																								</div>
																							</div>
																							
																							<!-- 送信ボタン -->
																							<div class="form-group form-group-sm">
																								<div class="col-lg-12">
																									<button type="submit" class="btn btn-sm btn-warning edit_user_profileImg" id="btn_filename">変更</button>
																									
																								</div>
																							</div>
																				</form>
																		</div>



																</div><!--modal01__inner-->
														</div><!--modal01-->
														<div id="modal02" class="modal js-profile-modal">
																<div class="modal__bg js-profile-modal-close"></div>
																<div class="modal__content">
																		<div class="justify-content-end mr-2">
																				<button type="button" class="close js-profile-modal-close" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																				</button>
																		</div>
																	<form action="" method="post" class="m-3">

																		<div class="form-group">
																			<label>ユーザー名</label>
																			<input type="text" class="form-control  w-75 m-auto" name="edit_user_name" required value="<?php echo $_SESSION['user']; ?>">
																		</div>	
																			<button type="submit" class="btn btn-warning btn-sm edit_user_name">変更</button>
																		
																	</form>
																</div><!--modal02__inner-->
														</div><!--modal02-->
											</class> <!-- modal content -->
	<?php } ?>
					<!-- プロフィール文 -->
					<div class="row justify-content-center">
							<div class="col-9 text-left">
								<p>
									<span style="font-size: 0.8rem;"><?php 
											$text_user_profile = str_replace(array('\r\n', '\r', '\n'), "", $user_data['user_introduction']);
											echo $text_user_profile; ?></span>
								</p>
							</div>
					</div>



					<div class="col-12">
										<!-- 投稿数 -->
										<div class="d-inline-block ml-2 mr-1">
												<div>
													<A HREF="user_profile.php?id=<?php 
																		echo $profile_user_id;?>">
													<?php 
													$res_fetch_num_postPerUser = fetch_num_postPerUser($profile_user_id, $mysqli );
													$num_postPerUser = $res_fetch_num_postPerUser;
													echo $num_postPerUser;
													?>
													</a>
												</div>
											<div>
										<span style="font-size: 0.8rem;">投稿</span>
												</div>
										</div>


										<!-- フォロー数 -->
										<div class="d-inline-block ml-4">
												<div id="num-follows">
																<!-- フォロー表示ページに移動 -->
																		<A HREF="user_profile_follow.php?id=<?php 
																		echo $profile_user_id;?>"><?php echo follow_number2($profile_user_id, $mysqli); ?></A>
												</div>
												<div>
													<span style="font-size: 0.8rem;">フォロー</span>
												</div>
										</div>


										<!-- フォロワー数 -->
										<div class="d-inline-block ml-1">
												<div id="num-followers">
																<!-- フォロワー表示ページに移動 -->
																		<A HREF="user_profile_follower.php?id=<?php 
																		echo $profile_user_id;?>"><?php echo follow_number($profile_user_id, $mysqli); ?></A>

												</div>
												<div>
													<span style="font-size: 0.8rem;">フォロワー</span>
												</div>
										</div>
						</div>

									
	<?php if($flg_profilePage != 2) {?>


		<!-- // フラグ0-1：フォローボタン表示 -->

		<section class="post" data-followid="<?php echo $profile_user_id; ?>">

			<div class="p-3">
				
				<?php 
						// <!-- 未ログインなら押せない -->
						if($flg_profilePage === 1) {
							$res_check_already_follow = check_already_follow($profile_user_id, $user_id, $mysqli);
						}?>
						<button <?php if($flg_profilePage === 0) { echo 'disabled ' ;} ?>role="button" class="btn btn-follow btn-outline-primary btn-sm px-5 <?php
										// フォロー済みならボタンをアクティブにする
										if(isset($res_check_already_follow)){
											if ($res_check_already_follow == true) { echo 'active';
											}
										};?>">
										<span style="font-size: 0.8rem;" class="<?php if(isset($res_check_already_follow)){if ($res_check_already_follow == true) { echo 'follow' ;}} ?>"><?php 
										if ($flg_profilePage === 0) { echo 'フォローする';
										} elseif(isset($res_check_already_follow)){
											if ($res_check_already_follow == true) { echo 'フォロー中' ;}
											else {echo 'フォローする' ; }
										}  ?></span>
								</button>
										
												
							</div>						
						</section>
													
					<?php } ?>

									<!-- フラグ2：プロフィール編集表示 -->
									<?php if($flg_profilePage === 2) {?>
																<div class="col-12 my-3">
																	<a href="user_setting.php" class="btn btn-secondary btn-sm px-4" role="button"><span style="font-size: 0.8rem;">プロフィールを編集する</span></a>
																</div>

									<?php }; ?>

				</div> <!-- end of ユーザープロフィールカラム -->



		<!-- 右カラム -->
		<div class="col-md-8 user_sub_info mb-4"> 

