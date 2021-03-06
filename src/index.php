<?php
session_start ();
function loginForm() {
	echo '
    <div id="loginform">
    <form action="index.php" method="post">
        <p>Please enter your name to continue:</p>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" />
        <input type="submit" name="enter" id="enter" value="Sign in" />
    </form>
    </div>
    ';
}

if (isset ( $_POST ['enter'] )) {
	if ($_POST ['name'] != "") {
		$_SESSION ['name'] = stripslashes ( htmlspecialchars ( $_POST ['name'] ) );
		$fp = fopen ( "log.html", 'a' );
		fwrite ( $fp, "<div class='msgln'><i>" . $_SESSION ['name'] . " has signed in.</i><br></div>" );
		fclose ( $fp );
	} else {
		echo '<span class="error">Enter a name.</span>';
	}
}

if (isset ( $_GET ['signout'] )) {
	
	// Simple exit message
	$fp = fopen ( "log.html", 'a' );
	fwrite ( $fp, "<div class='msgln'><i>" . $_SESSION ['name'] . " has logged out.</i><br></div>" );
	fclose ( $fp );
	
	session_destroy ();
	header ( "Location: index.php" ); // Redirect the user
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<title>BeeBit - Chat</title>
</head>
<body>
	<?php
	if (! isset ( $_SESSION ['name'] )) {
		loginForm ();
	} else {
		?>
<div id="wrapper">
		<div id="menu">
			<p class="welcome">
				Hello, <b><?php echo $_SESSION['name']; ?></b>
			</p>
			<p class="signout">
				<a id="exit" href="#">Sign Out</a>
			</p>
			<div style="clear: both"></div>
		</div>
		<div id="chatbox"><?php
		if (file_exists ( "log.html" ) && filesize ( "log.html" ) > 0) {
			$handle = fopen ( "log.html", "r" );
			$contents = fread ( $handle, filesize ( "log.html" ) );
			fclose ( $handle );
			
			echo $contents;
		}
		?></div>

		<form name="message" action="">
			<input name="usermsg" type="text" maxlength="100" id="usermsg" size="63" /> <input
				name="submitmsg" type="submit" id="submitmsg" value="Send" />
		</form>
	</div>
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
});

//jQuery Document
$(document).ready(function(){
	//If user wants to end session
	$("#exit").click(function(){
		var exit = confirm("Are you sure you want to sign out?");
		if(exit==true){window.location = 'index.php?signout=true';}		
	});
});

//If user submits the form
$("#submitmsg").click(function(){
		var clientmsg = $("#usermsg").val();
		$.post("post.php", {text: clientmsg});				
		$("#usermsg").attr("value", "");
		loadLog;
	return false;
});

function loadLog(){		
	var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
	$.ajax({
		url: "log.html",
		cache: false,
		success: function(html){		
			$("#chatbox").html(html); //Insert chat log into the #chatbox div	
			
			//Auto-scroll			
			var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
			if(newscrollHeight > oldscrollHeight){
				$("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
			}				
	  	},
	});
}

setInterval (loadLog, 2500);
</script>
<?php
	}
	?>
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
	<script type="text/javascript">
</script>
<h1>Welcome to the open beta of BeeBit, please avoid spamming.</h1>
</body>
</html>
