<?php
require('config.php');
// link to the font file no the server
$fontname = 'font/arial.ttf';
// controls the spacing between text
$i=30;

function create_image($server){
		$i=30;
		global $fontname;	
			

			// define the base image that we lay our text on
			$im = imagecreatefromjpeg("bg.jpg");
			
			// setup the text colours
			$color['white'] =  imagecolorallocate($im, 255, 255, 255);
			$color['grey'] = imagecolorallocate($im, 54, 56, 60);
			$color['green'] = imagecolorallocate($im, 50, 205, 50);
			$color['red'] = imagecolorallocate($im, 255, 0, 0);
			$color['yellow'] = imagecolorallocate($im, 255, 255, 0);
			// this defines the starting height for the text block
			$y = 10;
			 
		// loop through the array and write the text	
		foreach ($server as $value){
			// center the text in our image - returns the x value
			$txtclr = $color[$value['color']];
			if (strpos($value['name'], "Online") !== false) {
				$txtclr = $color['green'];
			}
			if (strpos($value['name'], "Offline") !== false) {
				$txtclr = $color['red'];
			}
			imagettftext($im, $value['font-size'], 0, 20, $y+$i, $txtclr, $fontname,$value['name']);
			// add 32px to the line height for the next text block
			$i = $i+32;	
			
		}
			// create the image file
			header('Content-type: image/png'); //set a header so the PHP script can be linked as an image
			imagepng($im); //create and display the image
			imagedestroy( $im ); //destroy to save memory
						
		return $file;	
}
			//default info if no server is specified
			$server = array(
			
				array(
					'name'=> "Unknown", 
					'font-size'=>'27',
					'color'=>'white'),
				array(
					'name'=> "IP: "."0.0.0.0", 
					'font-size'=>'16',
					'color'=>'white'),
						
				array(
					'name'=> "Map: "."Unknown",
					'font-size'=>'13',
					'color'=>'white'),
					
				array(
					'name'=> "Players: "."0/0",
					'font-size'=>'13',
					'color'=>'white'
					),
				array(
					'name'=> "Game Mode: "."Unknown",
					'font-size'=>'13',
					'color'=>'white'
					),
				array(
					'name'=> "Server Status: "."Offline",
					'font-size'=>'13',
					'color'=>'white'
					)	
					
			);
			//check if GET vars are set at all
			if (isset($_GET) && !empty($_GET))
			{
				//check if IP is set
				if (isset($_GET['ip']) && !empty($_GET['ip']))
				{
					//check if script is using mysql mode
					if ($use_mysql)
					{
						//if it is, then lookup the server info using the mysql function (see 'config.php')
						$server = getServer($db, $_GET['ip']);
					}
					else {
						//we're not using mysql mode, so lets check for some more vars
						if (isset($_GET['name']) && !empty($_GET['name']))
						{
							//name is set, continue on
							if (isset($_GET['ip']) && !empty($_GET['ip']))
							{
								//ip is set, continue on
								if (isset($_GET['map']) && !empty($_GET['map']))
								{
									//map is set, continue on
									if (isset($_GET['curplayers']) && !empty($_GET['curplayers']))
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
																//make sure server status is online or offline, then put all the info into the array and display it!
																$server = array(
																	array(
																		'name'=> $_GET['name'], 
																		'font-size'=>'27',
																		'color'=>'white'),
																	array(
																		'name'=> "IP: ".$_GET['ip'], 
																		'font-size'=>'16',
																		'color'=>'white'),
																			
																	array(
																		'name'=> "Map: ".$_GET['map'],
																		'font-size'=>'13',
																		'color'=>'white'),
																		
																	array(
																		'name'=> "Players: ".$_GET['curplayers']."/".$_GET['maxplayers'],
																		'font-size'=>'13',
																		'color'=>'white'
																		),
																	array(
																		'name'=> "Game Mode: ".$_GET['gamemode'],
																		'font-size'=>'13',
																		'color'=>'white'
																		),
																	array(
																		'name'=> "Server Status: ".$_GET['serverstatus'],
																		'font-size'=>'13',
																		'color'=>'white'
																		)	
																);
															}
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
// run the script to create the image
$filename = create_image($server);

?>