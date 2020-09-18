<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/product.php';
require_once __DIR__ . '/../app/functions/mail_send.php';
//PHPMailerの必要ファイルの読み込み

require_once __DIR__ . "/phpmailer/src/PHPMailer.php";
require_once __DIR__ . "/phpmailer/src/SMTP.php";
require_once __DIR__ . "/phpmailer/src/Exception.php";
require_once __DIR__ . "/phpmailer/src/OAuth.php";
require_once __DIR__ . "/phpmailer/language/phpmailer.lang-ja.php";

// 画面切り替え変数
$page_flag = 0;

// サニタイズ
if( !empty($_POST) ) {
$_POST['name'] = htmlspecialchars( $_POST['name'], ENT_QUOTES);
$_POST['email'] = htmlspecialchars( $_POST['email'], ENT_QUOTES);
$_POST['inquiry'] = htmlspecialchars( $_POST['inquiry'], ENT_QUOTES);
}

if( !empty($_POST['btn_confirm']) ) {

	$page_flag = 1;

	} elseif( !empty($_POST['btn_submit']) ) {

	$page_flag = 2;
	
}
	?>

<div class="wrapper">

				<!-- 2.確認画面 -->
				<?php if( $page_flag === 1 ): ?>
					<div class="col-xs-12">
						<form method="post" action="">
								<div class="element_wrap">
									<label>氏名：</label>
									<p><?php echo $_POST['name']; ?></p>
								</div>
								<div class="element_wrap">
									<label>メールアドレス：</label>
									<p><?php echo $_POST['email']; ?></p>
								</div>
									<div class="element_wrap">
									<label>お問い合わせ内容：</label>
									<p><?php echo nl2br($_POST['inquiry']); ?></p>
								</div>
								<input type="submit" name="btn_back" class="btn btn-secondary" value="戻る" />
								<input type="submit" name="btn_submit" class="btn btn-warning" value="送信" />
								<input type="hidden" name="name" value="<?php echo $_POST['name']; ?>">
								<input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
									<input type="hidden" name="inquiry" value="<?php echo $_POST['inquiry']; ?>">
						</form>
					</div>



					<!-- 3. 送信 -->
					<?php elseif( $page_flag === 2 ): 
							$post_name = $_POST['name'];
							$post_email = $_POST['email'];
							$post_inquiry = $_POST['inquiry'];
							send_mail($post_name, $post_email,$post_inquiry);
				else: ?>



				<!-- 1. 入力画面 -->
				
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-8">
							<div class="card">
								<div class="card-header">お問い合わせ</div> 
									<div class="card-body">

										<form action="" method="post">
											<div class="form-group">
												<label class="control-label">氏名</label>
													<div>
														<input class="form-control" type="text" name="name" value="<?php if( !empty($_POST['name']) ){ echo $_POST['name']; } ?>" required/>
													</div>
											</div>
											<div class="form-group">
												<label class="control-label">メールアドレス</label>
													<div> 
														<input class="form-control" type="text" name="email" value="<?php if( !empty($_POST['email']) ){ echo $_POST['email']; } ?>" required/>
													</div>
											</div>
											<div class="form-group">
												<label class="control-label">お問い合わせ内容</label>
													<textarea class="form-control" name="inquiry" rows="5" required><?php if( !empty($_POST['inquiry']) ){ echo $_POST['inquiry']; } ?></textarea>
											</div>
											<div class="form-group">
												<div>
													<input type="submit" name="btn_confirm" class="btn btn-warning" value="確認" />
												</div>
											</div>
										</form>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<?php endif; ?>



</div> <!-- end of wrapper -->

<?php
require_once __DIR__ . '/view/footer.php';