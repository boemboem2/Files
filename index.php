<?php
	include("classes/vote.class.php");
	$v = new VoteClass();
	
	if($v->isInstalled()==0) {
		header("location: install");
	}
	$server_name = $v->getSetting('server_name');
	if(!isset($_GET['step']) || $_GET['step'] == 1){
		$v->getHeader($server_name);
?>
		
				<section id="main-content">
					<div id="guts">		
						<?php 
						$time = $v->getVoteTime();
						echo '<h2>Step 1</h2>';
						if($time == -1){ ?>
								<p>
									Why should you vote for us? If you enjoy the server, and want the server to continue running with a good amount of activity, you should vote daily so we get higher up the toplists. By getting higher up these lists, people are more likely to join, thus making our server more and more popular!
									<br><BR>
									<font color="red"><B>Reminder</B>: Your account can only vote for rewards once 12 hours!</font>
								</p> 
								<div class="votelinks" >
									<ul>
										<?php $v->echoVotingButtons(); ?>
									</ul>
								</div>

								<form style="text-align: center; " target="" method="GET">
									<input type="hidden" name="step" value="2" />
									<input class="button" type="submit" value="Continue" id="stepbutton" />
								</form>
						<?php
							} else {
								$v->showTimeLeft($time);
							}
						?>
					</div>
				</section>
				
			<?php } else if ($_GET['step'] == 2){
				$v->getHeader($server_name);
	?><section id="main-content">
	<div id="guts">		
		<?php 
			if($v->hasVotedAllLinks()){
				$time = $v->getVoteTime();
				echo '<h2>Step 2</h2>';
				if($time == -1){ ?>
					<p>Please Choose a reward and enter your in game name below.</p> 
					<form style="text-align: center;" target="" method="GET">
						<table id="rewards" cellspacing="0">
							<thead>
								<tr>
									<th></th>
									<th>Image</th>
									<th>Name</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<?php $v->echoRewards(); ?>
							</tbody>
						</table>
						<input type="hidden" name="step" value="3" /><br/>
						<p><label for="username">Please input your username below:</label></p> 
						<input type="text" value="" name="username" class="textField" required="required" maxlength="12" placeholder="Username"/><br/>
						<input class="button" type="submit" value="Continue" id="stepbutton" />
					</form>
				<?php	
				} else {
					$v->showTimeLeft($time);
				}
			} else {
				echo '<h2>Step 2</h2>';
				echo 'You havent voted on all the links. Please click <a href="./">here</a> to go back.';						
			} ?>
	</div>
</section>
			
			<?php } else if ($_GET['step'] == 3){			
				$v->getHeader($server_name);
				echo '<section id="main-content"><div id="guts">';
				echo '<h2>Step 3</h2>';
				if($v->hasVotedAllLinks()){
					$UsernameVoted = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM `has_voted` WHERE UNIX_TIMESTAMP(NOW()) < UNIX_TIMESTAMP(time) + 43200 AND `username` = '".mysql_real_escape_string($_GET['username'])."'"));
					if($UsernameVoted[0] == 1) {
						echo "<font color='red'>Your account has already voted once in the past 12 hours.</font><br />";
						echo "You can <a href='#?step=2' style='font-weight: bold;'>go back</a> &amp; use the vote on another account, and you can just keep the vote &amp; use it later.";
					} else {
						$time = $v->getVoteTime();
						if($time == -1){
							echo "<h3>Congratulations!</h3><br />";
							$v->setAsVoted($_GET['rewards'], $_GET['username']);
							echo '<p>You\'ve now successfully voted, if you want to claim your Voting Reward then, login in-game &amp; do <b>::claim</b>.<br />
							If you don\'t receive your reward, report this to the Server Staff Team.</p>';
						} else {
							$v->showTimeLeft($time);
						}
					}
				} else {
					echo 'You haven\'t voted on all the links. Please click <a href="./">here</a> to go back.';
				}
				echo '</div></section>';

			} ?>
			<footer>
				<a href="http://www.gamingtoplist.net/"><img src="http://gamingtoplist.net/Themes/New/images/logo.png"></a>
			</footer>
		</div>

		
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js'></script>
		<script type='text/javascript' src='js/jquery.ba-hashchange.min.js'></script>
		<script type="text/javascript" src="js/jquery.noty.js"></script>
		<script type='text/javascript' src='js/script.js'></script>

	</body>
</html>