<?php

	Resource::setSearch($_POST['search']);

	$queryDetails = Resource::getSearchDetails();
	$whereAdd = $queryDetails["where"];
	$page = $queryDetails["page"];
	$orderBy = $queryDetails["order"];
	$recordsPerPage = $queryDetails["perPage"];

	// @annelhote : get all resource types
	$resourceTypeObj = new ResourceType();
	$typesArray = $resourceTypeObj->allAsArray();

	// @annelhote : set up the max level to the sky
	$recordsPerPage = 99999999;

	//numbers to be displayed in records per page dropdown
		$recordsPerPageDD = array(10,25,50,100);

		//determine starting rec - keeping this based on 0 to make the math easier, we'll add 1 to the display only
		//page will remain based at 1
		if ($page == '1'){
			$startingRecNumber = 0;
		}else{
			$startingRecNumber = ($page * $recordsPerPage) - $recordsPerPage;
		}


		//get total number of records to print out and calculate page selectors
		$resourceObj = new Resource();
		$totalRecords = $resourceObj->searchCount($whereAdd);

		//reset pagestart to 1 - happens when a new search is run but it kept the old page start
		if ($totalRecords < $startingRecNumber){
			$page = 1;
			$startingRecNumber = 1;
		}

		$limit = $startingRecNumber . ", " . $recordsPerPage;

		$resourceArray = array();
		$resourceArray = $resourceObj->search($whereAdd, $orderBy, $limit);

		if (count($resourceArray) == 0){
			echo "<br /><br /><i>"._("Sorry, no requests fit your query")."</i>";
			$i=0;
		}else{
			//maximum number of pages to display on screen at one time
			$maxDisplay = 25;

			$displayStartingRecNumber = $startingRecNumber + 1;
			$displayEndingRecNumber = $startingRecNumber + $recordsPerPage;

			if ($displayEndingRecNumber > $totalRecords){
				$displayEndingRecNumber = $totalRecords;
			}

			// @annelhote : Don't display the record count anymore, tends to be useless
			// div for displaying record count
			// echo "<span style='float:left; font-weight:bold; width:650px;'>"._("Displaying ") . $displayStartingRecNumber . _(" to ") . $displayEndingRecNumber . _(" of ") . $totalRecords . _(" Resource Records")."</span><span style='float:right;width:20px;'><a href='javascript:void(0);'><img src='images/xls.gif' id='export'></a></span>";
			echo "<span style='float:right;width:20px;'><a href='javascript:void(0);'><img src='images/xls.gif' id='export'></a></span>";


			//print out page selectors as long as there are more records than the number that should be displayed
			if ($totalRecords > $recordsPerPage){
				echo "<div style='vertical-align:bottom;text-align:left;clear:both;'>";

				//print starting <<
				if ($page == 1){
					echo "<span class='smallerText'><<</span>&nbsp;";
				}else{
					$prevPage = $page - 1;
					echo "<a href='javascript:void(0);' id='" . $prevPage . "' class='setPage smallLink' alt='"._("previous page")."' title='"._("previous page")."'><<</a>&nbsp;";
				}


				//now determine the starting page - we will display 3 prior to the currently selected page
				if ($page > 3){
					$startDisplayPage = $page - 3;
				}else{
					$startDisplayPage = 1;
				}

				$maxPages = ($totalRecords / $recordsPerPage) + 1;

				//now determine last page we will go to - can't be more than maxDisplay
				$lastDisplayPage = $startDisplayPage + $maxDisplay;
				if ($lastDisplayPage > $maxPages){
					$lastDisplayPage = ceil($maxPages);
				}

				for ($i=$startDisplayPage; $i<$lastDisplayPage;$i++){

					if ($i == $page){
						echo "<span class='smallerText'>" . $i . "</span>&nbsp;";
					}else{
						echo "<a href='javascript:void(0);' id='" . $i . "' class='setPage smallLink'>" . $i . "</a>&nbsp;";
					}

				}

				$nextPage = $page + 1;
				//print last >> arrows
				if ($nextPage >= $maxPages){
					echo "<span class='smallerText'>>></span>&nbsp;";
				}else{
					echo "<a href='javascript:void(0);' id='" . $nextPage . "' class='setPage smallLink' alt='"._("next page")."' title='"._("next page")."'>>></a>&nbsp;";
				}

				echo "</div>";


			}else{
				echo "<div style='vertical-align:bottom;text-align:left;clear:both;'>&nbsp;</div>";
			}


			?>
			<table class='dataTable' style='width:727px'>
			<tr>
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("ID");?></td><td style='width:10px;'><a href='javascript:setOrder("R.resourceID + 0","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("R.resourceID + 0","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th style='min-width: 150px;'><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Name");?></td><td style='width:10px;'><a href='javascript:setOrder("R.titleText","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("R.titleText","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Creator");?></td><td style='width:10px;'><a href='javascript:setOrder("CU.loginID","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("CU.loginID","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<!-- @annelhote : Hide column that displays the creation date -->
			<!-- <th><table class='noBorderTable' style='width:100%'><tr><td><?php // echo _("Date Created");?></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("R.createDate","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("R.createDate","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th> -->
			<!-- @annelhote : Add new column to display the resource's updated date -->
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Date Updated");?></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("R.updateDate","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("R.updateDate","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Acquisition Type");?></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("acquisitionType","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;vertical-align:top;'><a href='javascript:setOrder("acquisitionType","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<!-- @annelhote : Hide column that displays the status -->
			<!-- <th><table class='noBorderTable' style='width:100%'><tr><td><?php // echo _("Status");?></td><td style='width:10px;'><a href='javascript:setOrder("S.shortName","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("S.shortName","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th> -->
			<!-- @annelhote : Add column to display the resource's status like 'New' or 'Trial' -->
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Status");?></td><td style='width:10px;'><a href='javascript:setOrder("RST.shortName","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("RST.shortName","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<!-- @annelhote : Add new column to display the resource's publication status -->
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Published");?></td><td style='width:10px;'><a href='javascript:setOrder("R.published","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("R.published","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<!-- @annelhote : Add new column to display the resource's type -->
			<th><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Resource Type");?></td><td style='width:10px;'><a href='javascript:setOrder("RT.shortName","asc");'><img src='images/arrowup.gif' border=0></a></td><td style='width:10px;'><a href='javascript:setOrder("RT.shortName","desc");'><img src='images/arrowdown.gif' border=0></a></td></tr></table></th>
			<!-- @annelhote : Add new column to directly edit a resource -->
			<th style='max-width: 25px; overflow-x: hidden;'><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("Edit ");?></td></tr></table></th>
			<!-- @annelhote : Add new column to directly delete a resource -->
			<th style='max-width: 25px; overflow-x: hidden;'><table class='noBorderTable' style='width:100%'><tr><td><?php echo _("remove resource")?></td></tr></table></th>
			</tr>

			<?php

			$i=0;
			foreach ($resourceArray as $resource){
				$i++;
				if ($i % 2 == 0){
					$classAdd="";
				}else{
					$classAdd="class='alt'";
				}

				// @annelhote : if updateDate is null, set it to createDate
				if(is_null($resource['updateDate'])) {
					$resource['updateDate'] = $resource['createDate'];
				}


				echo "<tr>";
				echo "<td $classAdd><a href='resource.php?resourceID=" . $resource['resourceID'] . "'>" . $resource['resourceID'] . "</a></td>";
				echo "<td $classAdd><a href='resource.php?resourceID=" . $resource['resourceID'] . "'>" . $resource['titleText'] . "</a></td>";

				if ($resource['firstName'] || $resource['lastName']){
					echo "<td $classAdd>" . $resource['firstName'] . " " . $resource['lastName'] ."</td>";
				}else{
					echo "<td $classAdd>" . $resource['createLoginID'] . "</td>";
				}

				// @annelhote : Hide the creation date and display the updated date instead
				// echo "<td $classAdd>" . format_date($resource['createDate']) . "</td>";
				echo "<td $classAdd>" . format_date($resource['updateDate']) . "</td>";

				echo "<td $classAdd>" . $resource['acquisitionType'] . "</td>";

				// @annelhote : Hide the default resource status
				// echo "<td $classAdd>" . $resource['status'] . "</td>";
				// @annelhote : Display the needed resource status like 'New' or 'Trial'
				echo "<td $classAdd>" . ($resource['resourceStatus'] != '' ? _($resource['resourceStatus']) : '') . "</td>";

				// @annelhote : add resource's publication status
				echo "<td $classAdd>" . ($resource['published'] ? _('Yes') : _('No')) . "</td>";

				// @annelhote : get all types from this resource
				$resourceTypesLinkObj = new ResourceTypeLink();
				$resourceTypesArray = $resourceTypesLinkObj->getResourceTypes($resource['resourceID']);
				// @annelhote : add resource's type
				$resourceTypesString = '';
				foreach ($resourceTypesArray as $key => $value) {
					$resourceTypesString .= ($resourceTypesString == '') ? $value : ', ' . $value;
				}
				echo "<td $classAdd>" . $resourceTypesString . "</td>";

				// @annelhote : add modal link to directly update a resource
				if ($user->canEdit()) {
					echo "<td $classAdd style='text-align: center;'><a href='ajax_forms.php?action=getUpdateProductForm&height=498&width=730&resourceID=" . $resource['resourceID'] . "&modal=true' class='thickbox'><img src='images/edit.gif' alt='" . _("edit") . "' title='" . _("edit resource") . "'></a></td>";
				} else {
					echo "<td $classAdd></td>";
				}

				// @annelhote : add modal link to directly delete a resource
				if ($user->isAdmin) {
					echo "<td $classAdd style='text-align: center;'><a href='javascript:void(0);' class='removeResource' id='" . $resource['resourceID'] . "'><img src='images/cross.gif' alt='" . _("remove resource") . "' title='" . _("remove resource") . "'></a></td>";
				} else {
					echo "<td $classAdd></td>";
				}

				echo "</tr>";
			}

			?>
			</table>

			<table style='width:100%;margin-top:4px'>
			<tr>
			<td style='text-align:left'>
			<?php
			//print out page selectors
			if ($totalRecords > $recordsPerPage){

				//print starting <<
				if ($page == 1){
					echo "<span class='smallerText'><<</span>&nbsp;";
				}else{
					$prevPage = $page - 1;
					echo "<a href='javascript:void(0);' id='" . $prevPage . "' class='setPage smallLink' alt='"._("previous page")."' title='"._("previous page")."'><<</a>&nbsp;";
				}


				//now determine the starting page - we will display 3 prior to the currently selected page
				if ($page > 3){
					$startDisplayPage = $page - 3;
				}else{
					$startDisplayPage = 1;
				}

				$maxPages = ($totalRecords / $recordsPerPage) + 1;

				//now determine last page we will go to - can't be more than maxDisplay
				$lastDisplayPage = $startDisplayPage + $maxDisplay;
				if ($lastDisplayPage > $maxPages){
					$lastDisplayPage = ceil($maxPages);
				}

				for ($i=$startDisplayPage; $i<$lastDisplayPage;$i++){

					if ($i == $page){
						echo "<span class='smallerText'>" . $i . "</span>&nbsp;";
					}else{
						echo "<a href='javascript:void(0);' id='" . $i . "' class='setPage smallLink'>" . $i . "</a>&nbsp;";
					}

				}

				$nextPage = $page + 1;
				//print last >> arrows
				if ($nextPage >= $maxPages){
					echo "<span class='smallerText'>>></span>&nbsp;";
				}else{
					echo "<a href='javascript:void(0);' id='" . $nextPage . "' class='setPage smallLink' alt='"._("next page")."' title='"._("next page")."'>>></a>&nbsp;";
				}
			}
			?>
			</td>
			<!-- @annelhote : Remove the pagination tool -->
			<!--
				<td style="text-align:right">
				<select id='numberRecordsPerPage' name='numberRecordsPerPage' style='width:50px;'>
					<?php
					// foreach ($recordsPerPageDD as $i){
					// 	if ($i == $recordsPerPage){
					// 		echo "<option value='" . $i . "' selected>" . $i . "</option>";
					// 	}else{
					// 		echo "<option value='" . $i . "'>" . $i . "</option>";
					// 	}
					// }
					?>
				</select>
				<span class='smallText'><?php // echo _("records per page");?></span>
				</td>
			-->
			</tr>
			</table>

			<?php
		}

?>

