<?php

		$resourceID = $_POST['resourceID'];

		if ($resourceID){
			//get this resource
			$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
		}else{
			//set up new resource
			$resource = new Resource();
			$resource->createLoginID 		= $loginID;
			$resource->createDate			= date( 'Y-m-d' );
			$resource->updateLoginID 		= '';
			$resource->updateDate			= '';

		}


		//determine status id
		$status = new Status();
		$statusID = $status->getIDFromName($_POST['resourceStatus']);



		$resource->resourceTypeID 		= $_POST['resourceTypeID'];
		$resource->resourceFormatID 	= $_POST['resourceFormatID'];
		$resource->acquisitionTypeID 	= $_POST['acquisitionTypeID'];

		$resource->titleText 			= $_POST['titleText'];
		$resource->descriptionText 		= $_POST['descriptionText'];
		$resource->isbnOrISSN	 		= '';
		$resource->statusID		 		= $statusID;
		$resource->orderNumber	 		= '';
		$resource->systemNumber 		= '';
		$resource->userLimitID	 		= '';
		$resource->authenticationUserName 	= '';
		$resource->authenticationPassword 	= '';
		$resource->storageLocationID		= '';
		$resource->registeredIPAddresses 	= '';
		$resource->providerText			 	= $_POST['providerText'];
		$resource->coverageText 			= '';

		if ($_POST['resourceURL'] != 'http://'){
			$resource->resourceURL = $_POST['resourceURL'];
		}else{
			$resource->resourceURL = '';
		}

		if ($_POST['resourceAltURL'] != 'http://'){
			$resource->resourceAltURL = $_POST['resourceAltURL'];
		}else{
			$resource->resourceAltURL = '';
		}

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
		// @annelhote : Update resource's title translated in french
		$resource->titleText_fr			= $_POST['titleText_fr'];
		// @annelhote : Update resource's description translated in french
		$resource->descriptionText_fr	= $_POST['descriptionText_fr'];
		// @annelhote : Update resource's ISBN or ISSN
		$isbnarray = json_decode($_POST['isbnOrISSN']);
		$resource->setIsbnOrIssn($isbnarray);

		try {
			$resource->save();
			echo $resource->primaryKey;
			$resourceID=$resource->primaryKey;

			//get the provider ID in case we insert what was entered in the provider text box as an organization link
			$organizationRole = new OrganizationRole();
			$organizationRoleID = $organizationRole->getProviderID();

			//add notes
			if (($_POST['noteText']) || (($_POST['providerText']) && (!$_POST['organizationID']))){
				//first, remove existing notes in case this was saved before
				$resource->removeResourceNotes();

				//this is just to figure out what the creator entered note type ID is
				$noteType = new NoteType();

				$resourceNote = new ResourceNote();
				$resourceNote->resourceNoteID 	= '';
				$resourceNote->updateLoginID 	= $loginID;
				$resourceNote->updateDate		= date( 'Y-m-d' );
				$resourceNote->noteTypeID 		= $noteType->getInitialNoteTypeID();
				$resourceNote->tabName 			= 'Product';
				$resourceNote->resourceID 		= $resourceID;

				//only insert provider as note if it's been submitted
				if (($_POST['providerText']) && (!$_POST['organizationID']) && ($_POST['resourceStatus'] == 'progress')){
					$resourceNote->noteText 	= "Provider:  " . $_POST['providerText'] . "\n\n" . $_POST['noteText'];
				}else{
					$resourceNote->noteText 	= $_POST['noteText'];
				}

				$resourceNote->save();
			}

			// @annelhote : Comment all that to redo it as an orray
			// first remove the organizations if this is a saved request
			// $resource->removeResourceOrganizations();
			// if (($_POST['organizationID']) && ($organizationRoleID)){

			// 	$resourceOrganizationLink = new ResourceOrganizationLink();
			// 	$resourceOrganizationLink->resourceID = $resourceID;
			// 	$resourceOrganizationLink->organizationID = $_POST['organizationID'];
			// 	$resourceOrganizationLink->organizationRoleID = $organizationRoleID;

			// 	$resourceOrganizationLink->save();
			// }

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

			// @annelhote
			// update resource relationship (currently code only allows parent)
			// first remove the existing relationship then add it back
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

			// @annelhote : Add the Archive status of the resource
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

			// @annelhote : next, delete and then re-insert the aliases
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

			$yearArray          = array();  $yearArray          = explode(':::',$_POST['years']);
			$subStartArray      = array();  $subStartArray      = explode(':::',$_POST['subStarts']);
			$subEndArray        = array();  $subEndArray        = explode(':::',$_POST['subEnds']);
			$fundNameArray      = array();  $fundNameArray      = explode(':::',$_POST['fundNames']);
			$paymentAmountArray = array();  $paymentAmountArray = explode(':::',$_POST['paymentAmounts']);
			$currencyCodeArray  = array();  $currencyCodeArray  = explode(':::',$_POST['currencyCodes']);
			$orderTypeArray     = array();  $orderTypeArray     = explode(':::',$_POST['orderTypes']);
			$costDetailsArray   = array();  $costDetailsArray   = explode(':::',$_POST['costDetails']);
			$costNoteArray      = array();  $costNoteArray      = explode(':::',$_POST['costNotes']);
			$invoiceArray       = array();  $invoiceArray       = explode(':::',$_POST['invoices']);

			//first remove all payment records, then we'll add them back
			$resource->removeResourcePayments();

			foreach ($orderTypeArray as $key => $value){
				if (($value) && ($paymentAmountArray[$key] || $yearArray[$key] || $fundNameArray[$key] || $costNoteArray[$key])){
					$resourcePayment = new ResourcePayment();
					$resourcePayment->resourceID    = $resourceID;
					$resourcePayment->year          = $yearArray[$key];
					$resourcePayment->subscriptionStartDate = $subStartArray[$key];
					$resourcePayment->subscriptionEndDate   = $subEndArray[$key];
					$resourcePayment->fundName      = $fundNameArray[$key];
					$resourcePayment->paymentAmount = cost_to_integer($paymentAmountArray[$key]);
					$resourcePayment->currencyCode  = $currencyCodeArray[$key];
					$resourcePayment->orderTypeID   = $value;
					$resourcePayment->costDetails   = $costDetailsArray[$key];
					$resourcePayment->costNote      = $costNoteArray[$key];
					$resourcePayment->invoice       = $invoiceArray[$key];
					try {
						$resourcePayment->save();
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



			//next if the resource was submitted, enter into workflow
			if ($statusID == $status->getIDFromName('progress')){
				$resource->enterNewWorkflow();
			}



		} catch (Exception $e) {
			echo $e->getMessage();
		}

?>
