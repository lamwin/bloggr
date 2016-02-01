<div class="top-bar">
  <div class="row">
    <div class="large-12 columns" style="padding-right: 0; padding-left: 0;">
      <div class="top-bar-left">
        <ul class="dropdown menu" data-dropdown-menu>
          <li class="menu-text"><a href="index.php">Bloggr</a></li>
        </ul>
      </div>
      <div class="top-bar-right">
        <ul class="menu">
          <?php

            if(isset($user) && $user->isLoggedIn()){

          ?>

              <ul class="dropdown menu" data-dropdown-menu>
                <li><a href="dashboard.php">Dashboard</a></li>
          <?php
              if($user->hasPermission('admin')){
                echo '<li><a href="adminPanel.php">Admin Panel</a></li>';
              }
          ?>
                <li class="has-submenu">
                  <a href="#"><?= $user->data()->username; ?></a>
                  <ul class="submenu menu vertical" data-submenu>
                    <li><a href="/<?= $user->data()->username; ?>"><i class="fa fa-user"></i>&nbsp;Profile</a></li>
                    <li><a href="settings.php"><i class="fa fa-gears"></i>&nbsp;Settings</a></li>
                    <li><a href="changeprofilepic.php"><i class="fa fa-camera"></i>&nbsp;Profile Picture</a></li>
                    <li><a href="changepassword.php"><i class="fa fa-lock"></i>&nbsp;Change Password</a></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i>&nbsp;Logout</a></li>
                  </ul>
                </li>
              </ul>

          <?php
            }else{
          ?>
              <li><a href="register.php">Sign Up</a></li>
              <li><a href="login.php">Log in</a></li>
          <?php
            }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>