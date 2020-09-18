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
if( empty($user_id) ) {
	// ログインページへリダイレクト
	header("Location: login.php");
	exit;
}

// プロフィール編集処理
	if (!empty($_POST['edit_user_name'])) {
			$edit_user_name = $_POST['edit_user_name'];

			//ユーザー名が英数字かチェック
			if(!ctype_alnum($edit_user_name)) {
			$err_msg_userName = '※ユーザー名は半角英数字を使用してくだい。';
			} else {
				$user_name = $edit_user_name;
				$res_userNameExists = userNameExists($user_name, $mysqli);
				if ($res_userNameExists == true) {
					$err_msg_userName = '※ユーザー名がすでに使用されているため、変更できませんでした。';
					$edit_user_name = $user_name;
					} else {
							$res_edit_profile = edit_profile($edit_user_name, $user_id, $mysqli);
							if ($res_edit_profile == true) {
								$_SESSION['user'] = $edit_user_name;
								header("Location: admin.php");
								exit;
							}
					}
			}
	}



// ユーザーごとのレビュー取り出し

	$user_id = $mysqli->real_escape_string($user_id);
	$query = "SELECT review_material_name, review_material_id FROM reviews WHERE review_user_id = $user_id";
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

// プロフィール画像アップロード処理
	// post_max_size以上がアップされたとき
	// if (empty($_POST) && $_SERVER["REQUEST_METHOD"] === "POST") {
	// 	$msg_err_uploadFile = '※ファイルサイズが大きすぎます。20MB以内のファイルをアップロードしてください。';
	// }

	if(!empty($_FILES['file']['error'])){
			if($_FILES['file']['error'] === 1) {
		$msg_err_uploadFile = '※ファイルサイズが大きすぎます。20MB以内のファイルをアップロードしてください。';
	}} elseif (!empty($_FILES['file']['tmp_name'])) {
		$file = $_FILES['file']['tmp_name'];
		$res_upload_userProfileImg = upload_userProfileImg($file);
		if($res_upload_userProfileImg === "err01"){
			$msg_err_uploadFile = '※jpg/png形式の画像を指定してください。';
		} elseif($res_upload_userProfileImg === "suc") {
			$_msg_suc_uploadFile = '成功';
		}
	}
?>






<div class="wrapper">


<!-- ユーザープロフィール -->

<div class="container-fluid">


		<div class="row">
					<div class="col-md-5 user_profile_info"> <!-- ユーザープロフィールカラム -->
											<!-- プロフィール画像（クリックでモーダル） -->
											<span class="content">
												<a class="js-profile-modal-open" href="" role="button" data-target="modal01"><img src="https://aws-and-infra-wp-toisute.s3-ap-northeast-1.amazonaws.com/user_upload/profile_img/kubo-1598669225.jpg" class="rounded-circle user_profile_img m-2" alt="user_profile_img" width="80px" height="80px" oncontextmenu="return false;"/></a>
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
												<a class="js-profile-modal-open" href="" role="button" data-target="modal02"><u><?php echo $_SESSION['user']; ?></u></a><?php if(!empty($err_msg_userName)) {echo '<span class="text-danger ml-3">'.$err_msg_userName.'</span>';} ?>
											</span>
											</p>	
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
																								<label class="col-lg-12">プロフィール画像を変更</label>
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
																									<button type="submit" class="btn btn-sm btn-warning edit_user_profileImg" id="btn_filename">変更する</button>
																									
																								</div>
																							</div>
																				</form>
																		</div>



																</div><!--modal01__inner-->
														</div><!--modal01-->
														<div id="modal02" class="modal js-profile-modal">
																<div class="modal__bg js-profile-modal-close"></div>
																<div class="modal__content">
																	<form action="" method="post">
																		<div class="form-group">
																			<input type="text" class="form-control mb-2" name="edit_user_name" required value="<?php echo $_SESSION['user']; ?>">
																		</div>	
																			<a class="btn btn-secondary btn-sm" href="admin.php" role="button">キャンセル</a>
																			<button type="submit" class="btn btn-warning btn-sm edit_user_name">プロフィール編集</button>
																		
																	</form>
																</div><!--modal02__inner-->
														</div><!--modal02-->
											</class> <!-- modal content -->
									<div class="col-12">
										<!-- 投稿数 -->
										<div class="d-inline-block mr-3">
												<div>
													<?php 
													$res_fetch_num_postPerUser = fetch_num_postPerUser($user_id, $mysqli );
													$num_postPerUser = $res_fetch_num_postPerUser;
													echo $num_postPerUser;
													?>
												</div>
												<div>
													投稿
												</div>
										</div>
										<!-- フォロワー数 -->
										<div class="d-inline-block ml-3">
												<div>
														<?php 
															$res_fetch_num_follower = fetch_num_followerPerUser($user_id, $mysqli);
															$num_followerPerUser = $res_fetch_num_follower;
															echo $num_followerPerUser;
															?>
												</div>
												<div>
													ふぉろわー
												</div>
										</div>
									</div>
									<div class="col-12">
										<a href="account_setting.php" class="btn btn-secondary btn-sm" role="button">プロフィールを編集する</a>
									</div>
									<div class="col-12">
										<a href="account_pwChange.php" class="btn btn-warning btn-sm" role="button">パスワードを変更</a>
									</div>
									<div class="col-12">
										<a href="account_delete.php" class="btn btn-danger btn-sm" role="button">アカウントを削除</a>
									</div>
							</div> <!-- end of ユーザープロフィールカラム -->

							<div class="col-md-7">
								<div class="container-fluid"> <!-- 右カラム -->
											<div class="row">
												<div class="col-12 user_data_analysis"> <!-- ユーザー分析 -->
													<div><i class="fas fa-chart-bar mr-2"></i>ユーザー分析領域</div>
													
												</div>
												<div class="col-12 user_data_post"> <!-- 投稿一覧 -->
													<div><i class="fas fa-pencil-alt mr-2"></i>あなたのレビュー</div>
															<?php 
																if (isset($no_post)) {
																echo $no_post;
																} else {

																		foreach ( $user_posts_data as $user_post_data ) {
																		?>

																	<div class="col-xs-12">
																		<h2><a style="color: #505050;" href="detail.php?id=<?php echo $user_post_data['review_material_id']?>"><?php echo $user_post_data['review_material_name']; ?></a></h2>


																		<p><a href="review_edit.php?id=<?php echo $user_post_data['review_material_id']; ?>">&raquo; 編集する</a>　　　<a href="review_delete.php?id=<?php echo $user_post_data['review_material_id']; ?>">&raquo; 削除する</a></p>
																		<br><br>
																	</div>

																		<?php 
																		}
																				
																}
															?>
												</div>
											</div>
								</div> <!-- end of 右カラム -->
							</div>
					</div> <!-- end of row -->







</div> 
<!-- end of container -->




</div> <!-- end of wrapper -->
<?php
require_once __DIR__ . '/view/footer.php';