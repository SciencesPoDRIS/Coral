/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/


 $(".resourcesSubjectLink").click(function () {
	  updateResourceSubjectTable($(this).attr("resourceID"), $(this).attr("generalSubjectID"), $(this).attr("detailSubjectID"));
 });


function updateResourceSubjectTable(resourceID, generalSubjectID, detailSubjectID){
	if (typeof resourceID === "undefined") 
		resourceID = -1;
	if (typeof generalSubjectID === "undefined") 
		generalSubjectID = -1;
	if (typeof detailSubjectID === "undefined") 
		detailSubjectID = -1;		

	$.ajax({
		 type:       "GET",
		 url:        "ajax_processing.php?action=updateResourceSubject&generalSubjectID=" + generalSubjectID + "&resourceID=" + resourceID + "&detailSubjectID=" + detailSubjectID,
		 cache:      false,
		 data:       { },
		 success:    function(html) {
			if (html){
				$("#span_errors").html(html);
				$("#submitDetailedSubjectForm").removeAttr("disabled");
			}else{
				kill();
				window.parent.tb_remove();
				window.parent.updateProduct();					
				return false;
			}			
		 }


	 });	
	
}

// @annelhote : Create function to add subject in mass
function addSubjects() {
	// Remove all the subjects
	$("#div_updateForm input[name='subject']").each(
		function() {
			$.ajax({
				type:       "GET",
				url:        "ajax_processing.php",
				cache:      false,
				data:       "action=removeResourceSubjectRelationship&generalDetailSubjectID=" + $(this).val() + "&resourceID=" + $(this).attr('resourceID'),
				success:    function(html) {
				}
			});
		}
	);
	// Add only the selected subjects
	$("#div_updateForm input:checked[name='subject']").each(
		function() {
			updateResourceSubjectTable($(this).attr('resourceID'), $(this).val(), $(this).attr('detailSubjectID'));
		}
	);
}

//kill all binds done by jquery live
function kill(){

	$('.resourcesSubjectLink').die('click'); 
	$('.changeDefault').die('blur');
	$('.changeDefault').die('focus');
	$('.changeInput').die('blur');
	$('.changeInput').die('focus');
	$('.select').die('blur');
	$('.select').die('focus');
	$('.remove').die('click');

}