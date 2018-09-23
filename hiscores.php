<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<!--
	Filename:	hiscores.php
	Author:		Mike Blackmore
	Background: Display the top 10 scores stored in the MySQL database.
	Created:	13/02/2011 - Initial script
	Modified:	
-->

<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

	<head>
		<title>Assignment 3 - Play Your Cards Right</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Author" content="Mike Blackmore"/>

		<style type="text/css">
		
		body{font-family: Verdana, Arial, sans-serif; font-size: 100%; color: #990099; background-color: #eeeeee; width: 520px;}
			
		h1 { font-size: 150%; color: black; background-color: inherit; text-align:center} 
		h2 { font-size: 130%; color: #000066; background-color: inherit; text-align:center}
		
		p  { color:black; width:520px;}
		p.error { color:red; }
		p.indent  {font-size: 80%; margin-left:20px; width:480px; background-color:#b0e0e6;text-align:left }
		span.i{margin-left:120px}
		span.ii{margin-left:40px}
		span.centered{margin-left:190px; font-size: 120%}
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
		<!-- End of Google Analytics -->
	</head>
	
	<body>
		<!-- Display header information -->
		
		<h1>Mike Blackmore - Assignment 3</h1>
		<h2>Play Your Cards Right</h2>
		
		<?php

		$debug=FALSE;	// TRUE displays additional messages. set to FALSE to turn off messages
		
		// Link to the dbms server
		$link=mysql_connect("mblackmoredb.bimserver2.com","mblackmoredb","H4ck3r");
		if ( $link )
			{
			if ( $debug ) echo "<p>MySQL Link ID: ".$link."</p>";
			}
		else
			{
			echo "<p class=\"error\">Error:MySQL Connection Failed</p>";
			exit;
			}
			
		// Connect to mblackmoredb database
		$result=mysql_select_db("mblackmoredb", $link);		
		if ( $result )
			{
			if ( $debug ) echo "<p>Database Connection: Success</p>";
			}
		else
			{
			echo "<p class=\"error\"><strong>ERROR:</strong> Database connection failed</p>";
			exit;
			}
		
		// Query to return the Top 10 scores from the PYCR table
		$sql="SELECT score,name FROM PYCR ORDER BY score DESC LIMIT 0,10";
		
		$result=mysql_query($sql);
		
		if ( $result )
		{
			if ( $debug ) echo "<p>Query Success</p>";
			
			echo '<p><span class="centered"><strong>High Scores</strong></span></p>
				
				<table style="color:black" border="0">
					<tr>
						<td style="width:40%"> </td><td align="right"><strong>Score</strong></td><td style="width:10%"> </td><td align="left"><strong>Name</strong></td>
					</tr>';	
			
			while ($row = mysql_fetch_row($result))
			{
				printf("<tr><td> </td><td align=\"right\">%d</td><td> </td><td> %s</td></tr>\n",$row[0], $row[1]);
			}
			mysql_free_result($result);
			echo '</table>
			<form method="post" action="index.php" ><p><input type="submit" value="Back" /></p>
					</form>';
			
		}
		else
			{
			echo '<p class="error"><strong>ERROR:</strong>Query Failed</p>';
			exit;
			}
							
		?>
		
		<p><a href="http://validator.w3.org/check?uri=referer"><img
				src="http://www.w3.org/Icons/valid-xhtml10-blue"
				alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a></p>	
	</body>
</html>
