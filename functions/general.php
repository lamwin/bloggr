<?php

function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function display($string){
	return html_entity_decode($string);
}

function output_errors($errors){
	foreach($errors as $error){
		echo '<div class="alert small callout" data-closable="" style="overflow: auto;">
			  <p style="float: left; margin: 0;">'.ucfirst($error).'</p>
			  <span aria-hidden="true" style="cursor: pointer; float: right" data-close="">&times;</span>
			</div>';
	}
}

function flashMessage($message){
	echo '<div class="success small callout" data-closable="" style="overflow: auto;">
			  <p style="float: left; margin: 0;">'.$message.'</p>
			  <span aria-hidden="true" style="cursor: pointer; float: right" data-close="">&times;</span>
			</div>';
}

function flashAlert($message){
	echo '<div class="alert small callout" data-closable="" style="overflow: auto;">
			  <p style="float: left; margin: 0;">'.$message.'</p>
			  <span aria-hidden="true" style="cursor: pointer; float: right" data-close="">&times;</span>
			</div>';
}

function logged_in_redirect(){
	if(Session::exists(Config::get('session/session_name'))){
		Redirect::to('dashboard.php');
	}
}

function email($to, $subject, $body){
	$mail = new PHPMailer;

	$mail->isSMTP();
	$mail->SMTPAuth = true;
	//$mail->SMTPDebug = 2;

	$mail->Host = 'smtp.gmail.com';
	$mail->Username = 'lahmdev@gmail.com';
	$mail->Password = 'Dance4life';
	$mail->Port = 587;
	$mail->SMTPSecure = 'tsl';

	$mail->isHTML(true);

	$mail->From = 'lahmdev@gmail.com';
	$mail->FromName = 'Lahm Nguyen';
	$mail->addReplyTo('lahmdev@gmail.com', 'Reply Address:');
	$mail->addAddress($to);

	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $body;

	$mail->send();
}

function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
					unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}
 
?>

