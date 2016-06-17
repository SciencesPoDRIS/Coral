<?php

	$resourceID = $_POST['resourceID'];

	// Get this resource
	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
	// Clear logo field
	$resource->logo = '';

	// Save it
	try {
		$resource->save();
	} catch (Exception $e) {
		echo $e->getMessage();
	}

?>