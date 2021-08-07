<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo getTitle(); ?></title>
		<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.min.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>front.css" />
	</head>
	<body>
        <div class="upper-bar">
            <div class="container">
                <?php

                if (isset($_SESSION['user'])) {?>

                <img class="my-img img-thumbnail img-circle" src="images.png" />
                <div class="btn-group my-info">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <?php echo $session ?>
                        <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                        <li><a href ='profile.php'>My Profile</a></li>
                        <li><a href ='newad.php'>New Item</a></li>
                        <li><a href ='profile.php#my-ads'>My Items</a></li>
                        <li><a href ='logout.php'>Logout</a></li>
                    </ul>
                </div>

                <?php

                    // $userStatus = userStatus($session);

                    // if ($userStatus == 1) {// the user is not avtive

                    //     echo "you are not activated yet";
                    // }

                } else {

                ?>
                <a href="login.php">
                    <span class="pull-right">Login/Signup</span>
                </a>
                <?php } ?>
            </div>
        </div>
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-nav">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><?php echo lang('HOMEPAGE') ?></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="app-nav">
                    <ul class="nav navbar-nav navbar-right">
                    <?php
                    //getAll($field, $table, $where = NULL, $and = NULL, $orderBy, $ordering = 'DESC')
                        $cats = getAll('*', 'categories', 'WHERE parent = 0', '', 'ID', 'ASC');
                        //$cats = getCat(); 
                        foreach ($cats as $cat) {
                            echo "<li><a href='categories.php?catid=" . $cat['ID'] . "'>" . $cat['name'] . "</a></li>"; 
                        } 
                     ?>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container -->
        </nav> 
   