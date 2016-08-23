<?php
	$resourceID = $_GET['resourceID'];
	$generalSubject = new GeneralSubject();
	$generalSubjectArray = $generalSubject->allAsArray();

	// @annelhote : Get the general and detailled subjects for this resource
	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
	$generalDetailSubjectIDArray = array();
	foreach ($resource->getGeneralDetailSubjectLinkID() as $instance) {
		array_push($generalDetailSubjectIDArray, $instance->generalSubjectID);
	}

?>
		<div id='div_updateForm'>

		<!-- @aanelhote : Duplicate the "add" and "cancel" buttons at the top -->
		<td style='text-align:right'>
			<!-- @annelhote : Add a "add" button for the whole list -->
			<input type='button' value='<?php echo _("add");?>' onclick="addSubjects();">
			<input type='button' value='<?php echo _("cancel");?>' onclick="window.parent.tb_remove(); return false;">
		</td>
		<br /><br />

		<div class='formTitle' style='width:403px;'><span class='headerText' style='margin-left:7px;'></span><?php echo _("Add General / Detail Subject Link");?></div>

	<?php
		if (count($generalSubjectArray) > 0){
			?>
			<table class='linedDataTable' style='width:100%'>
				<tr>
				<!-- @annelhote : Add column for the checkboxes -->
				<th>&nbsp;</th>
				<th><?php echo _("General Subject Name");?></th>
				<th><?php echo _("Detail Subject Name");?></th>
				<!-- @annelhote : Hide unused columns -->
				<!-- <th>&nbsp;</th> -->
				<!-- <th>&nbsp;</th> -->
				</tr>
				<?php

				foreach($generalSubjectArray as $ug) {
					$generalSubject = new GeneralSubject(new NamedArguments(array('primaryKey' => $ug['generalSubjectID'])));

					echo "<tr>";

					// @annelhote : Add checkboxes
					if(in_array($ug['generalSubjectID'], $generalDetailSubjectIDArray)) {
						echo "<td><input type='checkbox' name='subject' value='" . $ug['generalSubjectID'] . "' resourceID='" . $resourceID . "' detailSubjectID='-1' checked></td>";
					} else {
						echo "<td><input type='checkbox' name='subject' value='" . $ug['generalSubjectID'] . "' resourceID='" . $resourceID . "' detailSubjectID='-1'></td>";
					}
					
					echo "<td>" . $generalSubject->shortName . "</td>";
					echo "<td></td>";
					// @annelhote : Hide the "add" button per line
					// echo "<td><a href='javascript:void(0);' class='resourcesSubjectLink' resourceID='" . $resourceID . " 'generalSubjectID='" . $ug['generalSubjectID'] . " 'detailSubjectID='" . -1 . "'><img src='images/add.gif' alt='"._("add")."' title='"._("add")."'></a></td>";

					foreach ($generalSubject->getDetailedSubjects() as $detailedSubjects){
						echo "<tr>";
						echo "<td></td>";
						echo "<td>";
						echo $detailedSubjects->shortName . "</td>";
						echo "<td><a href='javascript:void(0);' class='resourcesSubjectLink' resourceID='" . $resourceID . " 'generalSubjectID='" . $ug['generalSubjectID'] . " 'detailSubjectID='" . $detailedSubjects->detailedSubjectID . "'><img src='images/add.gif' alt='"._("add")."' title='"._("add")."'></a></td>";
						echo "</tr>";
					}
					echo "</tr>";
				}

				?>
			</table>
			<?php

		}else{
			echo _("(none found)")."<br />";
		}
		?>

		<td style='text-align:right'>
			<!-- @annelhote : Add a "add" button for the whole list -->
			<input type='button' value='<?php echo _("add");?>' onclick="addSubjects();">
			<input type='button' value='<?php echo _("cancel");?>' onclick="window.parent.tb_remove(); return false;">
		</td>
		</div>

		<script type="text/javascript" src="js/forms/resourceSubject.js?random=<?php echo rand(); ?>"></script>

