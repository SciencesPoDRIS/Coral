<?php
		$resourceID = $_POST['resourceID'];

		//get this resource
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

		$resource->updateLoginID 		= $loginID;
		$resource->updateDate			= date( 'Y-m-d H:i:s' );

		$resource->titleText 			= $_POST['titleText'];
		$resource->descriptionText 		= $_POST['descriptionText'];
		$resource->resourceFormatID 	= $_POST['resourceFormatID'];
		$resource->resourceTypeID 		= $_POST['resourceTypeID'];
		$resource->resourceURL 			= $_POST['resourceURL'];
		$resource->resourceAltURL 		= $_POST['resourceAltURL'];
		// @annelhote : Update resource's status
		$resource->resourceStatusID 	= $_POST['resourceStatusID'];
		// @annelhote : Update resource's accessibility
		$resource->accessibility		= $_POST['accessibility'];
		// @annelhote : Update resource's logo
		$resource->logo					= $_POST['resourceLogo'];
		// @annelhote : Update resource's publication status
		$resource->published			= $_POST['published'];
		// @annelhote : Update resource's publication status
		$resource->publicationComment	= $_POST['publicationComment'];
		// @annelhote : Update resource's publication date
		$resource->publicationDate		= $_POST['publicationDate'];

		$isbnarray = json_decode($_POST['isbnOrISSN']);
		$resource->setIsbnOrIssn($isbnarray);

		//to determine status id
		$status = new Status();

		if (((!$resource->archiveDate) || ($resource->archiveDate == '0000-00-00')) && ($_POST['archiveInd'] == "1")) {
			$resource->archiveDate = date( 'Y-m-d' );
			$resource->archiveLoginID = $loginID;
			$resource->statusID = $status->getIDFromName('archive');
		} else if ($_POST['archiveInd'] == "0") {
			//if archive date is currently set and being removed, mark status as complete
			if (($resource->archiveDate != '') && ($resource->archiveDate != '0000-00-00')){
				$resource->statusID = $status->getIDFromName('complete');
			}
			$resource->archiveDate = '';
			$resource->archiveLoginID = '';
		}
		try {
			$resource->save();
		} catch (Exception $e) {
			echo $e->getMessage();
		}


		//update resource relationship (currently code only allows parent)
		//first remove the existing relationship then add it back
		$resource->removeParentResources();

    if (($_POST['parentResourcesID'])){
      $parentResourcesArray = json_decode($_POST['parentResourcesID']);
      foreach($parentResourcesArray as $parentResource) {
        $resourceRelationship = new ResourceRelationship();
        $resourceRelationship->resourceID = $resourceID;
        $resourceRelationship->relatedResourceID = $parentResource;
        $resourceRelationship->relationshipTypeID = '1';  //hardcoded because we're only allowing parent relationships
        try {
          $resourceRelationship->save();
        } catch (Exception $e) {
          echo $e->getMessage();
        }
      }
    }

		// @annelhote : Update resource's language
		$resource->removeResourceLanguages();
		if ($_POST['resourceLanguages']) {
			$resourceLanguagesArray = json_decode($_POST['resourceLanguages']);
			foreach($resourceLanguagesArray as $languageId) {
				$resourceLanguage = new ResourceLanguage();
				$resourceLanguage->resourceId = $resourceID;
				$resourceLanguage->languageId = $languageId;
				try {
					$resourceLanguage->save();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
		}

		// @annelhote : Update ressource's tutos
		// First remove all existing tutos
		$resource->removeResourceTutos();
		// Then save tutos
		if ($_POST['tutoResource']) {
			$resourceTutosArray = json_decode($_POST['tutoResource']);
			foreach($resourceTutosArray as $tuto) {
				$resourceTuto = new ResourceTuto();
				$resourceTuto->resourceID = $resourceID;
				$resourceTuto->name = $tuto->{'name'};
				$resourceTuto->url = $tuto->{'url'};
				try {
					$resourceTuto->save();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			}
		}

		//next, delete and then re-insert the aliases
		$alias = new Alias();
		foreach ($resource->getAliases() as $alias) {
			$alias->delete();
		}

		$aliasTypeArray = array();
		$aliasTypeArray = explode(':::', $_POST['aliasTypes']);
		$aliasNameArray = array();
		$aliasNameArray = explode(':::', $_POST['aliasNames']);


		foreach ($aliasTypeArray as $key => $value){
			if (($value) && ($aliasNameArray[$key])){
				$alias = new Alias();
				$alias->resourceID = $resourceID;
				$alias->aliasTypeID = $value;
				$alias->shortName = $aliasNameArray[$key];

				$alias->save();


			}
		}



		//now delete and then re-insert the organizations
		$resource->removeResourceOrganizations();

		$organizationRoleArray = array();
		$organizationRoleArray = explode(':::', $_POST['organizationRoles']);
		$organizationArray = array();
		$organizationArray = explode(':::', $_POST['organizations']);


		foreach ($organizationRoleArray as $key => $value){
			if (($value) && ($organizationArray[$key])){
				$resourceOrganizationLink = new ResourceOrganizationLink();
				$resourceOrganizationLink->resourceID = $resourceID;
				$resourceOrganizationLink->organizationRoleID = $value;
				$resourceOrganizationLink->organizationID = $organizationArray[$key];

				$resourceOrganizationLink->save();
			}
		}

?>
