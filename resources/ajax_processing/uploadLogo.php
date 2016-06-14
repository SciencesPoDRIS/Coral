<?php
	//performs attachment upload
	$attachmentName = basename($_FILES['resourceLogo']['name']);
	$exists = 0;

	//verify the name isn't already being used
	// foreach ($attachment->allAsArray() as $attachmentTestArray) {
	// 	if (strtoupper($attachmentTestArray['attachmentURL']) == strtoupper($attachmentName)) {
	// 		$exists++;
	// 	}
	// }

	// if match was not found
	if ($exists == 0){
		$target_path = "logo/" . basename($_FILES['resourceLogo']['name']);
		//note, echos are meant for debugging only - only file name gets sent back
		if(move_uploaded_file($_FILES['resourceLogo']['tmp_name'], $target_path)) {
			// Set to web rwx, everyone else rw
			// This way we can edit the attachment directly on the server
			chmod ($target_path, 0766);
			echo "<div id=\"fileName\">" . $attachmentName . "</div>";
		} else {
		 	header('HTTP/1.1 500 Internal Server Error');
			echo "<div id=\"error\">"._("There was a problem saving your file to ").$target_path._(".  Please ensure your attachments directory is writable.")."</div>";
		}
	}

?>
