<?php

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/review.php';
require_once __DIR__ . '/../app/functions/user.php';
require_once __DIR__ . '/../app/functions/product.php';

?>
<?php
$product_id = $_GET['id'];


if(!empty($_SESSION['user_id'])) {
	$user_id = (int)$_SESSION['user_id'];
			//レビュー済みの人が投稿用ページに行けないようにする 
			$res_reviewExists = reviewExists($product_id, $user_id, $mysqli);
			if($res_reviewExists) {
				header("Location: detail_post.php?id=$product_id");
				exit;
			}
} 

//2重ポスト防止―ワンタイムチケット生成
$ticket = md5(uniqid(rand(), true));
$_SESSION['ticket'] = $ticket;

// 書名を取得する
$product_id = $_GET['id'];
$product_id = (int)$mysqli->real_escape_string($product_id);
$sql = "SELECT * FROM materials WHERE material_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt ->bind_param("i", $product_id);
$res = $stmt->execute();
$resultSet = $stmt->get_result(); 
while($row = $resultSet->fetch_assoc()){
	$materials_data_byProductId[] = $row;
}
	foreach ($materials_data_byProductId as $material_data_byProductId);
		

// 投稿時の処理
				if(!empty($_POST['add_review']) && !empty($_POST['add_study_period']) && !empty($_POST['add_before_L']) && !empty($_POST['add_before_R']) && !empty($_POST['add_after_L']) && !empty($_POST['add_after_R']) && !empty($_POST['aaaaa'])) {
					$add_material_name = $material_data_byProductId["material_name"];
					$add_study_period = $_POST['add_study_period'];
					$add_before_L = $_POST['add_before_L'];
					$add_before_R = $_POST['add_before_R'];
					$add_after_L = $_POST['add_after_L'];
					$add_after_R = $_POST['add_after_R'];
					$aaaaa = $_POST['aaaaa'];
					$add_review = $_POST['add_review'];
					add_review($product_id, $add_material_name, $add_study_period, $add_before_L, $add_before_R, $add_after_L, $add_after_R, $aaaaa, $add_review, $mysqli);
					} 
	
	?>  <!-- // end of foreach -->


	
<!-- // 入力フォーム -->
<div class="wrapper-review px-3 pt-0">




<div class="container-fluid p-0">
					<div class="row no-gutters justify-content-center ">
						<div class="col-sm-10 col-sm-8 col-lg-8 col-xl-7 review">
							<div class="card review">
								<div class="card-header">レビューを投稿する
</div>
									<div class="card-body pt-1" style="
background-color: #FFFBF7;">
										<div class="mt-2 mb-4 "><small  class="text-muted">
												<?php 
												echo $material_data_byProductId["material_name"]; ?>
										</small>
										</div>
										
							<form action="" method="post">
														<label>教材の使用期間</label>
															<div class="col-4 p-0 mb-3">
																<select name="add_study_period" class="form-control">
																	<option value='1週間'>1週間</option>
																	<option value='2週間'>2週間</option>
																	<option value='1ヶ月'>1ヶ月</option>
																	<option value='2ヶ月'>2ヶ月</option>
																	<option value='3ヶ月'>3ヶ月</option>
																	<option value='6ヶ月以上'>6ヶ月以上</option>
																</select>
															</div>
														<div class="form-group">
															<label>学習前のスコア（リスニング／リーディング）</label>
																	<div class="container-fluid">
																		<div class="row">
																				<div class="col-lg-3 col-md-4 col-sm-5 col-7 p-0 mr-3 mb-1">
																					<input type="number" class="form-control" name="add_before_L" min="5" max="495" step="5" placeholder="リスニング（点）" required>
																				</div>
																				<div class="col-lg-3 col-md-4 col-sm-5 col-7 p-0 mr-3 mb-1">
																					<input type="number" class="form-control" name="add_before_R" min="5" max="495" step="5" placeholder="リーディング（点）" required>
																				</div>
																		</div>
																	</div>
															</div>
															<div class="form-group">
															<label>学習後のスコア（リスニング／リーディング）</label>
																	<div class="container-fluid">
																		<div class="row">
																				<div class="col-lg-3 col-md-4 col-sm-5 col-7 p-0 mr-3 mb-1">
																					<input type="number" class="form-control" name="add_after_L" min="5" max="495" step="5" placeholder="リスニング（点）" required>
																				</div>
																				<div class="col-lg-3 col-md-4 col-sm-5 col-7 p-0 mr-3 mb-1">
																					<input type="number" class="form-control" name="add_after_R" min="5" max="495" step="5" placeholder="リーディング（点）" required>
																				</div>

																		</div>
																	</div>
														</div>

													<div class="form-group">
														<label>レビュータイトル</label>
														<div class="col-md-9 p-0">
															<input type="text" name="aaaaa" class="form-control" maxlength="80" required value="<?php if( !empty($_POST['aaaaa']) ){ echo $_POST['aaaaa']; } ?>" >
														</div>
													</div>

													<div class="form-group">
														<label>詳細</label>
														<div class="col-md-9 p-0">
															<textarea required name="add_review" class="form-control" style="height:300px;" placeholder="この教材を使ってどのように勉強したか、記入してください。" maxlength='4000'></textarea>
														</div>
													</div>
													<div class="form-group">
													<input type="button" onclick="location.href='detail.php?id=<?php echo $product_id ; ?>'" value="キャンセル" class="btn btn-secondary mr-2 d-inline">
															<div class="col-sm-12 d-inline">
																<button type="submit" name="editReady" class="btn bg-warning"><i class="fas fa-user-edit mr-2"></i>投稿する</button>
																<!-- <script type="text/javascript">
																function checkSubmit() {
																return confirm("以上の内容で投稿しますか？");
																}</script> -->

															</div>
													</div>

						</form>
</div>
</div>
</div>
</div>
</div>

</div> <!-- end of レビューフォーム -->


<?php
require_once __DIR__ . '/view/footer.php';