<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/user.php';
?>

<?php

	// 必須項目に情報が入っているかを確認する
	if (
		!empty( $_POST['user_email']) &&
		!empty( $_POST['user_password'])
	 ) {

		//  エラーがない場合
		$user_email = $_POST['user_email'];
		$user_password = $_POST['user_password'];

		// ログインする
		$res_login_user = login_user($user_email, $user_password, $mysqli);
	} 

	// ログイン状態ならトップへ
	if(!empty($_SESSION['user_id'])) {
		header("Location: index.php");
		exit;
	}
 ?>



 
<div class="wrapper">

<div class="container-fluid">
	<div class="row justify-content-center">

 <div class="col-sm-6">
	 <label style="font-size: 1.5rem;">ログイン</label><?php if (isset($res_login_user)) { ?>
		  <span style="color:red;"><?php echo $res_login_user; } ?></span>
	<form action="" method="post">
		<div class="form-group">
			<label for="user_email">Email</label>
			<input type="email" class="form-control" id="user_email" name="user_email">
		</div>
		<div class="form-group">
			<label for="user_password">パスワード</label>
			<input type="password" class="form-control" id="user_password" name="user_password">
		</div>
		<button type="submit" class="btn btn-warning">ログイン</button>　　<a href="signup.php">ユーザー登録する</a>
	</form>
 </div>


	</div>
</div>

</div>

<?php
require_once __DIR__ . '/view/footer.php';
