<?php 
$currentPoints = 0; 
$totalPoints = 0;
$hardPoints = 0; 
?>
<div class="wrap">

	
	<div id="icon-tools" class="icon32"></div>
	<h2>WP Survey And Quiz Tool - Mark</h2>	
	
	<?php if ( isset($successMessage) ){ ?>
		<div class="updated" id="question_added"><?php echo $successMessage; ?></div>
	<?php } ?>
	
	<?php if ( isset($errorArray) && !empty($errorArray) ) { ?>
		<ul class="error">
			<?php foreach($errorArray as $error ){ ?>
				<li><?php echo $error; ?></li>
			<?php } ?>
		</ul>
	<?php } ?>
	
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">	
	<?php if (!empty($result['person'])) { ?>
		<h3>User Details</h3>
		<div class="person">
			<ul>
			   <?php if (isset($result['person']['name'])){ ?>
				<li><b><u>Name</u></b> - <?php echo htmlentities(strip_tags(stripslashes($result['person']['name']))); ?></li>
			   <?php } 
			   		if (isset($result['person']['email'])){
			   ?>	
				<li><b><u>Email</u></b> - <?php echo htmlentities(strip_tags(stripslashes($result['person']['email']))); ?></li>
			   <?php }
				     if (isset($result['person']['phone'])){
				?>
				<li><b><u>Phone</u></b> - <?php echo htmlentities(strip_tags(stripslashes($result['person']['phone']))); ?></li>
				<?php }
					  if (isset($result['person']['heard'])){
				?>
				<li><b><u>Heard Of</u></b> - <?php echo htmlentities(strip_tags(stripslashes($result['person']['heard']))); ?></li>
				<?php }
					  if (isset($result['ipaddress'])){
				?>
				<li><b><u>IP Address</u></b> - <?php echo $result['ipaddress']; ?></li>
				<li><b><u>Hostname</u></b> - <?php echo gethostbyaddr($result['ipaddress']); ?></li> 
				<?php }
					  if (isset($result['person']['address'])){
				?>
				<li><b><u>Address</u></b> - <?php echo nl2br(htmlentities(strip_tags(stripslashes($result['person']['address'])))); ?></li>
				<?php }
					  if (isset($result['person']['notes'])){
				?>
				<li><b><u>Notes</u></b> - <?php echo nl2br(htmlentities(strip_tags(stripslashes($result['person']['notes'])))); ?></li>
				<?php }
				?>
				<li><b><u>Timetaken</u></b> - <?php echo $timeTaken; ?></li>
			</ul>
		</div>
	<?php } ?>
	
	<?php foreach ( $result['sections'] as $section ){ ?>
		<h3><?php echo $section['name']; ?></h3>
		
		<?php foreach ($section['questions'] as $questionKey => $questionArray){ ?>
			<h4><?php print stripslashes($questionArray['text']); ?></h4>
			<?php if ($questionArray['section_type'] == 'multiple'){
					if ( isset($section['answers'][$questionKey]['mark']) && $section['answers'][$questionKey]['mark'] == 'correct' ){
						$currentPoints++;
						$hardPoints++;
					}
					$totalPoints++;	
				?>				
				<b><u>Mark</u></b> - <?php if (isset($section['answers'][$questionKey]['mark'])) { echo $section['answers'][$questionKey]['mark']; } else { echo 'Incorrect'; } ?><br />
				<b><u>Answers</u></b>
				<p class="answer_given">
					<ol>
						<?php foreach ($questionArray['answers'] as $answer){ ?>
							  <li><font color="<?php echo ( $answer['correct'] != 'yes' ) ?  (isset($section['answers'][$questionKey]['given']) &&  in_array($answer['id'], $section['answers'][$questionKey]['given']) ) ? '#FF0000' :  '#000000' : '#00FF00' ; ?>"><?php echo stripslashes($answer['text']) ?></font><?php if (isset($section['answers'][$questionKey]['given']) && in_array($answer['id'], $section['answers'][$questionKey]['given']) ){ ?> - Given<?php }?></li>
						<?php } ?>
					</ol>
				</p>
			<?php } else { 
				?>				
				<b><u>Answer Given</u></b>
				<p class="answer_given" style="background-color : #c0c0c0; border : 1px dashed black; padding : 5px;overflow:auto;height : 200px;"><?php echo nl2br(htmlentities(stripslashes(current($section['answers'][$questionKey]['given'])))); ?></p>
				<p><b>Mark</b> <input type="hidden" name="old_mark[<?php echo $questionKey; ?>]" id="old_mark_<?php echo $questionKey; ?>" value="<?php echo (isset($questionArray['mark']) && ctype_digit($questionArray['mark']) ? $questionArray['mark'] : 0 ); ?>" /> <select name="mark[<?php echo $questionKey; ?>]" class="mark" id="current_mark_<?php echo $questionKey; ?>">
					<?php for( $i = 0; $i <= $questionArray['value']; $i++ ){ 
							if ( $i != 0) { $totalPoints++; }
					?>
							<option value="<?php echo $i; ?>" <?php   if ( isset($questionArray['mark']) && $questionArray['mark'] == $i ){ if ($i != 0){ $currentPoints++; } ?> selected="yes"<?php }?>><?php echo $i; ?></option>
					<?php } ?>
					</select> <b>Comment</b> : <input type="text" name="comment[<?php echo $questionKey; ?>]" value="<?php if ( isset($questionArray['comment']) ){ echo htmlentities($questionArray['comment']); } ?>" /> - <a href="#" class="show_hide_hint">Show/Hide Hint</a></p>
				<div class="hint">
					<h5>Hint</h5>
					<p style="background-color : #c9c9c9;padding : 5px;"><?php echo nl2br(htmlentities(stripslashes($questionArray['hint']))); ?></p>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	<p><font size="+3">Total Points <span id="total_points"><?php echo $currentPoints; ?></span> out of <?php echo $totalPoints; ?></font></p>
	<select name="status">
		<option value="Unviewed" <?php if ($result['status'] == 'Unviewed'){?> selected="yes"<?php } ?>>Unviewed</option>
		<option value="Rejected" <?php if ($result['status'] == 'Rejected'){?> selected="yes"<?php } ?>>Rejected</option>
		<option value="Accepted" <?php if ($result['status'] == 'Accepted'){?> selected="yes"<?php } ?>>Accepted</option>
	</select>
	
	<script type="text/javascript" src="<? echo bloginfo('wpurl'); ?>/wp-content/plugins/wp-survey-and-quiz-tool/javascript/mark.php?mark=<?php echo $hardPoints; ?>"></script>
	
	<input type="hidden" name="overall_mark" id="overall_mark" value="<?php echo $currentPoints; ?>" />
	<input type="hidden" name="total_mark" id="total_mark" value="<?php echo $totalPoints; ?>" />
	<p><input class="button-primary" type="submit" value="Submit"></p>
</div>
</form>