<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/review.php';
require_once __DIR__ . '/../app/functions/product.php';
require_once __DIR__ . '/../app/functions/user.php';
require_once __DIR__ . '/../app/functions/s3.php';

?>

<?php


// 管理者としてログインしているか確認
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user'];
if( empty($user_id) ) {
	// ログインページへリダイレクト
	header("Location: login.php");
	exit;
}



	// プロフィール画像アップロード処理
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

// アイコン：変更ボタン押下後の処理
if (!empty($_POST['imgChangeToDefault'])) {
	change_userProfileImgToDefault($user_id, $mysqli);
}

// プロフィール文：保存ボタン押下後の処理
if (isset($_POST['edit_user_introduction'])) {
	// プロフィール文の文字数チェック
	$_POST['edit_user_introduction'] = str_replace(array("\r\n", "\r", "\n"), "", $_POST['edit_user_introduction']);
	if (mb_strwidth($_POST['edit_user_introduction']) > 480) {
		$msg_err_text = '※制限文字数を超えているため、保存できませんでした。';
	} else {
		$edit_user_introduction = $_POST['edit_user_introduction'];
		$res_save_data_userProfile = save_data_userProfile($edit_user_introduction, $user_id, $mysqli);
		if($res_save_data_userProfile) {
			$msg_success_update = "保存しました。";

		}
	}
}

// ユーザーデータ取得
$res_fetch_data_userProfile = fetch_data_userProfile($user_id, $mysqli);
$users_data = $res_fetch_data_userProfile;


?>
<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-9 user_data_setting">
							<div class="card">
								<div class="card-header">設定</div> 
									<div class="card-body  pb-5" style="
background-color: #FFFBF7;">

	<!-- アイコン -->
	<div>
		<label><span class="font-weight-bold">アイコン</span></label>
				<!-- 画像アップロードエラーメッセージ -->
				<span style="font-size: 12px; color: red;">
					<?php
					if(isset($msg_err_uploadFile)) {echo $msg_err_uploadFile;} 
						if(isset($_msg_suc_uploadFile)) {echo $_msg_suc_uploadFile;}
								if(isset($msg_err_postMaxSize)) {echo $msg_err_postMaxSize;}
						?>
				</span>
		
	</div>
			<span class="p-3" style="display: inline-block; vertical-align: top;"> 
				<form action="" method="post">
 
								<!-- 1.デフォルト -->
									<span class="custom-radio ">
										<input type="radio" id="imgChange" name="imgChangeToDefault" class="custom-control-input" value="post" <?php foreach ($users_data as $user_data);
																			// デフォルト画像が設定されていれば、チェックを初期値として入れておく
																			if ($user_data['user_img'] === "0"){
																			echo 'checked';
																			}
																?>>
										<label class="custom-control-label ml-5" for="imgChange">
											<img src="<?php echo fetch_defaultUserImg() ; ?>" class="rounded-circle user_profile_img" alt="user_profile_defaultImg" width="60px" height="60px" oncontextmenu="return false;"/>
											<div class="text-center"><span class="font-weight-bold" style="font-size: 10px;">デフォルト</span></div>
											<div class="text-center"><button type="submit" class="btn btn-sm btn-secondary">変更</button></div>
											</label>
											</span>
										</form>
			</span>

			
										<!-- 2. カスタム画像（クリックでモーダル） -->
	
				<span style="display: inline-block;" class="profile_img_setting p-3"> 
												

															<span class="content">
																						<a class="js-profile-modal-open" href="" role="button" data-target="modal01" style="text-align: center;"><img src="<?php 
																								foreach ($users_data as $user_data);
																								// デフォルトが指定されている場合（初期設定）
																								if ($user_data['user_img'] === "0") {
																									echo 'https://aws-and-infra-wp-toisute.s3-ap-northeast-1.amazonaws.com/self-upload/website/profile_img_default/profile_img_change_01.jpeg" class="';
																								} else {
																								// カスタム画像が指定されている場合
																									echo $user_data['user_img'].'" class="rounded-circle ';} ?>user_profile_img" alt="user_profile_customImg" width="60px" height="60px" style="text-align: center;" oncontextmenu="return false;"/></a>
															</span>

																					
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
																																		<div class="col-lg-10">
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
																																		<div class="col-lg-offset-2 col-lg-10">
																																			<button type="submit" class="btn btn-sm btn-warning edit_user_profileImg" id="btn_filename">変更</button>
																																		</div>
																																	</div>
																														</form>
																												</div>

																										</div><!--modal01__inner-->
																								</div><!--modal01-->
																			
																					</class> <!-- modal content -->

																					<div class="text-center"><span class="font-weight-bold custom-img-text" style="font-size: 10px;">画像<br>アップロード</span></div>

						</span>				
			
		

				<!-- プロフィール文 -->
				<form action="" method="post" class="pt-3">
					<label for="profile-introduction"><span class="font-weight-bold">プロフィール文</span>（全角240文字以内）<?php 
							if(!empty($msg_err_text)) {
									echo '<span class="text-danger">'.$msg_err_text.'</span>';
							} elseif (!empty($res_save_data_userProfile)) {
									echo '<span class="text-success">'.$msg_success_update.'</span>';
							}	?></label>

						<div class="col-md-9">
								<textarea name="edit_user_introduction" rows="6" class="form-control profile-introduction" maxlength='240'><?php 
								// エラー時にプロフィール文下書きを再表示する
								if(!empty($_POST['edit_user_introduction'])) {
									echo $_POST['edit_user_introduction'];
								} else {
								// 初回表示はDBのプロフィール文を表示する
								foreach ($users_data as $user_data);
								echo str_replace(array('\r\n', '\r', '\n'), "", $user_data['user_introduction']);
								} ?></textarea>
								<div>
									<button type="submit" class="btn btn-secondary btn-sm edit_profile my-2">保存</button>
								</div>
						</div>
			</form>

			<div  class="border-top border-secondary pt-5 mt-5">
				<label><i class="fas fa-key pr-1"></i><span class="font-weight-bold">パスワード変更</span></label>
			</div>
					<p>定期的にパスワードを変更することをおすすめします。</p>
					<a href="account_pwChange.php" class="btn btn-outline-secondary btn-sm<?php if($_SESSION['user_id'] == "33") {echo ' disabled';} ?>" role="button">パスワードを変更する</a>

			<div class="border-top border-secondary pt-5 mt-5">
				<label><i class="fas fa-trash-alt pr-1"></i><span class="font-weight-bold">アカウント削除</span></label>
			</div>
					<p>一度アカウントを削除すると復元できませんので、ご注意ください。</p>
					<a href="account_delete.php" class="btn btn-danger btn-sm<?php if($_SESSION['user_id'] == "33") {echo ' disabled';} ?>" role="button">アカウントを削除する</a>
				




	</div> <!-- end of card-body  -->
	</div> <!-- end of card  -->
	</div> <!-- end of col  -->
	</div> <!-- end of row  -->

</div> <!-- end of container -->






<?php
require_once __DIR__ . '/view/footer.php';