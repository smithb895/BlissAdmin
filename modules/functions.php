<?php

function stringSplitSQL($inputString, $columnName) {
	$safe = '';
	$validchars = '#[^0-9a-z_ \.,\-+]#i';
	if (preg_match($validchars, $inputString)) {
		$stringsplit = preg_split($validchars, $inputString);
		for ($i=0; $i<count($stringsplit); $i++) {
			if ($i != 0) {
				$safe .= " AND ".$columnName." LIKE '%".$stringsplit[$i]."%'";
			} else {
				$safe = "'%".$stringsplit[$i]."%'";
			}
		}
	} else {
		$safe = "'%".$inputString."%'";
	}
	
	return $safe;
}

function getPaginationLinks($count,$per_page,$cur_page) {
	$previous_btn = true;
	$next_btn = true;
	$first_btn = true;
	$last_btn = true;
	
	/* HANDLE PAGINATION */
	$no_of_paginations = ceil($count / $per_page);
	/* -----Calculating the starting and endign values for the loop----- */
	if ($cur_page >= 7) {
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
			$end_loop = $cur_page + 3;
		else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
			$start_loop = $no_of_paginations - 6;
			$end_loop = $no_of_paginations;
		} else {
			$end_loop = $no_of_paginations;
		}
	} else {
		$start_loop = 1;
		if ($no_of_paginations > 7)
			$end_loop = 7;
		else
			$end_loop = $no_of_paginations;
	}
	/* ----------------------------------------------------------------------------- */
	$return = "<tr class='pagination'><td colspan='8'><ul>";

	// FOR ENABLING THE FIRST BUTTON

	if ($first_btn && $cur_page > 1) {
		$return .= "<li p='1' class='active'>First</li>";
	} else if ($first_btn) {
		$return .= "<li p='1' class='inactive'>First</li>";
	}

	// FOR ENABLING THE PREVIOUS BUTTON
	if ($previous_btn && $cur_page > 1) {
		$pre = $cur_page - 1;
		$return .= "<li p='$pre' class='active'>Previous</li>";
	} else if ($previous_btn) {
		$return .= "<li class='inactive'>Previous</li>";
	}
	for ($i = $start_loop; $i <= $end_loop; $i++) {

		if ($cur_page == $i)
			$return .= "<li p='$i' id='current_page' class='active'>{$i}</li>";
		else
			$return .= "<li p='$i' class='active'>{$i}</li>";
	}

	// TO ENABLE THE NEXT BUTTON
	if ($next_btn && $cur_page < $no_of_paginations) {
		$nex = $cur_page + 1;
		$return .= "<li p='$nex' class='active'>Next</li>";
	} else if ($next_btn) {
		$return .= "<li class='inactive'>Next</li>";
	}

	// TO ENABLE THE END BUTTON
	if ($last_btn && $cur_page < $no_of_paginations) {
		$return .= "<li p='$no_of_paginations' class='active'>Last</li>";
	} else if ($last_btn) {
		$return .= "<li p='$no_of_paginations' class='inactive'>Last</li>";
	}
	$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
	$total_string = "<span class='total' a='$no_of_paginations'>Page <b>" . $cur_page . "</b> of <b>$no_of_paginations</b></span>";
	$return = $return . "</ul>" . $goto . $total_string . "</td></tr>";  // Content for pagination
	
	return $return;
}

function fetchBanRows($qryHandle,$count,$cur_page,$page,$per_page) {
	//$page -= 1;
	//$per_page = 50;
	/*$previous_btn = true;
	$next_btn = true;
	$first_btn = true;
	$last_btn = true;*/
	//$start = $page * $per_page;
	$msg = '';
	while ($row=$qryHandle->fetch(PDO::FETCH_ASSOC)) {
		//$htmlmsg=htmlentities($row['message']); //HTML entries filter
		if ($row['ID'] % 2) {
			$msg .= '<tr class="alternate-row">';
		} else {
			$msg .= '<tr>';
		}
		$msg .= '
							<td><input name="delban[]" value="'.$row["ID"].'" type="checkbox"/></td>
							<td>'.$row["ID"].'</td>
							<td>'.$row["GUID_IP"].'</td>
							<td>'.$row["LENGTH"].'</td>
							<td>'.$row["REASON"].'</td>
							<td>'.$row["ADMIN"].'</td>
							<td>'.$row["DATE_TIME"].'</td>
							<td>'.$row["ACTIVE"].'</td>
						</tr>';
	}
	
	$msg .= getPaginationLinks($count,$per_page,$cur_page);
	
	return $msg;
}

function fetchPlayerDBRows($qryHandle,$count,$cur_page,$page,$per_page) {
	//$page -= 1;
	//$per_page = 50;
	/*$previous_btn = true;
	$next_btn = true;
	$first_btn = true;
	$last_btn = true;*/
	$start = $page * $per_page;
	$msg = '';
	while ($row=$qryHandle->fetch(PDO::FETCH_ASSOC)) {
		//$htmlmsg=htmlentities($row['message']); //HTML entries filter
		if ($row['ID'] % 2) {
			$msg .= '<tr class="alternate-row">';
		} else {
			$msg .= '<tr>';
		}
		$msg .= '
						<td>'.$row["GUID"].'</td>
						<td>'.$row["KNOWN_NAMES"].'</td>
						<td>'.$row["KNOWN_IPS"].'</td>
						<td>'.$row["LAST_SEEN"].'</td>
						<td>'.$row["FIRST_SEEN"].'</td>
						<td>'.$row["SERVER"].'</td>
						</tr>';
	}
	
	$msg .= getPaginationLinks($count,$per_page,$cur_page);
	
	return $msg;
}



?>