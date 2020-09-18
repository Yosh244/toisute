<?php
session_start();

header('X-FRAME-OPTIONS:DENY');

 ?><!DOCTYPE html>
<html lang="ja">
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" type="image/x-icon" href="https://aws-and-infra-wp-toisute.s3-ap-northeast-1.amazonaws.com/self-upload/website/favicon/favicon.ico">



		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

		<script src='//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
		<script src="js/modaal.min.js"></script>

		<!-- cropper (jQuery is required)-->
		<!-- <link  href="/js/cropper/cropper.css" rel="stylesheet">
		<script src="/js/cropper/cropper.js"></script> -->
		
		<link rel="stylesheet" href="css/modaal.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>トイステ</title>

</head>

<body>
<?php
require_once __DIR__ . '/../../app/config/database.php';
?>

<div class="body-wrapper">

<nav class='navbar navbar-default navbar-static-top'>
  <div class='container-fluid' style="padding: 0;">
      <a href='index.php' class='navbar-brand'><i class="fas fa-book-open">トイステ</i></a><span class="subtitle d-none d-md-inline mr-auto" style="font-size: 12px;">TOEIC参考書のレビュー・勉強法シェアサイト</span>

      <ul class="nav navbar-right pull-right justify-content-end" id='header-buttons'>
									<!-- 1.検索窓 -->
									<li class="nav-item my-auto d-none d-sm-inline">
											<form action="search.php" method="get" class="form-inline mr-3">
													<div class="input-group">
														<input type="search" name="id" class="form-control" placeholder="教材を検索" aria-label="Search">
														<span class="input-group-btn">
															<button type="submit" class="btn btn-warning"><i class="fas fa-search fa-inverse "></i></button>
														</span>
													</div>
											</form>
									</li>
									<?php
									// 2. 未ログイン（ログイン＆ユーザー登録）
											if(empty($_SESSION['user_id'])) { ?>
										<li class="nav-item my-auto"  style="margin:0">
											<button type="button" onclick="location.href='signup.php'" class="btn non-account btn-outline-light btn-sm" style="font-size: 0.7rem;">
											<span>ユーザー登録</span>
											</button>
										</li>
										<li class="nav-item my-auto">
											<a href='login.php' class="nav-link non-account">
												<span style="font-size: 0.7rem">ログイン</span>
											</a>
									</li>
											<?php	} else { ?>

								



        <li class='navbar-btn'>
          <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
											<?php
												(int)	$_SESSION['user_id'] = $mysqli->real_escape_string($_SESSION['user_id']);
																			$sql = "SELECT 
																			user_img
																			FROM 
																				users
																			WHERE 
																				user_id = ?";
																			$stmt = $mysqli->prepare($sql);
																			$stmt->bind_param("i", $_SESSION['user_id']);
																			$stmt->execute();
																			$result = $stmt->get_result();
																				$row = $result->fetch_assoc();
																				$profile_imgs[] = $row;
																				foreach ($profile_imgs as $profile_img);
													; ?>
																<!-- 未設定ならデフォルトアイコン -->
																<img src="<?php
																		if ($profile_img['user_img'] === '0') {
																			echo 'https://aws-and-infra-wp-toisute.s3-ap-northeast-1.amazonaws.com/self-upload/website/profile_img_default/profile_img_default_01.png';
																		} else {
																// カスタムアイコンを表示
																			echo $profile_img['user_img'];
																	}		?>" class="rounded-circle user_profile_img" alt="user_profile_img" width="40px" height="40px"/>


              <span class="caret"></span>
            </button>
						<!-- 3. ドロップダウン（ログインユーザー） -->
            <ul class="dropdown-menu">
							
              <li><a class="dropdown-item" href="user_profile.php?id=<?php echo $_SESSION['user_id'] ?>"><i class="fas fa-user-check mr-1"></i>マイページ</a></li>
              <li><a class="dropdown-item" href="user_setting.php?"><i class="fas fa-cog mr-2"></i>設定</a></li>
              <li>	<a class="dropdown-item" href="logout.php"><i class="fas fa-sign-in-alt mr-2"></i>ログアウト</a></li>
            </ul>
          </div>
				</li>
				<?php							
									}	?>
      </ul>
  </div>
</nav>
	
	<main>


