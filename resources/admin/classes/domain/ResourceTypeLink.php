<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
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
** @author : annelhote
**
**************************************************************************************************************************
*/

class ResourceTypeLink extends DatabaseObject {

	public function getResourceTypes($resourceId) {
		$query = "SELECT RT.shortName FROM ResourceTypeLink AS RTL, ResourceType AS RT WHERE RTL.resourceTypeId = RT.resourceTypeID AND RTL.resourceID = '" . $resourceId . "'";
		$rows = $this->db->processQuery($query, 'assoc');

		$results = array();
		foreach ($rows as $row) {
			if(is_array($row)) {
				array_push($results, $row['shortName']);
			} else {
				array_push($results, $row);
			}
		}

		return $results;
	}

}

?>