<!-- header.tpl-->
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	
	<title>Dev 2.4</title>
	
	<!-- Latest compiled and minified CSS -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
	<link href="/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Optional theme -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">-->
	<link href="/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet">
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
    <link href="http://getbootstrap.com/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">
    <link href="/dev2.4/inc/fix.css" rel="stylesheet">
	
</head>
<body>
	<nav class="navbar navbar-default"><!-- navbar-fixed-top  -->
	  <div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="index.php">COIMS</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <ul class="nav navbar-nav">
			<!-- <li class="active"><a href="#">Personal <span class="sr-only">(current)</span></a></li>-->
			<li class="dropdown<%IF_1(($id=2)OR($id=3)OR($id=4)){ active}ELSE{}%>">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="/dev2.4/img/user-3.png" width="20" height="20" alt="Personal"> Personal <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="info.php">Info</a></li>
				<li><a href="group.php">Groups</a></li>
				<li><a href="user.php">Users</a></li>
			  </ul>
			</li>
			<li class="dropdown<%IF_2(($id=5)OR($id=6)){ active}ELSE{}%>">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="/dev2.4/img/triangle-1.png" width="20" height="20" alt="Patches"> Patches <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="patch_create.php">Create</a></li>
				<li><a href="patch_search.php">Search</a></li>
				<!--<li><a href="#">Something else here</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="#">Separated link</a></li>
				<li role="separator" class="divider"></li>
				<li><a href="#">One more separated link</a></li>-->
			  </ul>
			</li>
			<li<%IF_3(($id=7)){ class="active"}ELSE{}%>><a href="direction.php"><img src="/dev2.4/img/distort.png" width="20" height="20" alt="Direction"> Directions</a></li>
		  </ul>
		  <form class="navbar-form navbar-left" id="search" action="search.php">
			<div class="form-group">
			  <input type="text" class="form-control" placeholder="Search" id="search_string" name="search_string">
			</div>
			<button type="submit" class="btn btn-default">Search</button>
		  </form>
		  
		  <%IF_4(($user_id=0)){
		  <form class="navbar-form navbar-left" method="post">
			<div class="form-group">
			  <input type="text" class="form-control" placeholder="Login" id="auth_name" name="auth_name">
			  <input type="password" class="form-control" placeholder="Password" id="auth_pass" name="auth_pass">
			  <input type="hidden" id="action_id" name="action_id" value="1">
			</div>
			<button type="submit" class="btn btn-default" id="submit_button" name="submit_button">Log in</button>
		  </form>
		  <!--
		  <p class="navbar-text"><a href="credential.php?action_id=2" class="navbar-link">Create account</a><br /><a href="credential.php?action_id=4" class="navbar-link">Forget password?</a></p>
		  -->
		  }ELSE{
		  <a class="btn btn-default navbar-btn" href="?loguot=1">Log out</a>
		  <p class="navbar-text navbar-right">Signed in as <a href="info.php" class="navbar-link">{user_nm}</a></p>
		  }%>
		  
		  <ul class="nav navbar-nav navbar-right">
		    <li class="dropdown<%IF_5(($id=10)OR($id=11)){ active}ELSE{}%>">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="/dev2.4/img/cube-2.png" width="20" height="20" alt="Objects"> Objects <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="object_create.php">Create</a></li>
				<li><a href="object_search.php">Search</a></li>
			  </ul>
			</li><li class="dropdown<%IF_6(($id=12)OR($id=13)){ active}ELSE{}%>">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="/dev2.4/img/cube-1.png" width="20" height="20" alt="Classes"> Classes <span class="caret"></span></a>
			  <ul class="dropdown-menu">
				<li><a href="class_create.php">Create</a></li>
				<li><a href="class_search.php">Search</a></li>
			  </ul>
			</li>
			<!--
			<li class="dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Algorythms <span class="caret"></span></a>
			 <ul class="dropdown-menu">
				<li><a href="#">Create</a></li>
				<li><a href="#">Search</a></li>
			  </ul>
			</li>
			-->
			<li><a href="#"><img src="/dev2.4/img/info.png" width="20" height="20" alt="Help"> Help</a></li>
		  </ul>
		  
		</div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
<!-- /header.tpl-->