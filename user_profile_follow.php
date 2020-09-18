<?php
require_once __DIR__ . '/user_profile_left.php';
; ?>



					<!-- C フォロワー表示 -->
					<div class="d-block user_data_follow" style="min-height: 80px;"> 
									<div>
											<label><i class="fas fa-chart-bar mr-2"></i><span class="font-weight-bold"><?php echo $profile_user_data['user_name']; ?>がフォローしている人</span></label>
									</div>

							<?php 

							//フォロワー表示
							$res_fetch_data_follows = fetch_data_follows($profile_user_id, $mysqli);
							if($res_fetch_data_follows) {

								$follows_data = $res_fetch_data_follows;
								foreach($follows_data as $follow_data){ ?>
								<div>
									<label style="font-size: 0.8rem;">
							
										<a href="user_profile.php?id=<?php echo $follow_data['user_id'] ;?>" class="follow">
											<img src="<?php 
												if ($follow_data['user_img'] === '0') {
												echo fetch_defaultUserImg(); } else { echo $follow_data['user_img'] ;} 
												?>" style="width:40px; height:40px;" alt="user_profile_img">
												<?php 
												echo $follow_data['user_name'];
												?>
										</a>
									</label>
								</div>
							<?php 
								}
							}
							; ?> 


									</div> <!-- end of カラムC -->



					</div> <!-- end of 右カラム -->
				
				
				
				
	</div> <!-- end of row -->
</div> <!-- end of container -->




</div> <!-- end of wrapper -->
<?php
require_once __DIR__ . '/view/footer.php';