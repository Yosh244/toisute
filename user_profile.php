<?php
require_once __DIR__ . '/user_profile_left.php';
; ?>



<!-- 右カラムA：アクティビティログ＆投稿レビュー -->

<div class="d-block user_data_analysis" style="min-height: 90px;"> 
								<div>
										<label><i class="fas fa-chart-bar mr-2"></i><span class="font-weight-bold">アクティビティログ</span></label>
									</div>

								<?php 

							// アクティビティログ表示（有れば）
							$res_fetch_data_ProfileUserLike = fetch_data_ProfileUserLike($profile_user_id, $mysqli);
							if($res_fetch_data_ProfileUserLike) {

								$data_ProfileUserLikes = $res_fetch_data_ProfileUserLike;
								foreach($data_ProfileUserLikes as $data_ProfileUserLike){ ?>
								<div>
									<p style="font-size: 0.8rem;">
									<i class="fab fa-gratipay mr-1"></i><a href="detail.php?id=<?php echo $data_ProfileUserLike['review_material_id'].'#review-no-'.$data_ProfileUserLike['review_id'];?>" class="activity-log">

										
									<img src="<?php 
												if ($data_ProfileUserLike['user_img'] === '0') {
												echo fetch_defaultUserImg(); } else { echo $data_ProfileUserLike['user_img'] ;} 
												?>" style="width:20px; height:20px;" alt="user_profile_img">

										<?php 
											echo $data_ProfileUserLike['user_name'];
										?>
										の投稿にいいねしました
												</u>
									</a>
									</p>
								</div>

							<?php 
								}
							}
							; ?> 


												</div>
												<div class="d-block user_data_post" style="min-height: 350px;"> <!-- B 投稿レビュー一覧 -->
														<label><i class="fas fa-pencil-alt mr-2"></i><span class="font-weight-bold">投稿レビュー</span></label>
															<?php 
																if (isset($no_post)) {
																echo $no_post;
																} else {

																		foreach ( $user_posts_data as $user_post_data ) {
																		?>

																	<div class="col-12 py-1">
																		<label>
																				<!-- 書籍名 -->
																				<u>
																					<a style="color: #505050;" href="detail.php?id=<?php echo $user_post_data['review_material_id'].'#review-no-'.$user_post_data['review_id'];?>"><?php echo $user_post_data['review_material_name']; ?>
																					</a>
																			</u>
																			<span style="color: #999; font-size: 12px;"><i class="far fa-clock mx-1"></i><?php echo $user_post_data['review_date']; ?><?php if ($user_post_data['review_update_date'] != NULL ) {echo '（更新：'.$user_post_data['review_update_date'].'）'; } ?></span>
																				<?php if($flg_profilePage === 2) { ?>
																				<!-- 編集ボタン -->
																				<a href="review_edit.php?id=<?php echo $user_post_data['review_material_id']; ?>" alt="編集する"><i class="far fa-edit text-primary m-2"></i></a>
																				<!-- 削除 -->
																				<a href="review_delete.php?id=<?php echo $user_post_data['review_material_id']; ?>"><i class="far fa-trash-alt text-danger"></i></a>
																				<?php }; ?>
																	</label>

															</div>

																		<?php 
																		}
																				
																}
															?>
												</div>
												<!-- </div> -->









<!--  -->
		</div> <!-- end of 右カラム -->

</div> <!-- end of row -->


</div> <!-- end of container -->




</div> <!-- end of wrapper -->
<?php
require_once __DIR__ . '/view/footer.php';
