<?php
require_once __DIR__ . '/user_profile_left.php';
; ?>


						<div class="d-block user_data_follower" style="min-height: 80px;"> <!-- C フォロワー表示 -->
									<div>
											<label><i class="fas fa-chart-bar mr-2"></i><span class="font-weight-bold"><?php echo $profile_user_data['user_name']; ?>をフォローしている人</span></label>
									</div>

							<?php 

							//フォロワー表示

							$res_fetch_data_followers = fetch_data_followers($profile_user_id, $mysqli);
							if($res_fetch_data_followers) {
								$followers_data = $res_fetch_data_followers;

								foreach($followers_data as $follower_data){ ?>
								<div>
									<label style="font-size: 0.8rem;">
							
										<a href="user_profile.php?id=<?php echo $follower_data['user_id'] ;?>" class="follower">
											<img src="<?php 
												if ($follower_data['user_img'] === '0') {
												echo fetch_defaultUserImg(); } else { echo $follower_data['user_img'] ;} 
												?>" style="width:40px; height:40px;" alt="user_profile_img">
												<?php 
												echo $follower_data['user_name'];
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




</div> 
<!-- end of container -->




</div> <!-- end of wrapper -->
<?php
require_once __DIR__ . '/view/footer.php';