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
//全て記入されているか確認
if (!empty($_POST['add_review']) && !empty($_POST['add_study_period']) && !empty($_POST['add_before_L']) && !empty($_POST['add_before_R']) && !empty($_POST['add_after_L']) && !empty($_POST['add_after_R']) && !empty($_POST['aaaaa'])){
	$add_study_period = $_POST['add_study_period'];
	$add_review_user_before_L = $_POST['add_before_L'];
	$add_review_user_before_R = $_POST['add_before_R'];
	$add_review_user_after_L = $_POST['add_after_L'];
	$add_review_user_after_R = $_POST['add_after_R'];
	$add_review = $_POST['add_review'];
	$aaaaa = $_POST['aaaaa'];

	$add_study_period = $mysqli->real_escape_string($add_study_period);
	$add_review_user_before_L = $mysqli->real_escape_string($add_review_user_before_L);
	$add_review_user_before_R = $mysqli->real_escape_string($add_review_user_before_R);
	$add_review_user_after_L = $mysqli->real_escape_string($add_review_user_after_L);
	$add_review_user_after_R = $mysqli->real_escape_string($add_review_user_after_R);
	$add_review = $mysqli->real_escape_string($add_review);
	$aaaaa = $mysqli->real_escape_string($aaaaa);

//DB更新
$sql = "UPDATE reviews SET 
review_user_study_period = ?, 
review_update_date = NOW(),
review_user_before_L = ?, 
review_user_before_R = ?,
review_user_after_L = ?,
review_user_after_R = ?,
review_user_title = ?,
review_comment = ?
WHERE review_user_id = ? AND review_material_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("siiiissii", $add_study_period, $add_review_user_before_L, $add_review_user_before_R, $add_review_user_after_L, $add_review_user_after_R, $aaaaa, $add_review, $user_id, $review_material_id);
$res = $stmt->execute();

// 更新に成功したら一覧に戻る
	if( $res ) {
		$msg = '更新が完了しました。<a href="user_profile.php?id='.$user_id.'"><br>マイページ</a>に戻る';
	} else {
		$msg =  'エラーが発生しました。<a href="user_profile.php?id='.$user_id.'">マイページへ戻る</a>';
	}
	$stmt->close();

} else {
	$msg = 'エラーが発生しました。<a href="user_profile.php?id='.$user_id.'">マイページへ戻る</a>';
}
?>


<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-8 review_edit">
							<div class="card" style="min-height: 75vh;">
								<div class="card-header">レビュー編集</div> 
									<div class="card-body  pb-5" style="
background-color: #FFFBF7;">

<div class="text-center">
<?php 
echo $msg; 
?>
</div>

</div>
</div>
</div>
</div>
</div>




<?php
require_once __DIR__ . '/view/footer.php';
