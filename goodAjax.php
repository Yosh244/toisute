<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/functions/review.php';

session_start();
if(!empty($_SESSION['user_id'])) {
	$user_id = (int)$_SESSION['user_id'];
}

// いいねボタンが押されたときの処理（追加・削除→全いいね数表示）

// ログインユーザーのいいね情報を取得
if(!empty($_POST['postId'])){
	$review_id = (int)$_POST['postId'];
		
	$sql1 = "SELECT count(*) as cnt FROM good WHERE review_id = ? AND good_user_id = ? LIMIT 1";
	$stmt1 = $mysqli->prepare($sql1);
	$stmt1->bind_param('ii', $review_id, $user_id);
	$stmt1->execute();
	$resultSet1 = $stmt1->get_result(); 
	$user_good_row = $resultSet1->fetch_all(); 


	// レコードが1件でもある場合
	if ($user_good_row[0][0] > 0){

			// レコードを削除する→<span>内に全いいね数を表示する
			$sql2 = "DELETE FROM good WHERE review_id=? AND good_user_id=?";
			$stmt2 = $mysqli->prepare($sql2);
			$stmt2->bind_param('ii', $review_id, $user_id);
			$stmt2->execute();
			$stmt2->close();
			good_number($review_id, $mysqli);
	
	} else {
	//レコードがない場合 

			// レコードを挿入する→<span>内に全いいね数を表示する
			$sql = "INSERT INTO good (review_id, good_user_id, create_date) 	VALUES (?, ?, NOW())";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('ii', $review_id, $user_id);
			$stmt->execute();
			$stmt->close();
			good_number($review_id, $mysqli);

	}
}
// back to script.js



// 	$good_check_res = $stmt1->fetch();
// 	$stmt1->close();

// 	// レコードが1件でもある場合
// 	if ($good_check_res == true){

// 	// レコードを削除する
// 	$sql2 = "DELETE FROM good WHERE review_id=? AND good_user_id=?";
// 	$stmt2 = $mysqli->prepare($sql2);
// 	$stmt2->bind_param('ii', $review_id, $user_id);
// 	$stmt2->execute();
// 	$stmt2->close();

// 	} else {
// 		// レコードを挿入する
// 		$sql = "INSERT INTO good (review_id, good_user_id, create_date) 	VALUES 			(?, ?, NOW())";
// 		$stmt = $mysqli->prepare($sql);
// 		$stmt->bind_param('ii', $review_id, $user_id);
// 		$stmt->execute();
// 		$stmt->close();
// 						if(!$stmt) {
// 								echo 'エラーが発生しました。';
// 						}

// 	}
// }











