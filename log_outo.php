<?php

//共通変数・関数の読み込み
require('function.php');

debug('==================================================');
debug('ログアウトページ');
debug('==================================================');
debugLogStart();

debug('ログアウトします');
//セッションを削除（ログアウト）
session_destroy();
debug('ログインページへ遷移します');
//ログインページ
header("Location:login.php");

?>