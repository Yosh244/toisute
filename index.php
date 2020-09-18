<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/view/header.php';
require_once __DIR__ . '/../app/functions/product.php';

?>

<div class="jumbotron-fluid jumbotron-extend col-lg-12">
  <div class="container-fluid">
    <div class="row">
  <div class="jumbotron-bg-img">
    <h1 class="my-4 lead-copy text-center text-light">あなたに合う<br>TOEIC対策本を見つけよう。</h1>
    <div class="row justify-content-center no-gutters">
      <form action="search.php" method="get" class="form-inline">
        <div class="form-group">
          <input type="text" name="id" class="form-control form-control-lg" style="width: 240px" placeholder="教材を検索">
        </div>
        <div class="form-group">
           <button type="submit" class="btn btn-warning btn-lg ml-2"><i class="fas fa-search fa-inverse"></i></button>
        </div>
      </form>
    </div>
  </div>
</div>
  </div>
</div>

<div class="wrapper">
  <div class="container-fluid">

      <div class="text-center">
        <h2>トイステとは</h2>
      </div>
      <div class="text-center mb-3 font-weight-bold">
      <span style="background: linear-gradient(transparent 50%, yellow 50%);">TOEIC対策教材（書籍）のレビューサイトです。</span><br>
        <br>
        「就活・転職・昇進のためにTOEICが必要」<br>
        「ビジネスで役立つ英語力をみにつけたい」<br>
        といった理由で、TOEICの勉強をする人も多いかと思います。<br>
        <br>
        TOEICのスコアアップを目指すには、自分に合ったTOEIC対策本で勉強することが  ポイントですが、<br>
        「TOEIC教材はたくさんありすぎて、どれを選んだらよいかわからない」<br>
        「自分のレベルに合った本がわからない！」<br>
        「教材を買ってみたけど、どうやって勉強すればいいんだろう？」<br>
        といった悩みをもつ人は多いのではないでしょうか。<br>
        <br>
        トイステは、<span style="background: linear-gradient(transparent 50%, yellow 50%);">みなさんが実際に使ったTOEIC対策本のレビュー（勉強法やその結果）をシェアする</span>ための場所です。<br>
        <br>
        トイステに投稿されたレビューを読むことで、<span style="background: linear-gradient(transparent 50%, yellow 50%);">自分に合ったTOEIC対策本を効果的に見つけることができます</span>。<br>
        <br>
        <form>
        <button type="button" class="btn btn-warning btn-lg text-white  font-weight-bold" onclick="location.href='search.php'"><i class="fas fa-user-edit mr-2"></i>投稿してみる</button>
        </form>
        <br>
        <span style="background: linear-gradient(transparent 50%, yellow 50%);  font-size: 20px;">あなたが使ったTOEIC対策本をシェアしてください！</span>
      </div>
  </div>

  <div class="container-fluid">
    <div class="row no-gutters py-5">
      <div class="card card-custom col-xl-6 offset-xl-3">
    		<div class="card-body card-new-custom">
      		<h4 class="card-title" style="text-align: center;">◆◆新着レビュー◆◆</h4>
      		<p class="card-text">
            <ul style="padding-inline-start: 20px;">
	  				<?php
	  				// 新着レビューを表示
	  				$products_data = fetch_newReview($mysqli);
	  				foreach ($products_data as $product_data ) {
            ?>
              <li>
              <a href="detail.php?id=<?php echo $product_data ['review_material_id'] ?>"><?php echo $product_data['review_material_name']; ?></a> <span style="color: #999; font-size: 12px;"><i class="far fa-clock mx-1"></i><?php echo $product_data ['review_date']; ?></span>
            </li>
            <?php  } // End of foreach ?>
            </ul>
	  			</p>
        </div>
       </div>
    </div>
  </div>
</div>

<?php
require_once __DIR__ . '/view/footer.php';
?>