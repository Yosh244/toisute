<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/user.php';
?>

<?php

// 管理者としてログインしているか確認
if( empty($_SESSION['user_id']) ) {
	header("Location: login.php");
	exit;
}

// テストユーザー制限
if($_SESSION['user_id'] == "33" || $_SESSION['user'] === "test3") {
	header("Location: index.php");
	exit;
}



$account_pwChange_flg = 0;

// 「変更」ボタン押下後の処理
if( !empty($_POST['account_pwChange_submit']) ) {
		// パスワード認証
		if (!empty($_SESSION['user_id']) && !empty($_POST['user_current_password']) && !empty($_POST['user_new_password']) && !empty($_POST['user_new_password_check'])) {
					$user_id = $_SESSION['user_id'];
					$user_current_password = $_POST['user_current_password'];
					$user_new_password = $_POST['user_new_password'];
					$user_new_password_check = $_POST['user_new_password_check'];

					$res_check_pwForChange = check_pwForChange($user_id, $user_current_password, $mysqli);
					if($res_check_pwForChange == false) { // 不一致の場合
						$msg_pw_err = '※パスワードが登録情報と一致しません。';
					} elseif($res_check_pwForChange == true) { // 一致の場合
						// 新しいパスワードの一致チェック
						if($user_new_password != $user_new_password_check) {
							$msg_NewPw_err = '※新しいパスワードが一致しません。';
						} elseif ($user_new_password == $user_new_password_check){
							$account_pwChange_flg = 1;
						}
					} 
		} 
} 
 
  /* 変更フラグが立ったら、変更処理 */
  if (isset($_SESSION['user_id']) && isset($_POST['account_pwChange_submit']) && $account_pwChange_flg == 1) {
		$user_new_password = password_hash($user_new_password, PASSWORD_DEFAULT);

		$sql = "UPDATE users SET 
		user_password = ?
		WHERE user_id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("si", $user_new_password, $_SESSION['user_id']);
		$res = $stmt->execute();
		$stmt->close();

				// 更新に成功したら一覧に戻る
					if( $res ) {
						$msg_pwChange_success = 'パスワードを変更しました';
						unset($_POST['user_new_password']);

					} else {
					$err_msg_delete = 'エラーが発生しました。';
					}
	}

 

?>

<!-- HTML -->
<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-9 user_data_pwChange">
							<div class="card">
								<div class="card-header">パスワード変更</div> 
									<div class="card-body  pb-5" style="
background-color: #FFFBF7;">


		<label><?php if (!empty($msg_pwChange_success)){echo '<span class="pl-2 text-success">'.$msg_pwChange_success.'</span>' ;} elseif (!empty($err_msg_delete)){echo '<span class="pl-2 text-danger">'.$err_msg_delete.'</span>' ;}?></label>


		<!-- 1. パスワード入力ページ -->

					<form action="" method="post">
							<div class="form-group">
								<label for="user_password">現在のパスワード</label><?php if (!empty($msg_pw_err)){echo '<span class="pl-2" style="color:red;">'.$msg_pw_err.'</span>' ;} ?>
								<input type="password" class="form-control" id="user_password" name="user_current_password" required>
							</div>
							<div class="form-group">
								<label for="user_password">新しいパスワード<span class="text-muted">（半角英数字8～32文字）</span></label><?php if (!empty($msg_NewPw_err)){echo '<p><span class="pl-2" style="color:red;">'.$msg_NewPw_err.'</span></p>' ;} ?>
								<input type="password" class="form-control" id="user_password" name="user_new_password" minlength="8" maxlength="32" value="<?php if( !empty($_POST['user_new_password']) ){ echo $_POST['user_new_password']; } ?>" required>
								
							</div>
							<div class="form-group">
								<label for="user_password">新しいパスワード（確認用）</label>
								<input type="password" class="form-control" id="user_password" name="user_new_password_check" minlength="8" maxlength="32" required>
							</div>

							<div class="form-group">

											<a href="user_setting.php" role="button" class="btn btn-secondary mr-2">キャンセル</a>

											<input type="submit" name="account_pwChange_submit" class="btn btn-warning" value="変更" />

							</div>
				</form>

		</div>
		</div>
		</div>
		</div>
		</div>


<?php
require_once __DIR__ . '/view/footer.php';