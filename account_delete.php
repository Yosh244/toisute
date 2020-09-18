<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/user.php';
?>

<?php

// 管理者としてログインしているか確認
if( empty($_SESSION['user_id']) ) {
	// ログインページへリダイレクト
	header("Location: login.php");
	exit;
}

// テストユーザー制限
if($_SESSION['user_id'] == "33" || $_SESSION['user'] === "test3") {
	header("Location: index.php");
	exit;
}


$account_delete_flg = 0;

// 「確認」ボタン押下後の処理
if( !empty($_POST['account_delete_confirm']) ) {
		// パスワード認証
		if (!empty($_SESSION['user_id']) && !empty($_POST['user_password_forDLT'])) {
					$user_id = $_SESSION['user_id'];
					$user_password_forDLT = $_POST['user_password_forDLT'];
					$res_check_pwForDLT = check_pwForDLT($user_id, $user_password_forDLT, $mysqli);
					if($res_check_pwForDLT == true) { // 一致している場合
						$account_delete_flg = 1;
					} elseif($res_check_pwForDLT == false) { // 不一致の場合
						$msg_pw_err = '※パスワードが登録情報と一致しません。';
					} 
		} else { // PWが空の場合
			$msg_pw_null = '※ログインパスワードを入力してください。' ;
		} 

// 「削除」ボタン押下後の処理
} elseif( !empty($_POST['account_delete_submit']) ) {

		$account_delete_flg = 2;
	
}
 
/* 退会処理 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  /* ログイン状態で、かつ退会ボタンを押した */
  if (isset($_SESSION['user_id']) && isset($_POST['account_delete_submit']) && $account_delete_flg == 2) {

	/* 退会 */
		//DB更新
		$sql = "UPDATE users SET 
		delete_flg = 1 
		WHERE user_id = ?";
		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("i", $_SESSION['user_id']);
		$res = $stmt->execute();
		$stmt->close();

				// 更新に成功したら一覧に戻る
					if( $res ) {
						session_destroy(); // セッションを破壊
 						header('Location: index.php');
						exit;
					} else {
					$err_msg_delete = 'エラーが発生しました。<a href="index.php">TOPへ戻る</a>';
					echo $err_msg_delete;
					}
	}

  }
 

?>

<!-- HTML -->

<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-9 user_data_accountDelete">
							<div class="card" style="min-height: 75vh;">
								<div class="card-header">アカウント削除</div> 
									<div class="card-body  pb-5" style="
background-color: #FFFBF7;">



	<?php if($account_delete_flg == 0) { ?>
		<!-- 1. パスワード入力ページ -->
						<div class="card mb-3" style="background-color: #FFEDDD">
							<div class="card-body">
									<p>	※ユーザーアカウントを削除しても、以下の内容はデータ上に残ります。</p>
										<ul>
											<li>あなたがレビューした投稿</li>
											<!-- <li>あなたが誰かのレビューに対して行ったコメント</li> -->
											<li>あなたが押した「いいね」の記録</li>
										</ul>
									<p>上記データを削除する場合は、<span class="font-weight-bold">ユーザーアカウントを削除する前に、手動で削除などを行ってください</span>。</p>
							</div>
						</div>

						<p>アカウントを削除するには、ログインパスワードを入力してください。</p>

					<form action="" method="post">
							<div class="form-group">
								<label for="user_password">ログインパスワード</label><?php if(!empty(	$msg_pw_null)) {echo '<span class="pl-2" style="color:red;">'.$msg_pw_null.'</span>' ;} else if (!empty($msg_pw_err)){echo '<span class="pl-2" style="color:red;">'.$msg_pw_err.'</span>' ;} ?>
								<input type="password" class="form-control" id="user_password" name="user_password_forDLT" style="width:240px;">
							</div>

													<a href="user_setting.php" role="button" class="btn btn-secondary mr-3">戻る</a>

													<input type="submit" name="account_delete_confirm" class="btn btn-danger" value="確認" />

						</form>

	<?php }	?>


<!-- 2. 確認ページ -->
	<?php if($account_delete_flg == 1) { ?>

		
				<p>アカウントを削除しますか？</p>
								<a href="user_setting.php" class="btn btn-secondary mr-4" role="button">キャンセル</a>

								<form action="" method="POST" style="display: inline;">	
														<input type="submit" name="account_delete_submit" class="btn btn-danger" value="削除" />
	
								</form>
		
	<?php } ?>


</div>
</div>
</div>
</div>
</div>

<?php
require_once __DIR__ . '/view/footer.php';