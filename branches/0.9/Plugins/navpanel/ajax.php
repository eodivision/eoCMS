<?php
if($user['admin_panel']) {
$fields = explode('&', $_REQUEST['order']);
	$order  = 0;
	foreach($fields as $field)
	{
		$order++;
		$field_key_value = explode('=', $field);
		$level 		 = urldecode($field_key_value[0]);
		$id 		 = urldecode($field_key_value[1]);
$result = call('sql_query', "UPDATE navigation_menu SET item_order=". $order . " WHERE id=". $id);
if($result) {
echo true;
}
else {
echo false;
}
}
}
?>