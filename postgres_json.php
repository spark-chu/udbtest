<?php

// 接続情報の定義
$pgsql_schema	= "udb";
// $pgsql_host		= "10.248.7.161";
$pgsql_host		= "localhost";
$pgsql_user		= "guest";
$pgsql_password	= "guest";

// 初期処理
ini_set("date.timezone", "Asia/Tokyo");

// 結果返却用
$data			= array();

// 発行するSQL
$sql			= "";

// PostgreSQL接続文字列生成
$pgsql_con_str	= "host=".$pgsql_host." port=5432 dbname=".$pgsql_schema." user=".$pgsql_user." password=".$pgsql_password;

try
{
	// ここにGETパラメータからの取得処理と、復号化処理を入れる
	if (array_key_exists("sql", $_POST))
	{
		// GETパラメータ取得
		$sql			= $_POST["sql"];

		$pgsql_con		= pg_connect($pgsql_con_str);

		if ($pgsql_con)
		{
			$resultSet	 = pg_query($pgsql_con, $sql);

			while($row = pg_fetch_assoc($resultSet))
			{
				array_push($data, $row);
			}

			try
			{
				pg_close($pgsql_con);
			}
			catch(Exception $exp){}
		}
	}
}
catch(Exception $exp)
{
	// 致命的エラー発生時(とりあえず結果なし)
	$data			= array();
}

// JSON形式での返却
header("Content-type: application/json; charset=utf-8");
echo json_encode($data);

?>
