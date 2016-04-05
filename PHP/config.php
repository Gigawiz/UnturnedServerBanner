<?php
$use_mysql = true; //set to false if you only want to use GET based information
$auto_add = true; //set to false if you want users to register their server manually
$api_keys = true; //set this to false if you don't require users to have an api key to update information
//only set these if using mysql mode
$db_host = "localhost"; //your database host, usually "localhost"
$db_user = ""; //username with access to the database
$db_pass = ""; //password for said user
$db_name = ""; //the name of your database

if ($use_mysql) //make sure we want to use mysql, otherwise, don't try to connect to a database
{
	$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

	if($db->connect_errno > 0){
		die('Unable to connect to database [' . $db->connect_error . ']');
	}
}
//MySql functions. Only edit if you know what you're doing.
function getServer($db, $ip)
{
	$sql = <<<SQL
    SELECT *
    FROM `servers`
    WHERE `ip` = '$ip' 
SQL;

	if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
	}  
	$arr    = array();
	while($row = $result->fetch_assoc()){
		$arr[0]['name'] = $row['name'];
		$arr[0]['font-size'] = '27';
		$arr[0]['color'] = 'white';
		
		$arr[1]['name'] = "IP: ".$row['ip'];
		$arr[1]['font-size'] = '16';
		$arr[1]['color'] = 'white';
		
		$arr[2]['name'] = "Map: ".$row['map'];
		$arr[2]['font-size'] = '13';
		$arr[2]['color'] = 'white';
		
		$arr[3]['name'] = "Players: ".$row['current_players']."/".$row['max_players'];
		$arr[3]['font-size'] = '13';
		$arr[3]['color'] = 'white';
		
		$arr[4]['name'] = "Game Mode: ".$row['game_mode'];
		$arr[4]['font-size'] = '13';
		$arr[4]['color'] = 'white';
		
		$arr[5]['name'] =  "Server Status: ".$row['server_status'];
		$arr[5]['font-size'] = '13';
		$arr[5]['color'] = 'white';
	}
	return $arr;
}

function update_server($db, $name, $ip, $map, $curplayers, $maxplayers, $gamemode, $serverstatus, $id)
{
	//let's make sure we don't get hacked by unescaping strings
	$vernam = $db->real_escape_string($name);
	$verip = $db->real_escape_string($ip);
	$vermap = $db->real_escape_string($map);
	$vercurply = $db->real_escape_string($curplayers);
	$vermaxply = $db->real_escape_string($maxplayers);
	$vergamemode = "PvP";
	if ($gamemode = "False")
	{
		$vergamemode = "PvE";
	}
	$verstatus = $db->real_escape_string($serverstatus);
	
	$SQL = "UPDATE `servers` SET `name` = ?, `ip` = ?, `map` = ?, `current_players` = ?, `max_players` = ?, `game_mode` = ?, `server_status` = ? WHERE `id` = '".$id."'";
	
	if ($stmt = $db->prepare($SQL)) {
     $stmt->bind_param("sssssss", $vernam,$verip,$vermap,$vercurply,$vermaxply,$vergamemode,$verstatus);
     $stmt->execute();

		 return "Server Stats Updated!";
	}
	$db->close();
}

function create_server($db, $name, $ip, $map, $curplayers, $maxplayers, $gamemode, $serverstatus, $api_key)
{
	//let's make sure we don't get hacked by unescaping strings
	$vernam = $db->real_escape_string($name);
	$verip = $db->real_escape_string($ip);
	$vermap = $db->real_escape_string($map);
	$vercurply = $db->real_escape_string($curplayers);
	$vermaxply = $db->real_escape_string($maxplayers);
	$vergamemode = "PvP";
	if ($gamemode = "False")
	{
		$vergamemode = "PvE";
	}
	$verstatus = $db->real_escape_string($serverstatus);
	
	$SQL = "INSERT INTO `servers` (`name`, `ip`, `map`, `current_players`, `max_players`, `game_mode`, `server_status`, `api_key`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	if ($stmt = $db->prepare($SQL)) {
     $stmt->bind_param("ssssssss", $vernam,$verip,$vermap,$vercurply,$vermaxply,$vergamemode,$verstatus,$api_key);
     $stmt->execute();

		 return "Server Added!";
	}
	$db->close();
}

function server_exists($db, $ip, $name)
{		
	$fixedname = strtolower($name);
		//our query to find accounts
		$sql = <<<SQL
    SELECT `id`,`ip`, LOWER(`name`)
    FROM `servers`
    WHERE `ip` = '$ip' 
	OR `name` = '$fixedname'
SQL;

	if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
	}  
	$ret = 0; //set the result to 0 (not found). If the server is found, this will be set to the record id of the server
	//if we get more than 0, then the server is in our database
	//get the id of that server so we can update it
	while($row = $result->fetch_assoc()){
		$ret = $row['id'];
	}
	//return the result.
	return $ret;
}

function check_api_key($db, $api_key)
{
		//our query to find accounts
		$sql = <<<SQL
    SELECT `api_key`
    FROM `servers`
    WHERE `api_key` = '$api_key' 
SQL;

	if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
	}  
	//if we get more than 0, then the API key is in use
	if ($result->num_rows > 0)
	{
		return true;
	}
	//return the result.
	return false;
}

function get_api_key()
{
	return md5(microtime().rand());
}

function check_ban($db, $ip, $name)
{		
		//our query to find banned accounts
		$sql = <<<SQL
    SELECT *
    FROM `servers`
    WHERE `ip` = '$ip' 
	AND `name` = '$name'
	AND `banned` = 1
SQL;

	if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
	}  
	//if we get more than 0, then the server has been banned
	if ($result->num_rows>0)
	{
		return true;
	}
	//otherwise, its good!
	return false;
}
?>