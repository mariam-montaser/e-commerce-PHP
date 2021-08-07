
<?php

    //ob_start();//output buffring start (use to handle header errors)
    //ob_start("ob_gzhandler"); 

ob_start();
    
    session_start();

  	if (isset($_SESSION['username'])) {

      $pageTitle = 'Dashboard';
  		
  		include 'intial.php';
  		//echo "welcome" . ' '. $_SESSION['username'];
      // start dashboard page
      $usersNum = 5; //number of latest users

      $latestUsers = getLatest('*', 'name', 'userID', $usersNum);// latest users array

      $itemsNum = 4; //number of latest items

      $latestItems = getLatest('*', 'items', 'itemID', $itemsNum);// latest items array

      $commentsNum = 3; //number of latest comments

      ?>

      <div class="stat-holder text-center">
        <div class="container">
          <h1 class="text-center">Dashboard</h1>
          <div class="row">
            <div class="col-md-3">
              <div class="stat st-members">
                <i class="fa fa-users"></i>
                <div class="info">
                  Total Members
                  <span><a href="members.php"><?php echo countItems('userID', 'name'); ?></a></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-pending">
                <i class="fa fa-user-plus"></i>
                <div class="info">
                  Pending Members
                  <span><a href="members.php?do=Manage&page=Pending"><?php echo checkItem('regstatus', 'name', 0) ?></a></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-items">
                <i class="fa fa-tag"></i>
                <div class="info">
                  Total Items
                  <span><a href="items.php"><?php echo countItems('itemID', 'items') ?></a></span>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="stat st-comments">
                <i class="fa fa-comments"></i>
                <div class="info">
                  Total Comments
                  <span><a href="comments.php"><?php echo  countItems('c_id', 'comments') ?></a></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="latest">
        <div class="container">
          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-users"></i>Latest <?php echo $usersNum ?> Registerd Users
                  <span class="toggle-info pull-right"><i class="fa fa-minus fa-lg"></i></span>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled latest-users">
                    <?php 
                      // loop to get users from array
                    if (!empty($latestUsers)) {
                      foreach ($latestUsers as $user) {

                        echo "<li>";
                          echo $user['username'];
                          echo "<a href='members.php?do=Edit&userid=" . $user['userID'] . "'>
                                  <span class='btn btn-success pull-right'>
                                    <i class='fa fa-edit'></i>Edit</a>
                                  </span>";
                          if ($user['regstatus'] == 0) {
                            echo "<a href='members.php?do=Activate&userid=" . $user['userID'] . "'>
                                  <span class='btn btn-info pull-right'>
                                    <i class='fa fa-check'></i>Activate</a>
                                  </span>";
                          }
                        echo "</li>";
                      }
                    } else {
                      echo "There's No Record To Show";
                    }
                  ?>
                  </ul>
                </div>
              </div><!--latest users-->
            </div>
            <div class="col-sm-6"> 
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-tag"></i>Latest <?php echo $itemsNum ?> Items
                  <span class="toggle-info pull-right"><i class="fa fa-minus fa-lg"></i></span>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled latest-users">
                    <?php
                    if (!empty($latestItems)) {
                      foreach ($latestItems as $item) {
                        
                        echo "<li>";
                          echo $item['name'];
                          echo "<a href='items.php?do=Edit&itemid=" . $item['itemID'] . "'>
                          <span class='btn btn-success pull-right'><i class='fa fa-edit'></i>Edit</span>
                          </a>";
                          if ($item['approve'] == 0){
                            echo "<a href='items.php?do=Approve&itemid=" . $item['itemID'] . "'>
                          <span class='btn btn-info pull-right'><i class='fa fa-check'></i>Approve</span>
                          </a>";
                          }
                        echo "</li>";
                      }
                    } else {
                      echo "There's No Record To Show";
                    }

                  ?>
                  </ul>
                </div>
              </div><!--latest items-->
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="panel panel-default">
                <div class="panel-heading"> <!--latest comments-->
                  <i class="fa fa-comments-o"></i>Latest <?php echo $commentsNum; ?> Comments
                  <span class="toggle-info pull-right"><i class="fa fa-minus fa-lg"></i></span>
                </div>
                <div class="panel-body">
                  <?php
                    $stmt = $con->prepare("SELECT comments.*, name.username AS member
                                            FROM comments
                                            INNER JOIN name
                                            ON name.userID = comments.user_id
                                            ORDER BY c_id DESC
                                            LIMIT $commentsNum");
                    $stmt->execute();

                    $comments = $stmt->fetchAll();

                    if (!empty($comments)) {

                      foreach ($comments as $comment) {
                        echo "<div class='comment-box'>";
                          echo "<a href='members.php?do=Edit&userid=" . $comment['user_id'] . "' class='member-n'>" . $comment['member'] . "</a>";
                          echo "<p class='member-c'>" . $comment['comment'] . "</p>";
                        echo "</div>";
                      }
                    } else {

                      echo "There's No Record To Show";
                    }

                   ?>
                </div>
              </div>
            </div>

            <div class="col-sm-6"> 
              <div class="panel panel-default">
                <div class="panel-heading">
                  <i class="fa fa-comments"></i>Latest  Items
                  <span class="toggle-info pull-right"><i class="fa fa-minus fa-lg"></i></span>
                </div>
                <div class="panel-body">
                  <ul class="list-unstyled latest-users">
                   test
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php 
      // end dashboard page
  		include $tpl . 'footer.php';

  	} else {

  		//echo "you are not autherised";

  		header('location:index.php');

  		exit();
  	} 

    //ob_end_flush();//
?>