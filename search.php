<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/product.php';
?>

<div class="wrapper">

<div class="container-fluid">




<br>
		<div class="row justify-content-center">
      <form action="search.php" method="get" class="form-inline">
        <div class="form-group">
          <input type="text" name="id" class="form-control form-control-lg mr-2" style="width: 240px" placeholder='"600点" "文法"など'>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-warning btn-lg"><i class="fas fa-search fa-inverse"></i></button>
        </div>
      </form>
    </div>

<br>

<!-- 検索キーワードが入っていれば、結果を表示する -->

	<?php if(!empty($_GET['id'])) { 
		$res = search_result($mysqli); 
		if($res) { 
			$num_rows = $res->num_rows;
			?>
	<h3 class="h3-line">検索結果（<?php echo $num_rows ;?>件）</h3>

		<?php 
		//検索結果のリスト化
		if ($num_rows == 0){
			echo '該当する教材が見つかりませんでした。';
		} else {
			while ($row = $res->fetch_assoc()) {
				$search_res_name = $row['material_name'];
				$search_res_id = $row['material_id'];
				$search_res_img = $row['material_img'];
				$search_res_publisher = $row['material_publisher'];
				?>
		<div class="col-xs-12">
			<ul>
				<li>
					<!-- 書名＆出版社 -->
					<p class="search-results"><a href="detail.php?id=<?php echo $search_res_id ?>"><?php echo $search_res_name ?>（<?php echo $search_res_publisher;?>）</a></p>
					<!-- 書影 -->
					<a href="detail.php?id=<?php echo $search_res_id ?>">
						<img src="<?php 
						echo $search_res_img?>" alt="book_cover" width="150px" height="225px"/>
					</a>
				</li>
			</ul>		
		</div>
		<?php	
			}
		}
		} 

	} ?>
					<script>
								const images = document.querySelectorAll('img');
								images.forEach((image) => {
								　image.addEventListener('error',() => {
								　　image.setAttribute('src', 'https://aws-and-infra-wp-toisute.s3-ap-northeast-1.amazonaws.com/self-upload/website/no_img_02.png');
								　});
								});
					</script>
			<!-- <div class="my-5">
				<nav aria-label="Page Navigation">
					<ul class="pagination justify-content-center">
						<li class="page-item"><a class="page-link" href="#">前へ</a></li>
						<li class="page-item"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item"><a class="page-link" href="#">4</a></li>
						<li class="page-item"><a class="page-link" href="#">5</a></li>
						<li class="page-item"><a class="page-link" href="#">次へ</a></li>
					</ul>
				</nav>
			</div> -->

</div>



<div class="container-fluid">
	<h3 class="h3-line">新着の口コミ</h3>
    <div class="card">
    		<div class="card-body card-new-custom">
      		<p class="card-text">
						<ul>
								<?php
								// 商品一覧データを取得
								$products_data = fetch_newReview($mysqli);
								foreach ($products_data as $product_data ) {
								?>
									<li>
										<h5>
											<a href="detail.php?id=<?php echo $product_data ['review_material_id'] ?>"><?php echo $product_data  ['review_material_name']; ?></a><span style="color: #999; font-size: 12px;"><i class="far fa-clock mx-1"></i><?php echo $product_data ['review_date']; ?></span>
										</h5>
									</li>
								<?php  } // End of foreach ?>
						</ul>
	  			</p>
	  		</div>
    </div>
  </div>

						</div>

<?php
require_once __DIR__ . '/view/footer.php';
?>