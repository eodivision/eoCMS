<?php
/*  eoCMS © 2007 - 2010, a Content Management System
    by James Mortemore, Ryan Matthews
    http://www.eocms.com
	is licenced under a Creative Commons
	Attribution-Share Alike 2.0 UK: England & Wales Licence.
	Permissions beyond the scope of this licence 
	may be available at http://creativecommons.org/licenses/by-sa/2.0/uk/.
	Additional licence terms at http://eocms.com/licence.html
*/
function displayrating($page, $type, $number='') {
	global $user;
	//check to see if a rating is being added
	if(isset($_POST['rating_form']))
		$check = call('addrating', $page, $type, $_POST['rating']);
	//we need to check what the type is and if ratings has been enabled
	// We need to work out the average rating...
	$row = call("sql_query", "SELECT * FROM ratings WHERE type='$type' AND type_id='$page'");
	$rating = 0;
	$new = 1;
	//get the total number of votes
	$total = call('sql_num_rows', $row);
	while($fetch = call('sql_fetch_array', $row)) {
		$rating = $rating + $fetch['rating'];
		if(!$user['guest'] && $fetch['user_id'] == $user['id'] || $fetch['ip'] == call('visitor_ip'))
			$new = 0;
	}
	//work out the mean of the votes
	@$averageRating = $rating/$total;
	//round up the results
	$averageRating = round($averageRating, 2);
	//check to see if just the number is required
	if(empty($number)) {
		//if not then Echo out the results and the form
		$rating= theme('title', 'Ratings').theme('start_content').'<form action="" method="post"><table width="100%" align="center"><tr><td>Rating: '.$averageRating.'/5 ('.$total.' Votes)</td></tr>';
		if($user['post_rating'] && $new != 0) {
			$rating.='<tr><td>Choose your rating:<select name="rating">';
			for($i = 1; $i < 6; $i++){
				$rating.='<option value="'.$i.'">'.$i.'</option>';
			}
			$rating.='</select> <input type="submit" name="rating_form" value="Add Rating"/></td></tr>';
		}
		$rating .='</table></form>'.theme('end_content');
	} else {
		//if it isnt empty just return the rating
		$rating = $averageRating;
	}
	return $rating;
}
?>