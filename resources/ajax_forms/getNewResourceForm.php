<?php

		$resourceID = $_GET['resourceID'];
		if ($resourceID){
		$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
		}else{
			$resource = new Resource();
		}

		//used for default currency
		$config = new Configuration();

		//get all acquisition types for output in drop down
		$acquisitionTypeArray = array();
		$acquisitionTypeObj = new AcquisitionType();
		$acquisitionTypeArray = $acquisitionTypeObj->sortedArray();

		//get all resource formats for output in drop down
		$resourceFormatArray = array();
		$resourceFormatObj = new ResourceFormat();
		$resourceFormatArray = $resourceFormatObj->sortedArray();

		// get all resource types for output in drop down
		$typesArray = array();
		$resourceTypeObj = new ResourceType();
		$typesArray = $resourceTypeObj->allAsArray();

		// @annelhote : get all types from this resource
		$resourceTypesLinkObj = new ResourceTypeLink();
		$resourceTypesArray = $resourceTypesLinkObj->getResourceTypes($resourceID);


		//get all currency for output in drop down
		$currencyArray = array();
		$currencyObj = new Currency();
		$currencyArray = $currencyObj->allAsArray();

		//get all Order Types for output in drop down
		$orderTypeArray = array();
		$orderTypeObj = new OrderType();
		$orderTypeArray = $orderTypeObj->allAsArray();

		//get all Cost Details for output in drop down
		$costDetailsArray = array();
		$costDetailsObj = new CostDetails();
		$costDetailsArray = $costDetailsObj->allAsArray();

		//get payments
		$paymentArray = array();
		if ($resourceID){
			$sanitizedInstance = array();
			$instance = new ResourcePayment();
			foreach ($resource->getResourcePayments() as $instance) {
				foreach (array_keys($instance->attributeNames) as $attributeName) {
					$sanitizedInstance[$attributeName] = $instance->$attributeName;
				}

				$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;

				array_push($paymentArray, $sanitizedInstance);
			}
		}

		//get notes
		if ($resourceID){
			$resourceNote = $resource->getInitialNote;
		}else{
			$resourceNote = new ResourceNote();
		}

		$orgArray = $resource->getOrganizationArray();
		if (count($orgArray)>0){
			foreach ($orgArray as $org){
				$providerText = $org['organization'];
				$orgID = $org['organizationID'];
			}
		}else{
			$providerText = $resource->providerText;
			$orgID = '';
		}

		// @annelhote : get all languages
		$languageObj = new Language();
		$languages = $languageObj->getAll();

		// @annelhote : get all languages from this resource
		$resourceLanguageObj = new ResourceLanguage();
		$resourceLanguageArray = $resourceLanguageObj->getResourceLanguages($resourceID);

		// @annelhote : get all status for output in drop down
		$resourceStatusArray = array();
		$resourceStatusObj = new ResourceStatus();
		$resourceStatusArray = $resourceStatusObj->sortedArray();

		// @annelhote : get parents resources
		$sanitizedInstance = array();
		$instance = new Resource();
		$parentResourceArray = array();
		foreach ($resource->getParentResources() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}
			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;
			array_push($parentResourceArray, $sanitizedInstance);
		}

		// @annelhote : get all organization roles for output in drop down
		$organizationRoleArray = array();
		$organizationRoleObj = new OrganizationRole();
		$organizationRoleArray = $organizationRoleObj->getArray();

		// @annelhote : get organizations (already returned in an array)
		$orgArray = $resource->getOrganizationArray();

		// @annelhote : Get all tutos
		$sanitizedInstance = array();
		$instance = new ResourceTuto();
		$tutosArray = array();
		foreach ($resource->getTutos() as $tuto) {
			foreach (array_keys($tuto->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $tuto->$attributeName;
			}
			$sanitizedInstance[$tuto->primaryKeyName] = $tuto->primaryKey;
			array_push($tutosArray, $sanitizedInstance);
		}

		// @annelhote : tranform MySQL Date into javascript one
		$a = strptime($resource->publicationDate, '%Y-%m-%d');
		$d = sprintf("%02d", ($a['tm_mon'] + 1)) . '/' . sprintf("%02d", $a['tm_mday']) . '/' . ($a['tm_year'] + 1900);

		// @annelhote : get all alias types for output in drop down
		$aliasTypeArray = array();
		$aliasTypeObj = new AliasType();
		$aliasTypeArray = $aliasTypeObj->allAsArray();

		// @annelhote : get aliases
		$sanitizedInstance = array();
		$instance = new Alias();
		$aliasArray = array();
		foreach ($resource->getAliases() as $instance) {
			foreach (array_keys($instance->attributeNames) as $attributeName) {
				$sanitizedInstance[$attributeName] = $instance->$attributeName;
			}
			$sanitizedInstance[$instance->primaryKeyName] = $instance->primaryKey;
			$aliasType = new AliasType(new NamedArguments(array('primaryKey' => $instance->aliasTypeID)));
			$sanitizedInstance['aliasTypeShortName'] = $aliasType->shortName;
			array_push($aliasArray, $sanitizedInstance);
		}

?>
		<div id='div_resourceSubmitForm'>
		<form id='resourcePromptForm'>


		<input type='hidden' id='organizationID' value='<?php echo $orgID; ?>' />
		<input type='hidden' id='editResourceID' value='<?php echo $resourceID; ?>' />
		<div class='formTitle' style='width:745px;'><span class='headerText'><?php if ($resourceID) { echo _("Edit Saved Resource"); }else{ echo _("Add New Resource"); } ?></span></div>
		<div class='smallDarkRedText' style='height:14px;margin:3px 0px 0px 0px;'>&nbsp;* <?php echo _("required fields");?></div>

		<table class='noBorder' style='width:100%;'>
		<tr style='vertical-align:top;'>
		<td style='vertical-align:top; padding-right:35px;' colspan='2'>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<b><?php echo _("Product");?></b>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:310px; margin:5px 15px;'>

					<!-- @annelhote : Display resource logo -->
					<tr>
						<td>
							<input type="file" name="resourceLogo" id="resourceLogo" accept="image/*" />
						</td>
					</tr>
					<tr>
						<td class='resourceLogoFileName'>
							<input type="text" name="resourceLogoFileName" id="resourceLogoFileName" value="<?php echo $resource->logo; ?>" disabled />
							<a href='#'>
								<img src='images/cross.gif' alt='<?php echo _("remove ");?>' title='<?php echo _("remove ");?>' class='removeLogo' style="vertical-align: bottom;" />
							</a>
						</td>
					</tr>

					<!-- @annelhote : Change label from "Name:" to "English name:" -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='titleText'><?php echo _("English name:");?>&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
					<td><input type='text' id='titleText' style='width:220px;' class='changeInput' value="<?php echo $resource->titleText; ?>" /><span id='span_error_titleText' class='smallDarkRedText'></span></td>
					</tr>

					<!-- @annelhote : Add field to translate title in french -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='titleText_fr'><?php echo _("French name:");?></label></td>
					<td><input type='text' id='titleText_fr' style='width:220px;' class='changeInput' value = "<?php echo $resource->titleText_fr; ?>" /><span id='span_error_titleText' class='smallDarkRedText'></span></td>
					</tr>

					<!-- @annelhote : Change label from "Description:" to "English description:" -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='descriptionText'><?php echo _("English description:");?></label></td>
					<td><textarea rows='3' id='descriptionText' style='width:223px'><?php echo $resource->descriptionText; ?></textarea></td>
					</tr>

					<!-- @annelhote : Add field to translate description in french -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='descriptionText_fr'><?php echo _("French description:");?></label></td>
					<td><textarea rows='3' id='descriptionText_fr' style='width:223px'><?php echo $resource->descriptionText_fr; ?></textarea></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='providerText'><?php echo _("Provider:");?></label></td>
					<td><input type='text' id='providerText' style='width:220px;' class='changeInput' value='<?php echo $providerText; ?>' /><span id='span_error_providerText' class='smallDarkRedText'></span></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceURL'><?php echo _("URL:");?></label></td>
					<td><input type='text' id='resourceURL' style='width:220px;' class='changeInput' value='<?php echo $resource->resourceURL; ?>' /><span id='span_error_resourceURL' class='smallDarkRedText'></span></td>
					</tr>

					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceAltURL'><?php echo _("Alt URL:");?></label></td>
					<td><input type='text' id='resourceAltURL' style='width:220px;' class='changeInput' value='<?php echo $resource->resourceAltURL; ?>' /><span id='span_error_resourceAltURL' class='smallDarkRedText'></span></td>
					</tr>

					<!-- @annelhote : Add resource's languages -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceLanguageID'><?php echo _("Language:");?></label></td>
					<td>
					<?php
					foreach ($languages as $language) {
						if(in_array($language['shortName'], $resourceLanguageArray)) {
							echo "<input type='checkbox' name='languages' value='" . $language['languageId'] . "' checked /> " . $lang_name->getNameLang($language['shortName']) . "<br/>";
						} else {
							echo "<input type='checkbox' name='languages' value='" . $language['languageId'] . "' /> " . $lang_name->getNameLang($language['shortName']) . "<br/>";
						}
					}
					?>
					</td>
					</tr>

					<!-- @annelhote : Add resource's status -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceStatusID'><?php echo _("Status:");?></label></td>
					<td>
					<select name='resourceStatusID' id='resourceStatusID' style='width:100px;' class='changeSelect'>
					<option value=''></option>
					<?php
					foreach ($resourceStatusArray as $resourceStatus){
						if (!(trim(strval($resourceStatus['resourceStatusID'])) != trim(strval($resource->resourceStatusID)))){
							echo "<option value='" . $resourceStatus['resourceStatusID'] . "' selected>" . _($resourceStatus['shortName']) . "</option>\n";
						}else{
							echo "<option value='" . $resourceStatus['resourceStatusID'] . "'>" . _($resourceStatus['shortName']) . "</option>\n";
						}
					}
					?>
					</select>
					</td>
					</tr>

					<!-- @annelhote : Add resource's publication status -->
					<tr>
					<td style='text-align:left'><label for='published'><?php echo _("Published") . ":" ; ?></label></td>
					<td>
					<?php
					if($resource->published) {
						echo "<input type='checkbox' id='published' name='published' checked />";
					} else {
						echo "<input type='checkbox' id='published' name='published' />";
					}
					?>
					</td>
					</tr>

					<!-- @annelhote : Add resource's publication comment -->
					<tr class="publicationComment">
					<td style='vertical-align:top;text-align:left;'><label for='publicationComment'><?php echo _("Publication Comment") . ":" ;?></label></td>
					<td><textarea rows='3' id='publicationComment' name='publicationComment' style='width:220px' class='changeInput' ><?php echo $resource->publicationComment; ?></textarea></td>
					</tr>

					<!-- @annelhote : Add resource's publication date -->
					<tr class="publicationDate">
					<td style='vertical-align:top;text-align:left;'><label for='publicationDate'><?php echo _("Publication Date") . ":" ;?></label></td>
					<td>
					<?php
					if($resource->publicationDate != '' && $resource->publicationDate != '0000-00-00') {
						echo "<input type='text' id='publicationDate' value=" . $d . " />";
					} else {
						echo "<input type='text' id='publicationDate' value='' />";
					}
					?>
					</td>
					</tr>

					<!-- @annelhote : Add resource's parents -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='titleText'><?php echo _("Parents:");?></label></td>
					<td>
					<span id="newParent">
					<div class="oneParent">
					<input type='text' class='parentResource parentResource_new' name='parentResourceName' value='' style='width:180px;' class='changeInput' />
					<input type='hidden' class='parentResource parentResource_new' name='parentResourceID' value='' />
					<span id='span_error_parentResourceName' class='smallDarkRedText'></span>
					<a href='#'><img src='images/add.gif' class='addParent' alt='<?php echo _("add Parent resource");?>' title='<?php echo _("add Parent Resource");?>'></a><br />
					</div>
					</span>
					<span id="existingParent"> 
						<?php
						$i = 1;
						foreach ($parentResourceArray as $parentResource) {
						$parentResourceObj = new Resource(new NamedArguments(array('primaryKey' => $parentResource['relatedResourceID'])));
						?>
							<div class="oneParent">
							<input type='text' name='parentResourceName' disabled='disabled' value = '<?php echo $parentResourceObj->titleText; ?>' style='width:180px;' class='changeInput'  />
							<input type='hidden' name='parentResourceID' value = '<?php echo $parentResourceObj->resourceID; ?>' />
							<a href='javascript:void();'><img src='images/cross.gif' alt='<?php echo _("remove parent");?>' title='<?php echo _("remove parent");?>' class='removeParent' /></a>
							</div>
						<?php
						$i++;
						}
						?>
					</span>
					</td>
					</tr>

					<!-- @annelhote : Add resource's ISSN / ISBN -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='isbnOrISSN'><?php echo _("ISSN / ISBN:");?></label></td>
					<td>
					<span id="newIsbn">
					<input type='text' class='isbnOrISSN isbnOrISSN_new' name='isbnOrISSN' value = "" style='width:180px;' class='changeInput'  />
					<span id='span_errors_isbnOrISSN' class='smallDarkRedText'></span>
					<a href='javascript:void(0);'><img src='images/add.gif' class='addIsbn' alt='<?php echo _("add Isbn");?>' title='<?php echo _("add Isbn");?>'></a><br />
					</span>
					<span id="existingIsbn">
						<?php
							$isbnOrIssns = $resource->getIsbnOrIssn();
							$i = 1;
							foreach ($isbnOrIssns as $isbnOrIssn) {
						?>
						<input type='text' class='isbnOrISSN' name='isbnOrISSN' value = '<?php echo $isbnOrIssn->isbnOrIssn; ?>' style='width:97px;' class='changeInput' /><br />
						<?php
							$i++;
							}
						?>
					</span>
					</td>
					</tr>

					<!-- @annelhote : Add resource's format -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceFormatID'><?php echo _("Format:");?></label></td>
					<td>
					<select name='resourceFormatID' id='resourceFormatID' style='width:100px;' class='changeSelect'>
					<option value=''></option>
					<?php
					foreach ($resourceFormatArray as $resourceFormat){
						if (!(trim(strval($resourceFormat['resourceFormatID'])) != trim(strval($resource->resourceFormatID)))){
							echo "<option value='" . $resourceFormat['resourceFormatID'] . "' selected>" . $resourceFormat['shortName'] . "</option>\n";
						}else{
							echo "<option value='" . $resourceFormat['resourceFormatID'] . "'>" . $resourceFormat['shortName'] . "</option>\n";
						}
					}
					?>
					</select>
					</td>
					</tr>

					<!-- @annelhote : Change resource's type as multivaluated -->
					<tr>
					<td style='vertical-align:top;text-align:left;'><label for='resourceTypeID'><?php echo _("Type:");?>&nbsp;&nbsp;<span class='bigDarkRedText'>*</span></label></td>
					<td>
					<?php
					foreach ($typesArray as $resourceType) {
						if(in_array($resourceType['shortName'], $resourceTypesArray)) {
							echo "<input type='checkbox' name='types' value='" . $resourceType['resourceTypeID'] . "' checked /> " . $resourceType['shortName'] . "<br/>";
						} else {
							echo "<input type='checkbox' name='types' value='" . $resourceType['resourceTypeID'] . "' /> " . $resourceType['shortName'] . "<br/>";
						}
					}
					?>
					<span id='span_error_resourceTypeID' class='smallDarkRedText'></span>
					</td>
					</tr>

					<!-- @annelhote : Add resource's archived -->
					<tr>
					<td style='text-align:left'><label for='archiveInd'><?php echo _("Archived:");?></label></td>
					<td>
					<input type='checkbox' id='archiveInd' name='archiveInd' <?php echo $archiveChecked; ?> />
					</td>
					</tr>

					<!-- @annelhote : Add resource's accessibility -->
					<tr>
					<td style='text-align:left'><label for='accessibility'><?php echo _("Accessibility:");?></label></td>
					<td>
					<?php
					if($resource->accessibility) {
						echo "<input type='checkbox' id='accessibility' name='accessibility' checked />";
					} else {
						echo "<input type='checkbox' id='accessibility' name='accessibility' />";
					}
					?>
					</td>
					</tr>

				</table>
			</td>
			</tr>

			</table>


			<!-- @annelhote : Remove resource format -->
			<!-- <span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b><?php // _("Format");?></b></label>&nbsp;<span class='bigDarkRedText'>*</span>&nbsp;&nbsp;</span> -->

			<!-- <table class='surroundBox' style='width:350px;'>
			<tr>
			<td>
<span id='span_error_resourceFormatID' class='smallDarkRedText'></span>

				<table class='noBorder' style='width:310px; margin:5px 15px;'>
				<?php
				// $i=0;

				// foreach ($resourceFormatArray as $resourceFormat){
				// 	$i++;
				// 	if(($i % 2)==1){
				// 		echo "<tr>\n";
				// 	}

				// 	//determine default
				// 	if ($resourceID){
				// 		if ($resourceFormat['resourceFormatID'] == $resource->resourceFormatID) $checked = 'checked'; else $checked = '';
				// 	//otherwise default to electronic
				// 	}else{
				// 		if (strtoupper($resourceFormat['shortName']) == 'ELECTRONIC') $checked = 'checked'; else $checked = '';
				// 	}

				// 	echo "<td><input type='radio' name='resourceFormatID' id='resourceFormatID' value='" . $resourceFormat['resourceFormatID'] . "' " . $checked . " />  " . $resourceFormat['shortName'] . "</td>";

				// 	if(($i % 2)==0){
				// 		echo "</tr>\n";
				// 	}
				// }

				// if(($i % 2)==1){
				// 	echo "<td>&nbsp;</td></tr>\n";
				// }

				?>

				</table>

			</td>
			</tr>
			</table> -->

			<!-- @annelhote : Remove resource acquisition type -->
			<!-- <span class='surroundBoxTitle'>&nbsp;&nbsp;<b><?php // echo _("Acquisition Type");?></b>&nbsp;<span class='bigDarkRedText'>*</span>&nbsp;&nbsp;</span> -->

			<!-- <table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding' style='width:310px; margin:5px 15px;'>
				<?php
				// $i=0;

				// foreach ($acquisitionTypeArray as $acquisitionType){
				// 	$i++;
				// 	if(($i % 3)==1){
				// 		echo "<tr>\n";
				// 	}

				// 	//set default
				// 	if ($resourceID){
				// 		if ($acquisitionType['acquisitionTypeID'] == $resource->acquisitionTypeID) $checked = 'checked'; else $checked = '';
				// 	}else{
				// 		if (strtoupper($acquisitionType['shortName']) == 'PAID') $checked = 'checked'; else $checked = '';
				// 	}

				// 	echo "<td><input type='radio' name='acquisitionTypeID' id='acquisitionTypeID' value='" . $acquisitionType['acquisitionTypeID'] . "' " . $checked . " />  " . $acquisitionType['shortName'] . "</td>\n";

				// 	if(($i % 3)==0){
				// 		echo "</tr>\n";
				// 	}
				// }

				// if(($i % 3)==1){
				// 	echo "<td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				// }else if(($i % 3)==2){
				// 	echo "<td>&nbsp;</td></tr>\n";
				// }

				?>

				</table>

			</td>
			</tr>
			</table> -->

		<td>
			<!-- <span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceTypeID'><b><?php echo _("Resource Type");?></b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder' style='width:320px; margin:5px 15px;'>
				<?php
				// $i=0;

				// foreach ($resourceTypeArray as $resourceType){
				// 	$i++;
				// 	if(($i % 3)==1){
				// 		echo "<tr>\n";
				// 	}

				// 	$checked='';
				// 	//determine default checked
				// 	if ($resourceID){
				// 		if (strtoupper($resourceType['resourceTypeID']) == $resource->resourceTypeID) $checked = 'checked';
				// 	}

				// 	echo "<td><input type='radio' name='resourceTypeID' id='resourceTypeID' value='" . $resourceType['resourceTypeID'] . "' " . $checked . "/>" . $resourceType['shortName'] . "</td>\n";

				// 	if(($i % 3)==0){
				// 		echo "</tr>\n";
				// 	}
				// }

				// if(($i % 3)==1){
				// 	echo "<td>&nbsp;</td><td>&nbsp;</td></tr>\n";
				// }else if(($i % 3)==2){
				// 	echo "<td>&nbsp;</td></tr>\n";
				// }

				?>

				</table>
				<span id='span_error_resourceTypeID' class='smallDarkRedText'></span>

			</td>
			</tr>
			</table> -->



			<!-- <span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b><?php // echo _("Notes");?></b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:350px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding' style='width:320px; margin:7px 15px;'>

					<tr>
					<td style='vertical-align:top;text-align:left;'><span class='smallGreyText'><?php // echo _("Include any additional information");?></span><br />
					<textarea rows='5' id='noteText' name='noteText' style='width:310px'><?php // echo $resourceNote->noteText; ?></textarea></td>
					</tr>
				</table>
			</td>
			</tr>
			</table> -->

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b>Organizations</b></label>&nbsp;&nbsp;</span>

			<table class='surroundBox' style='width:380px;'>
			<tr>
			<td>

				<table class='noBorder smallPadding newOrganizationTable' style='width:330px;  margin:15px 20px 0px 20px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:103px;'><?php echo _("Role:");?></td>
					<td style='vertical-align:top;text-align:left;font-weight:bold;width:160px;'><?php echo _("Organization:");?></td>
					<td>&nbsp;</td>
				</tr>

				<tr class='newOrganizationTR'>
				<td style='vertical-align:top;text-align:left;'>
					<select style='width:100px; background:#f5f8fa;' class='changeSelect organizationRoleID'>
					<option value=''></option>
					<?php
					foreach ($organizationRoleArray as $organizationRoleID => $organizationRoleShortName){
						echo "<option value='" . $organizationRoleID . "'>" . $organizationRoleShortName . "</option>\n";
					}
					?>
					</select>
				</td>

				<td style='vertical-align:top;text-align:left;'>
				<input type='text' value = '' style='width:160px;background:#f5f8fa;' class='changeAutocomplete organizationName' />
				<input type='hidden' class='organizationID' value = '' />
				</td>

				<td style='vertical-align:top;text-align:left;width:40px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addOrganization' alt='<?php echo _("add organization");?>' title='<?php echo _("add organization");?>'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorOrganization' style='margin:0px 20px 7px 26px;'></div>

				<table class='noBorder smallPadding organizationTable' style='width:330px;margin:0px 20px 10px 20px;'>
				<tr>
				<td colspan='3'>
					<hr style='width:310px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>

				<?php
				if (count($orgArray) > 0){

					foreach ($orgArray as $organization){
					?>
						<tr>
						<td style='vertical-align:top;text-align:left;'>
						<select style='width:100px;' class='organizationRoleID changeSelect'>
						<option value=''></option>
						<?php
						foreach ($organizationRoleArray as $organizationRoleID => $organizationRoleShortName){
							if (!(trim(strval($organizationRoleID)) != trim(strval($organization['organizationRoleID'])))){
								echo "<option value='" . $organizationRoleID . "' selected>" . $organizationRoleShortName . "</option>\n";
							}else{
								echo "<option value='" . $organizationRoleID . "'>" . $organizationRoleShortName . "</option>\n";
							}
						}
						?>
						</select>
						</td>

						<td style='vertical-align:top;text-align:left;'>
						<input type='text' class='changeInput organizationName' value = '<?php echo $organization['organization']; ?>' style='width:160px;' class='changeInput' />
						<input type='hidden' class='organizationID' value = '<?php echo $organization['organizationID']; ?>' />
						</td>

						<td style='vertical-align:top;text-align:left;width:40px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt="<?php echo _("remove organization");?>" title='<?php echo _("remove ").$resourceOrganization['shortName']._("organization"); ?>' class='remove' /></a>
						</td>

						</tr>

					<?php
					}
				}

				?>

				</table>



			</td>
			</tr>
			</table>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b><?php echo _("Tutos") . ':';?></b></label>&nbsp;&nbsp;</span>
			<table class='surroundBox' style='width:380px;'>
				<tr>
					<td>
						<table class='noBorder smallPadding' style='width:330px;  margin:15px 20px 0px 20px;'>
							<tr>
								<td style='vertical-align:top;text-align:left;width:103px;'>
									<div><?php echo _("Name:");?></div>
								</td>
								<td style='vertical-align:top;text-align:left;width:160px;'>
									<div><?php echo _("URL:");?><div>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td style='vertical-align:top;text-align:left;'>
									<input type='text' value = '' style='width:160px;background:#f5f8fa;' class='changeAutocomplete addTutoName' />
								</td>
								<td style='vertical-align:top;text-align:left;'>
									<input type='text' value = '' style='width:160px;background:#f5f8fa;' class='changeAutocomplete addTutoUrl' />
								</td>
								<td style='vertical-align:top;text-align:left;width:40px;'>
									<a href='#'><img src='images/add.gif' class='addTuto' alt='<?php echo _("add tuto");?>' title='<?php echo _("add tuto");?>' /></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table class='noBorder smallPadding tutoTable' style='width:330px;margin:0px 20px 10px 20px;'>
							<tr>
								<td colspan='3'>
									<hr style='width:310px;margin:0px 0px 5px 5px;' />
								</td>
							</tr>
							<tr class="tutoToFill" style="display: none;">
								<td>
									<input type="text" style="width:160px;" value="" class="addTutoNameToFill" disabled />
								</td>
								<td>
									<input type="text" style="width:160px;" value="" class="addTutoUrlToFill" disabled />
								</td>
								<td>
									<a href='#'>
										<img src='images/cross.gif' alt='<?php echo _("remove ");?>' title='<?php echo _("remove ");?>' class='removeTuto' style="vertical-align: bottom;" />
									</a>
								</td>
							</tr>
							<?php
								if (count($tutosArray) > 0) {
									foreach($tutosArray as $tuto) {
							?>
								<tr class="tutoFilled">
									<td>
										<input type="text" style="width:160px;" value="<?php echo $tuto['name']; ?>" class="addTutoNameToFill" disabled />
									</td>
									<td>
										<input type="text" style="width:160px;" value="<?php echo $tuto['url']; ?>" class="addTutoUrlToFill" disabled />
									</td>
									<td>
										<a href='#'>
											<img src='images/cross.gif' alt='<?php echo _("remove "); ?>' title='<?php echo _("remove "); ?>' class='removeTuto' style="vertical-align: bottom;" />
										</a>
									</td>
								</tr>
							<?php
									}
								}
							?>
						</table>
					</td>
				</tr>
			</table>

			<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='resourceFormatID'><b><?php echo _("Aliases");?></b></label>&nbsp;&nbsp;</span>
			<table class='surroundBox' style='width:300px;'>
			<tr>
			<td>
				<table class='noBorder smallPadding newAliasTable' style='width:260px; margin:15px 20px 0px 20px;'>
				<tr>
					<td style='vertical-align:top;text-align:left;width:98px;'><?php echo _("Type:");?></td>
					<td style='vertical-align:top;text-align:left;width:125px;'><?php echo _("Alias:");?></td>
					<td>&nbsp;</td>
				</tr>
				<tr class='newAliasTR'>
				<td style='vertical-align:top;text-align:left;'>
					<select style='width:98px; background:#f5f8fa;' class='changeSelect aliasTypeID'>
					<option value='' selected></option>
					<?php
					foreach ($aliasTypeArray as $aliasType){
						echo "<option value='" . $aliasType['aliasTypeID'] . "' class='changeSelect'>" . $aliasType['shortName'] . "</option>\n";
					}
					?>
					</select>
				</td>
				<td style='vertical-align:top;text-align:left;'>
				<input type='text' value = '' style='width:125px;' class='changeDefault aliasName' />
				</td>
				<td style='vertical-align:center;text-align:left;width:37px;'>
				<a href='javascript:void();'><img src='images/add.gif' class='addAlias' alt='<?php echo _("add this alias");?>' title='<?php echo _("add alias");?>'></a>
				</td>
				</tr>
				</table>
				<div class='smallDarkRedText' id='div_errorAlias' style='margin:0px 20px 7px 26px;'></div>
				<table class='noBorder smallPadding aliasTable' style='width:260px; margin:0px 20px 10px 20px;'>
				<tr>
				<td colspan='3'>
				<hr style='width:240px;margin:0px 0px 5px 5px;' />
				</td>
				</tr>
				<?php
				if (count($aliasArray) > 0){
					foreach ($aliasArray as $resourceAlias){
					?>
						<tr>
						<td style='vertical-align:top;text-align:left;'>
						<select style='width:98px;' class='changeSelect aliasTypeID'>
						<option value=''></option>
						<?php
						foreach ($aliasTypeArray as $aliasType){
							if (!(trim(strval($aliasType['aliasTypeID'])) != trim(strval($resourceAlias['aliasTypeID'])))){
								echo "<option value='" . $aliasType['aliasTypeID'] . "' selected class='changeSelect'>" . $aliasType['shortName'] . "</option>\n";
							}else{
								echo "<option value='" . $aliasType['aliasTypeID'] . "' class='changeSelect'>" . $aliasType['shortName'] . "</option>\n";
							}
						}
						?>
						</select>
						</td>
						<td style='vertical-align:top;text-align:left;'>
						<input type='text' value = '<?php echo htmlentities($resourceAlias['shortName'], ENT_QUOTES); ?>' style='width:125px;' class='changeInput aliasName' />
						</td>
						<td style='vertical-align:top;text-align:left;width:37px;'>
							<a href='javascript:void();'><img src='images/cross.gif' alt='<?php echo _("remove this alias");?>' title='<?php echo _("remove this alias");?>' class='remove' /></a>
						</td>
						</tr>
					<?php
					}
				}
				?>
				</table>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>

		<hr style='width:745px;margin:15px 0px 10px 0px;' />

		<table class='noBorderTable' style='width:175px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='<?php echo _("save");?>' class='submitResource' id ='save'></td>
				<td style='text-align:left'><input type='button' value='<?php echo _("submit");?>' class='submitResource' id ='progress'></td>
				<td style='text-align:left'><input type='button' value='<?php echo _("cancel");?>' onclick="kill(); tb_remove()"></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript" src="js/forms/resourceNewForm.js?random=<?php echo rand(); ?>"></script>

