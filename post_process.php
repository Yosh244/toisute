<?php
require_once __DIR__ . '/view/header.php';

?>





<?php
	$_SESSION['ticket'] = htmlspecialchars($_SESSION['ticket'], ENT_QUOTES, 'UTF-8');
	$_POST['ticket'] = htmlspecialchars($_POST['ticket'], ENT_QUOTES, 'UTF-8');
	
// if(isset($_POST['ticket'], $_SESSION['ticket'])){
  $ticket = $_POST['ticket'];
	if ($ticket !== $_SESSION['ticket']){
		header('Location: detail.php');
		exit;
  } else {
  die('エラーが発生しました。<a href="detail.php">前の画面に戻る</a>');
}

// //  ポストされたワンタイムチケットを取得する。
// $ticket = isset($_POST['ticket'])    ? $_POST['ticket']    : '';
// //  セッション変数に保存されたワンタイムチケットを取得する。
// $save   = isset($_SESSION['ticket']) ? $_SESSION['ticket'] : '';
// //  セッション変数を解放し、ブラウザの戻るボタンで戻った場合に備える。
// unset($_SESSION['ticket']);
// if ($ticket === '') {
	// 	die('不正なアクセスです');
// }
// //  ポストされたワンタイムチケットとセッション変数から取得したワン
// //  タイムチケットが同じ場合、正常にポストされたとみなして処理を行
// //  う。
// if ($ticket === $save) {
	// 	echo 'Normal Access';
// }
// //  ブラウザの戻るボタンで戻った場合は、セッション変数が存在しない
// //  ため、2重送信とみなすことができる。
// //  また、不正なアクセスの場合もワンタイムチケットが同じになる確率
// //  は低いため、不正アクセス防止にもなる。
// else {
	// 	echo 'Dual Posted';
// }
?>

