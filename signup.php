<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/user.php';
?>

<?php
//  送信ボタンが押された時に下記を実行
if ( $_POST ) {
	
	// ①必須項目に情報が入っているかを確認する
	if (
		!empty( $_POST['user_name']) &&
		!empty( $_POST['user_email']) &&
		!empty( $_POST['user_password']) &&
		!empty( $_POST['user_pass_check'])
		) {
		$user_name = $_POST['user_name'];
		$user_email = $_POST['user_email'];
		$user_password = $_POST['user_password'];
	}

	//ユーザー名が英数字かチェック
	if(!ctype_alnum($user_name)) {
		$err_msg_userName = '※ユーザー名は半角英数字を使用してくだい。';
	} else {

	// ②ユーザーID重複チェック
	$res_userNameExists = userNameExists($user_name, $mysqli);
	if ($res_userNameExists == true) {
		$err_msg_userName = '※ユーザー名がすでに使用されています。';
		
		} else {
		// ③メールアドレス重複チェック
		$email_check_res = emailExists($user_email, $mysqli);
				// もし重複なしなら
				if ($email_check_res == false) {
					// ④パスワード制約チェック
					$res_check_pw_rule = check_pw_rule($user_password, $mysqli);
					if($res_check_pw_rule == true) {
						$pw_err_msg = '※パスワードは8文字以上で指定してください';
					} else {
						// パスワードが8文字以上なら
							// ⑤2回入力したパスワードがマッチしているかを確認する
							if ( $_POST['user_password'] === $_POST['user_pass_check']) {
								// ユーザー登録処理をする
								$res_save_user = save_user($user_name, $user_email, $user_password, $mysqli);
							} else {
									$err_message = '※パスワードが一致しません';
							}
					}
				}
		}  
		
	}

}




?>

<div class="wrapper">
	
	<div class="container-fluid">

		<div class="row justify-content-center">
			<div class="col-sm-6">
					<label style="font-size: 1.5rem;">ユーザー登録</label><?php if (isset($res_save_user)) {echo '<span class="pl-2" style="color: red; vertical-align: baseline;">'.$res_save_user.'</span>';}?>

			<form action="" method="post">

							<div class="form-group">
								<label for="user_name">ユーザー名<span class="text-muted">（半角英数字）</span></label>
												<!-- ユーザーIDが重複していればメッセージを表示 -->
												<span class="pl-2" style="color: red;">
													<?php if (($_POST) && !empty($err_msg_userName)) {
																echo $err_msg_userName ; } ?>
												</span>
								<input type="text" class="form-control" name="user_name" value="<?php if( !empty($_POST['user_name']) ){ echo $_POST['user_name']; } ?>" required >
							</div>

							<div class="form-group">
								<label for="user_email">メールアドレス</label>
												<!-- メールアドレスが重複していればメッセージを表示 -->
												<span class="pl-2" style="color: red;">
													<?php if (($_POST) && !empty($email_check_res)) {
																echo '※メールアドレスはすでに登録されています' ; } ?>
												</span>
								<input type="email" class="form-control" name="user_email" value="<?php if( !empty($_POST['user_email']) ){ echo $_POST['user_email']; } ?>" required>
							</div>

							<div class="form-group">
								<label for="user_password">パスワード<span class="text-muted">（半角英数字8～32文字）</span></label>
											<!-- PWが8字以下 or 確認用と一致していなければ、メッセージを表示 -->
											<span class="pl-2" style="color: red;">
												<?php if (($_POST) && isset($pw_err_msg)) {
															echo $pw_err_msg; } ?>
												<?php if (($_POST) && isset($err_message)) {
															echo $err_message; } ?>
											</span>
								<input type="password" class="form-control" name="user_password" minlength="8" maxlength="32" value="<?php if( !empty($_POST['user_password']) ){ echo $_POST['user_password']; } ?>" required>
							</div>

							<div class="form-group">
								<label for="user_pass_check">パスワード（確認用）</label>
								<input type="password" class="form-control" name="user_pass_check" minlength="8" maxlength="32" required>
							</div>
									<button type="submit" class="btn btn-warning">登録する</button>


				</form>
			</div>
		</div>
	</div>

</div>



<?php
require_once __DIR__ . '/view/footer.php';