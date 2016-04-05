<?php
require('config.php');
if ($use_mysql) //we only use this script in mysql mode.
{
	if (isset($_GET) && !empty($_GET))//let's make sure we've been sent some info to update
	{
		if (isset($_GET['ip']) && !empty($_GET['ip']))
		{
			if (isset($_GET['name']) && !empty($_GET['name']))
			{
				$servid = server_exists($db, $_GET['ip'], $_GET['name']);
				if ($servid > 0)
				{
					if ($api_keys)
					{
						if (isset($_GET['api_key']) && !empty($_GET['api_key']))
						{
							if (!check_api_key($db, $_GET['api_key']))
							{
								die("Invalid API Key Supplied!");
							}
						}
						else {
							die("API Key not set!");
						}
					}
							if (!check_ban($db, $_GET['ip'], $_GET['name']))
							{
								//yay! we exist and aren't banned!
								//check if the rest of the update vars are set.
								//we're using mysql mode, so lets check for some more vars
										//name is set, continue on
										if (isset($_GET['map']) && !empty($_GET['map']))
										{
											//map is set, continue on
											if (isset($_GET['curplayers'])  && (!empty($_GET['curplayers']) || $_GET['curplayers'] >= 0))
											{
												//current players is set, continue on
												if (is_numeric($_GET['curplayers']))
												{
													//let's make sure its a number, then continue
													if (isset($_GET['maxplayers']) && !empty($_GET['maxplayers']))
													{
														//max players is set, continue on
														if (is_numeric($_GET['maxplayers']))
														{
															//let's make sure its a number, then continue
															if (isset($_GET['gamemode']) && !empty($_GET['gamemode']))
															{
																//game mode is set, continue on
																if (isset($_GET['serverstatus']) && !empty($_GET['serverstatus']))
																{
																	//server status is set, continue on
																	if ($_GET['serverstatus'] == "Online" || $_GET['serverstatus'] == "Offline")
																	{
																		echo update_server($db, $_GET['name'], $_GET['ip'], $_GET['map'], $_GET['curplayers'], $_GET['maxplayers'], $_GET['gamemode'], $_GET['serverstatus'], $servid);
																	}
																}
															}
														}
													}
												}
											}
									}
							}
							else {
								die("Oh no! Your server has been banned from our list!");
							}
				}
				else {
					if ($auto_add)
					{
						if (isset($_GET['name']) && !empty($_GET['name']))
						{
							//name is set, continue on
							if (isset($_GET['map']) && !empty($_GET['map']))
							{
								//map is set, continue on
								if (isset($_GET['curplayers']) && (!empty($_GET['curplayers']) || $_GET['curplayers'] >= 0))
								{ 
									//current players is set, continue on
									if (is_numeric($_GET['curplayers']))
									{
										//let's make sure its a number, then continue
										if (isset($_GET['maxplayers']) && !empty($_GET['maxplayers']))
										{
											//max players is set, continue on
											if (is_numeric($_GET['maxplayers']))
											{
												//let's make sure its a number, then continue
												if (isset($_GET['gamemode']) && !empty($_GET['gamemode']))
												{
													//game mode is set, continue on
													if (isset($_GET['serverstatus']) && !empty($_GET['serverstatus']))
													{
														//server status is set, continue on
														if ($_GET['serverstatus'] == "Online" || $_GET['serverstatus'] == "Offline")
														{
															$api_key = "";
															if ($api_keys)
															{
																$api_key = get_api_key();
															}
															echo create_server($db, $_GET['name'], $_GET['ip'], $_GET['map'], $_GET['curplayers'], $_GET['maxplayers'], $_GET['gamemode'], $_GET['serverstatus'], $api_key);
														}
														else {
															die("Invalid Server Status");
														}
													}
													else {
														die("No Server Status");
													}
												}
												else {
													die("Invalid Gamemode");
												}
											}
											else {
												die("Max Players <i>must</i> be a number!");
											}
										}
										else {
											die("Invalid Max Players!");
										}
									}
									else {
										die("Player Count <i>must</i> be a number!");
									}
								}
								else {
									die("Invalid Player Count");
								}
							}
							else {
								die("Invalid Map!");
							}
						}
						else {
							die("You must name your server!");
						}
					}
					else {
						die("Oh no! Your server doesn't exist in our database! Please register on our site!");
					}
				}
			}
			else {
				die("Invalid or No Server Name sent!");
			}
		}
		else {
			die("Invalid or No IP sent!");
		}
	}
	else {
		die("No data sent to server!");
	}
}
else {
	die("This portion of the script has been disabled.");
}
?>