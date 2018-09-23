<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<!--
	Filename:	index.php
	Author:		Mike Blackmore
	Background: Play Your Cards Right.
				Inspiration from the following episode of Bruce's PYCR : http://www.youtube.com/watch?v=9V6mCxl7Up4
	Created:	08/02/2011 - Initial script
	Modified:	
	
	
-->

<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

	<head>
		<title>Assigment 3 - Play Your Cards Right</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Author" content="Mike Blackmore"/>

		<style type="text/css">
		
		body{font-family: Verdana, Arial, sans-serif; font-size: 100%; color: #990099; background-color: #eeeeee; width: 520px;}
			
		h1 { font-size: 150%; color: black; background-color: inherit; text-align:center} 
		h2 { font-size: 130%; color: #000066; background-color: inherit; text-align:center}
		
		p  { color:black; width:720px;}
		p.indent  {font-size: 80%; margin-left:20px; width:480px; background-color:#b0e0e6;text-align:left }
		span.i{margin-left:20px}
		span.ii{margin-left:40px}
		p.left {text-align: left; margin-left:20px;}
		p.centered{text-align:center;margin-top:0px;margin-bottom:0px;padding:0px;}
		
		</style>
		<!-- Google Analytics -->
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-20775469-2']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>		
	</head>
	
	<body>
		<!-- Display header information -->
		
<!--		<img src="./cards/Joker.png" height="129" width="105" alt="Joker" /> -->
		<h1>Mike Blackmore - Assignment 3</h1>
		<h2>Play Your Cards Right</h2>
<!--		<img src="./cards/Joker.png" height="129" width="105" alt="Joker" /> -->
		
	<?php

	function CompareCards($Option, $Bet)
		{
		sscanf($_SESSION['Shoe'][$_SESSION['Card']],"%d%s", $current, $suit);	// Strip the suit info from the card details e.g. 4clubs = 4
		sscanf($_SESSION['Shoe'][$_SESSION['Card']+1],"%d%s", $next, $suit);
	
		if ( $current == $next )												// Must be a pair - automatic lose
			{
			$_SESSION['Message']="<p>Lose: You don't get anything for a pair, not in this game!</p>";
			$_SESSION['Bank']=$_SESSION['Bank']-$Bet;
			return 0;
			}
		else if ( $next > $current  )											// Next card is higher
			{
			if ( $Option == "Higher" )
				{
				$_SESSION['Message']="<p>Higher: Win</p>";
				$_SESSION['Bank']=$_SESSION['Bank']+$Bet;
				return 1;
				}
			else
				{
				$_SESSION['Message']="<p>Higher: Lose</p>";
				$_SESSION['Bank']=$_SESSION['Bank']-$Bet;
				return 0;
				}
			}
		else																	// Next card is lower
			{
			if ( $Option == "Lower" )
				{
				$_SESSION['Message']="<p>Lower: Win</p>";
				$_SESSION['Bank']=$_SESSION['Bank']+$Bet;
				return 1;
				}
			else
				{
				$_SESSION['Message']="<p>Lower: Lose</p>";
				$_SESSION['Bank']=$_SESSION['Bank']-$Bet;
				
				return 0;
				}
			}
			
		}
		
	//---Main-----------------------------------------------------------------------------------------------------------------------------------------

		session_start();														// Using sessions to retain values between posts
	
		if ( $_POST['Player'] )													// Has player name been posted? If yes, it is the start of a game
		{
			$_SESSION['Player']=substr(trim($_POST['Player']),0,20);			// Store players name is session variable (max 20 chars)
																				// Default Name
			if  ( $_SESSION['Player'] == "" || $_SESSION['Player'] == " " || $_SESSION['Player'] == "Enter name" ) $_SESSION['Player']="Player";
		
			$_SESSION['Change']=TRUE;											// Set Change Card Flag
			$_SESSION['Card']=0;												// Number of cards already drawn
			$_SESSION['Top']=8;													// Next card on deck after dealing from the start
			$_SESSION['Bank']=250;												// Starting Money
		}
		else if ( $_POST['Higher'] )											// Player Chose Higher
			{
			CompareCards("Higher", $_POST['bet']); 
			$_SESSION['Card']++;
			$_SESSION['Change']=TRUE;											// Reset Change Card Flag
			}
		else if ( $_POST['Lower'] )												// Player Chose Lower
			{
			CompareCards("Lower", $_POST['bet']);
			$_SESSION['Card']++;
			$_SESSION['Change']=TRUE;											// Reset Change Card Flag
			}
		else if ( $_POST['Change'] )											// Player elected to Change Card
			{
			$Current=$_SESSION['Card'];
			$_SESSION['Shoe'][$Current]=$_SESSION['Shoe'][$_SESSION['Top']];	// Replace card with next off the pile
			$_SESSION['Top']++;													// Move to next card in the shoe for next change
			$_SESSION['Change']=FALSE;
			}
	//-End of $_POST validation-----------------------------------------------------------------------------------------------------
		
		if ( !$_SESSION['Player'] )												// If Name does not have a value, need to start a new game.
			{																	// Display Game Rules
			echo '<p class="indent"><strong>How to Play:</strong><br /><br />
			        <span class="i">1. The aim of the game is to win as much money as possible.</span><br /><br />
					<span class="i">2. You are given <strong>&pound;250</strong> at the start of the game to bet.</span><br /><br />
					<span class="i">3. You win your stake if you correctly pick <strong>Higher</strong> or <strong>Lower</strong>.</span><br /><br />
					<span class="i">4. An Ace is <strong>higher</strong> than a King.</span><br /><br />
					<span class="i">5. You get nothing for a <strong>pair</strong> "Not in this game".</span><br /><br />
					<span class="i">6. Play continues until all cards are drawn or...</span><br /><br />	
					<span class="i">7. You lose all your money.</span><br /><br />
					<span class="i">8. To see the <strong>High Scores</strong> click <a href="./hiscores.php">Here</a></span><br /><br /></p>
					<form method="post" action="index.php" ><p>Name: 										
					<input type="text" value="Enter name" name="Player" /><input type="submit" value="Play!" /></p>
					</form>';
			
			// Create Cards 
			$suit=array("Hearts","Spades","Clubs","Diamonds");
			for ( $loop1=0 ; $loop1<4 ; $loop1++ )
				{
				for ( $loop2=2 ; $loop2<15 ; $loop2++ )
					{
					if ( $loop1==1 && $loop2==14 )	// Alternate Ace of Spades ":o) 
						{
						$ace=rand(0,2);
						if		( $ace==0 ) $pack[]=$loop2.$suit[$loop1];
						else if ( $ace==1 ) $pack[]=$loop2."Spadesb";
						else if ( $ace==2 ) $pack[]=$loop2."Spadesc";
						}
					else $pack[]=$loop2.$suit[$loop1];
					}
				}

			// Now shuffle the pack
			shuffle($pack);

			// Finally load the pack into a $_SESSION Array			
			for ( $loop=0 ; $loop<52 ; $loop++ )
				{
				$_SESSION['Shoe'][$loop]=$pack[$loop];
				}
			}
		else																	// Must be in a game already
			{
			// Deal Cards Starting with
			// Top Row
			if ( $_SESSION['Card'] < 6 )  printf("<p><img src=\"./cards/Hidden.png\" height=\"110\" width=\"90\" alt=\"?\" /> ");
			else						  printf("<p><img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][6]);
				
			if ( $_SESSION['Card'] < 7 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /></p>");
			else						  printf("<img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /></p>",$_SESSION['Shoe'][7]);
								
			// Middle Row		
			if ( $_SESSION['Card'] < 3 )  printf("<p><img src=\"./cards/Hidden.png\" height=\"110\" width=\"90\" alt=\"?\" /> ");
			else						  printf("<p><img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][3]);
				
			if ( $_SESSION['Card'] < 4 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /> ");
			else						  printf("<img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][4]);
				
			if ( $_SESSION['Card'] < 5 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /> ");
			else						  printf("<img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][5]);
				
			if ( $_SESSION['Card'] < 6 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /></p>");
			else						  printf("<img src=\"./cards/Hidden.png\" height=\"110\" width=\"90\" alt=\"?\" /></p>");//,$_SESSION['Shoe'][6]);
				
			// Bottom Row
										  printf("<p><img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][0]);
				
			if ( $_SESSION['Card'] < 1 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /> ");
			else						  printf("<img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][1]);
				
			if ( $_SESSION['Card'] < 2 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /> ");
			else						  printf("<img src=\"./cards/%s.png\" height=\"110\" width=\"90\" alt=\"?\" /> ",$_SESSION['Shoe'][2]);
				
			if ( $_SESSION['Card'] < 3 )  printf("<img src=\"./cards/Blue.png\" height=\"110\" width=\"90\" alt=\"?\" /></p>");
			else						  printf("<img src=\"./cards/Hidden.png\" height=\"110\" width=\"90\" alt=\"?\" /></p>");//,$_SESSION['Shoe'][3]);
								
			// Any Mony Left?
			if ( $_SESSION['Bank'] )
				{
				// Display Control Form
				if ( $_SESSION['Card'] == 0 || $_SESSION['Card'] == 3 || $_SESSION['Card'] == 6 )
					{
					// Option to change card ?
					if ( $_SESSION['Change'] )
						{
						printf("<form method=\"post\" action=\"index.php\" ><p>Bank: &pound;%d Bet:<select name=\"bet\">",$_SESSION['Bank']);
							
						for ( $bet=$_SESSION['Bank']; $bet>0; $bet=$bet-50 )
							{
							// Display bet values in £50 
							printf("<option value=\"%d\">%d</option>\n",$bet, $bet);
							} 
							
						printf("</select><input type=\"submit\" name=\"Higher\" value=\"Higher\" /><input type=\"submit\" name=\"Lower\" value=\"Lower\" /><input type=\"submit\" name=\"Change\" value=\"Change\" />
								</p></form>");
						printf("%s",$_SESSION['Message']);
						}
					else 
						{
						printf("<form method=\"post\" action=\"index.php\" ><p>Bank: &pound;%d Bet:<select name=\"bet\">",$_SESSION['Bank']);
							
						for ( $bet=$_SESSION['Bank']; $bet>0; $bet=$bet-50 )
							{
							// Display bet values in £50 
							printf("<option value=\"%d\">%d</option>\n",$bet, $bet);
							} 
							
						printf("</select><input type=\"submit\" name=\"Higher\" value=\"Higher\" /><input type=\"submit\" name=\"Lower\" value=\"Lower\" />
								</p></form>");
						printf("%s",$_SESSION['Message']);
						}
					}
				else if ( $_SESSION['Card'] < 7 )
					{
					printf("<form method=\"post\" action=\"index.php\" ><p>Bank: &pound;%d Bet:<select name=\"bet\">",$_SESSION['Bank']);
							
					for ( $bet=$_SESSION['Bank']; $bet>0; $bet=$bet-50 )
						{
						// Display bet values in £50 
						printf("<option value=\"%d\">%d</option>\n",$bet, $bet);
						} 
							
					printf("</select><input type=\"submit\" name=\"Higher\" value=\"Higher\" /><input type=\"submit\" name=\"Lower\" value=\"Lower\" />
							</p></form>");
					printf("%s",$_SESSION['Message']);
					}
				}	
			else
				{
				printf("<form method=\"post\" action=\"index.php\" ><p>Game Over: You Lost All Your Money!<input type=\"submit\" value=\"Replay\" /></p></form>");
				printf("%s",$_SESSION['Message']);
				
				session_destroy();  
				}
					
			if ( $_SESSION['Card'] == 7 )
				{
				if ( $_SESSION['Bank'] )
					{
					printf("<form method=\"post\" action=\"index.php\" ><p>%s has won &pound;%d <input type=\"submit\" value=\"Replay\" /></p></form>",$_SESSION['Player'],$_SESSION['Bank']);
					// Write score into database - Could have tested if score above 10th highest, but wanted to store all successful games for interest of who plays ;-)
					
					$debug=FALSE;	// TRUE displays additional messages. set to FALSE to turn off messages
			
					// Link to the dbms server
					$link=mysql_connect("mblackmoredb.bimserver2.com","mblackmoredb","H4ck3r");
					if ( $link )
						{
						if ( $debug ) echo "<p>MySQL Link ID: ".$link."</p>";
						}
					else
						{
						echo "<p class=\"error\">Error:MySQL connection failed</p>";
						exit;
						}
				
					// Connect to mblackmoredb database
					$result=mysql_select_db("mblackmoredb", $link);		
					if ( $result )
						{
						if ( $debug ) echo "<p>Database Connection: OK</p>";
						}
					else
						{
						echo "<p class=\"error\"><strong>ERROR:</strong> Database connection failed</p>";
						exit;
						}
			
					// Query to insert score into the the PYCR table
					$sql="INSERT INTO `mblackmoredb`.`PYCR` (`score`, `name`) VALUES ('".$_SESSION['Bank']."', '".$_SESSION['Player']."');";
					if ( $debug )
						{
						printf("<p>%s</p>",$sql);
						}
						
					$result=mysql_query($sql);
					if ( $result )
						{
						if ( $debug ) echo "<p>Database Update: OK</p>";
						}
					else
						{
						echo "<p class=\"error\"><strong>ERROR:</strong> Database update failed</p>";
						exit;
						}
						
					$result=mysql_close($link);	
					if ( $result )
						{
						if ( $debug ) echo "<p>Database Closed: OK</p>";
						}
					else
						{
						echo "<p class=\"error\"><strong>ERROR:</strong> Database close failed</p>";
						exit;
						}
						
					session_destroy();
					}
				}
			}					
	?>
		
		<p><a href="http://validator.w3.org/check?uri=referer"><img
				src="http://www.w3.org/Icons/valid-xhtml10-blue"
				alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a></p>	
	</body>
</html>
