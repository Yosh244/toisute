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


//DB
$user_id = $mysqli->real_escape_string($user_id);
$review_material_id = $mysqli->real_escape_string($review_material_id);
$query = "SELECT 
	*
	FROM reviews 
	WHERE review_user_id = $user_id AND review_material_id = $review_material_id";
$result = $mysqli->query($query);

if ($result) {
	$review_data = $result->fetch_assoc();
} else {
	// データが読み込めなかったら一覧に戻る
	$url = "user_profile.php?id=".$user_id;
	header("Location: $url");
	exit;
}

?>



<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-sm-8 review_delete">
							<div class="card">
								<div class="card-header">レビュー削除</div> 
									<div class="card-body  pb-5" style="
background-color: #FFFBF7;">

	<div class="container">
	<div class="row">
		 <div class="col-12">
				<label>教材の使用期間</label>
				<form id="delete" action="review_delete2.php?id=<?php echo $review_material_id ?>" method="post">
					<select id="period" name="delete_study_period" class="form-control mb-2" style="width: 50%" disabled>
						<option value='1週間' <?php if ($review_data['review_user_study_period'] === '1週間') { echo 'selected'; } ?>>1週間</option>
						<option value='2週間' <?php if ($review_data['review_user_study_period'] === '2週間') { echo 'selected'; } ?>>2週間</option>
						<option value='1ヶ月' <?php if ($review_data['review_user_study_period'] === '1ヶ月') { echo 'selected'; } ?>>1ヶ月</option>
						<option value='2ヶ月' <?php if ($review_data['review_user_study_period'] === '2ヶ月') { echo 'selected'; } ?>>2ヶ月</option>
						<option value='3ヶ月' <?php if ($review_data['review_user_study_period'] === '3ヶ月') { echo 'selected'; } ?>>3ヶ月</option>
						<option value='6ヶ月以上' <?php if ($review_data['review_user_study_period'] === '6ヶ月以上') { echo 'selected'; } ?>>6ヶ月以上</option>
					</select>
					<label>学習前のスコア（リスニング／リーディング）</label>
												<div class="container-fluid">
													<div class="row">
															<div class="col-lg-3 col-md-4 col-sm-5 col-7 p-0 mr-3 mb-1">
																<input type="number" class="form-control" name="delete_before_L" min="5" max="495" step="5" placeholder="リスニング（点）" required value="<?php echo $review_data['review_user_before_L']; ?>" disabled>
															</div>
															<div class="col-lg-3 col-md-4 col-sm-5 col-7 mb-2 p-0">
																<input type="number" class="form-control" name="delete_before_R" min="5" max="495" step="5" placeholder="リーディング（点）" required value="<?php echo $review_data['review_user_before_R']; ?>" disabled>
															</div>
														</div>
													</div>
													<label>学習後のスコア（リスニング／リーディング）</label>
												<div class="container-fluid">
													<div class="row">
															<div class="col-lg-3 col-md-4 col-sm-5 col-7 p-0 mr-3 mb-1">
																<input type="number" class="form-control" name="delete_before_L" min="5" max="495" step="5" placeholder="リスニング（点）" required disabled value="<?php echo $review_data['review_user_before_L']; ?>">
															</div>
															<div class="col-lg-3 col-md-4 col-sm-5 col-7 mb-2 p-0">
																<input type="number" class="form-control" name="delete_before_R" min="5" max="495" step="5" placeholder="リーディング（点）" required disabled value="<?php echo $review_data['review_user_before_R']; ?>">
															</div>
														</div>
													</div>
													<div class="form-group">
														<label>レビュータイトル</label>
															<input type="text" name="aaaaa" class="form-control" maxlength="80" required value="<?php echo $review_data['review_user_title']; ?>" disabled>
													</div>
				<label>詳細</label>
					<textarea required name="delete_review" class="form-control mb-3" disabled><?php 
									$review_data['review_comment'] = str_replace(array('\r\n', '\r', '\n'), "\r\n", $review_data['review_comment']);
									echo $review_data['review_comment']; ?></textarea>
					<button type="button" onclick="location.href='user_profile.php?id=<?php echo $user_id ?>'" class="delete btn btn-secondary btn-sm mr-3" >キャンセル</button>
					<input type="submit" name="delete_submit" class="delete btn btn-danger btn-sm" value="削除">
				</form>
		 </div>
	</div>
</div>


</div>
	</div>
	</div>
	</div>
</div>




<?php
require_once __DIR__ . '/view/footer.php';
