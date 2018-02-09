				<nav class="navbar navbar-default top-navbar" role="navigation">
						<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="SmtAdmin-Main.php">SafeMinistryTraining.com.au</a>
						</div>
				</nav>
				<!--/. NAV TOP  -->
				<nav class="navbar-default navbar-side" role="navigation">
						<div class="sidebar-collapse">
								<ul class="nav" id="main-menu">

										<li <?php if ($_SESSION['Page']=="Dash"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-Main.php" <?php if ($_SESSION['Page']=="Dash"){ echo "style='color: #ffffff;'";} ?> ><i class="fa fa-dashboard fa-fw fa-2x"></i> Dashboard</a>
										</li>
										<li <?php if ($_SESSION['Page']=="Org"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-OrgSettings.php" <?php if ($_SESSION['Page']=="Org"){ echo "style='color: #ffffff;'";} ?> ><i class="fa fa-gears fa-fw fa-2x"></i> General Settings</a>
										</li>
										<li <?php if ($_SESSION['Page']=="Wwcc"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-WwccSettings.php" <?php if ($_SESSION['Page']=="Wwcc"){ echo "style='color: #ffffff;'";} ?> ><i class="fa fa-puzzle-piece fa-fw fa-2x"></i> Policy Settings</a>
										</li>
										<li <?php if ($_SESSION['Page']=="Invite"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-Invite.php" <?php if ($_SESSION['Page']=="Invite"){ echo "style='color: #ffffff;'";} ?> ><i class="fa fa-users fa-fw fa-2x"></i> Invite Trainees</a>
										</li>
										<li <?php if ($_SESSION['Page']=="BuyCredits"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-BuyCredits.php"><i class="fa fa-usd fa-fw fa-2x"></i> Buy Credits</a>
										</li>
												<li <?php if ($_SESSION['Page']=="Elvanto"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-Elvanto.php" <?php if ($_SESSION['Page']=="Wwcc"){ echo "style='color: #ffffff;'";} ?> ><i class="fa fa-link fa-fw fa-2x"></i> Elvanto Connection</a>
										</li>
										<li <?php if ($_SESSION['Page']=="Log"){ echo "style='background-color: #203852;'";} ?> >
												<a href="SmtAdmin-ActivityLog.php" <?php if ($_SESSION['Page']=="Log"){ echo "style='color: #ffffff;'";} ?> ><i class="fa fa-table fa-fw fa-2x"></i> ActivityLog</a>
										</li>
										<li>
												<a href="SmtAdmin-Logout.php"><i class="fa fa-arrow-right fa-fw fa-2x"></i> Logout</a>
										</li>
								</ul>

						</div>

				</nav>
