<?php
// ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~
//  
//  Copyright (C) 2012  Alessandro Gubitosi
//  
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//  
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//  
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
//
// Lecense URI: http://www.gnu.org/licenses/gpl-3.0.txt
//
// ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~

function db_connect($table = ""){
	switch(strtolower($table)){
		// Insert a case of database for switch database
		/*
		case "db_1":
			$db_name = "db_1";
			break;
		case "db_2":
			...
		*/
		default:
			$db_name = "YOUR DATABASE NAME";
			break;
	}
	$pdo = new PDO("mysql:host=localhost;dbname=" . $db_name, "USER", "PASSWORD", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	return $pdo;
}
function browse($uri, $user, $options = ""){
	// Extract a random User Agent fromdatabase
	$pdo = db_connect("");
	$uas = $pdo->query("select `string` from `UAS` order by rand() limit 0,1");
	if ($uas->rowCount() > 0){
		while ($dato_uas = $uas->fetch()){
			$user_agent = $dato_uas["string"];
		}
	}
	$cookie = tempnam("/tmp", "browse_cookie_");
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $uri);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	$html = curl_exec($ch);
	$html = mb_convert_encoding($html, mb_detect_encoding($html));
	$info = curl_getinfo($ch);
	curl_close($ch);
	
	// Store log visits in a database
	$logpdo = db_connect("system_logs");
	$add_se = $logpdo->prepare("insert into `browser_scraping` (`uri`, `user`, `data`, `ora`) values(?, ?, ?, ?)");
	$add_se->bindParam(1, addslashes($uri)); // URI
	$add_se->bindParam(2, addslashes($user)); // User that launched operation
	$add_se->bindParam(3, date("Y-m-d")); // Date
	$add_se->bindParam(4, date("H:i:s")); // Hour
	if (!$add_se->execute()) {
		return "There was an error saving in database:\n" . $pdo->errorCode();
	} else {
		switch($options){
			case "header":
				return $info;
				break;
			case "mixed":
				return array($info, mb_convert_encoding($html, "UTF-8", mb_detect_encoding($html)));
				break;
			default:
				return mb_convert_encoding($html, "UTF-8", mb_detect_encoding($html));
				break;
		}
	}
}
?>