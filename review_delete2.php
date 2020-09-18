<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/user.php';
require_once __DIR__ . '/../app/functions/review.php';
?>

<?php
	$review_material_id = $_GET['id'];


// 管理者としてログインしているか確認
$user_id = $_SESSION['user_id'];
if( empty($user_id) ) {
	// ログインページへリダイレクト
	header("Location: login.php");
}

//DB編集

if (!empty($_POST['delete_submit'])) {
	$user_id = (int)$mysqli->real_escape_string($user_id);
	$review_material_id = (int)$mysqli->real_escape_string($review_material_id);

//DB更新（削除）
$sql = "DELETE FROM reviews WHERE review_user_id = ? AND review_material_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $user_id, $review_material_id);
$res = $stmt->execute();
if($res) {
	// 削除に成功したら一覧に戻る
	$msg = '投稿を削除しました。<br><a href="user_profile.php?id='.$user_id.'">マイページに戻る</a>';
	$mysqli->close();
}

 else {
$msg = 'エラーが発生しました。\n<a href="user_profile.php?id='.$user_id.'">マイページに戻る</a>';
$mysqli->close();
}
}




?>

<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-8 review_delete">
							<div class="card" style="min-height: 65vh;">
								<div class="card-header">レビュー削除</div> 
									<div class="card-body  pb-5" style="
background-color: #FFFBF7;">

<p><?php if(!empty($msg)) {echo $msg;} ?></p>

</div>
</div>
</div>
</div>
</div>


<?php
require_once __DIR__ . '/view/footer.php';
