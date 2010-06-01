<?php
# form include for eoCMS
# id=newtopic and id=editpost change to id=bbcode-form
# need to sort out names & ids for update - see:bbcode-for-flex.php 

include_once($BBCODE_path . 'bbcodepress/bbcodepress-lite.php');
$textarea_name = 'dataBox';
$smiley_image_path = './images/emoticons/';
$bbcode_image_path = './themes/' . $settings['site_theme'] . '/images/';

if(!$BBCODE_override){
	$head .= '<script language=JavaScript src=bbcodepress-lite.js></script>';
	$BBCODE_override = getStandard('./js/bbcodepress/');
//	$BBCODE_override = getStandard('./js/bbcodepress/','-eocms');
}

$body .= '<form action="" method="post" id="bbcode-form">
<table id=bbcode-area>
  <tr>
    <td id=bbcode-topic>' . $BBCODE_LANG['topic_subject'] . ':</td>
    <td id=bbcode-title><input type="text" name="topic_title" class="required" id="topic_title" maxlength="80" value="' . $_POST['topic_title'] . '"></td>
  </tr>
  <tr>
    <td id=bbcode-message>' . $BBCODE_LANG['message'] . ':</td>
    <td id=bbcode-message-area>' . call('form_token') . $BBCODE_override . '<textarea rows="12" cols="40" name="message" id="message" type="text" class="required">' . $_POST['message'] . '</textarea></td>
  </tr>';
if($BBCODE_disable_smileys)
$body .= '
  <tr>
    <td colspan=2 id=bbcode-disable-smiley>' .$BBCODE_LANG['disable_smileys'] . '? <input type="checkbox" name="smiley" id=bbcode-no-smiley></td>
  </tr>';
$body .= '
  <tr>
    <td colspan=2 id=bbcode-submit><p align="center" class="bbcode-btn-center-padding"><input type="submit" value="' . $BBCODE_LANG['btn_submit'] . '" id=bbcode-submit-btn></p></td>
  </tr>
</table>
</form>';

?>