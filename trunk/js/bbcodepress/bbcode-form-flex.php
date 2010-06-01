<?php
# form include for eoCMS
# id=newtopic and id=editpost should be change to id=bbcode-form
# subject & message names and id should be same for new and update
#
# copy EDITPOST_LANG or NEWTOPIC_LANG to BBCODE_lang before include
# both BBCODE_form and BBCODE_LANG can be set before include
# set overrides as defined below before include
#
# output:
#	BBCODE_header
#	BBCODE_body

if(!is_array($BBCODE_form)){
	$BBCODE_form = array(
		'id' => 'newtopic', # for eoCMS, should be 'bbcode-form' 
		'name' => '',
		'action' => '',
		'method' => 'POST',
		'style' => '',
		'subject_id' => 'topic_title', # for eoCMS
		'subject_name' => 'topic_title',
		'subject_maxlength' => '80',
		'subject_style' => '',
		'subject' => ((isset($fetch['subject']))?$fetch['subject']:$_POST['subject']), # for eoCMS
		'message_id' => 'message',
		'message_name' => 'message',
		'message_cols' => '40',
		'message_rows' => '12',
		'message_style' => '',
		'message' => ((isset($fetch['message']))?$fetch['message']:$_POST['message']), # for eoCMS
		'disable_id' => '',
		'disable_name' => 'smiley',
		'disable_style' => '',
		'submit_id' => '',
		'submit_name' => '',
		'submit_style' => '',
		'token' => (defined("IN_ECMS"))?call('form_token'):'' # for eoCMS
	);
}

if($BBCODE_action)
	$BBCODE_form['action'] = $BBCODE_action;
if($BBCODE_method)
	$BBCODE_form['method'] = $BBCODE_method;
if($BBCODE_maxlength)
	$BBCODE_form['maxlength'] = $BBCODE_maxlength;
if($BBCODE_token)
	$BBCODE_form['token'] = $BBCODE_token;

if(!is_array($BBCODE_LANG)){
	if(is_array($BBCODE_lang))
		$BBCODE_LANG = $BBCODE_lang;
	else
	$BBCODE_LANG = array(
		'subject' => 'Topic Title',
		'smileys' => 'Smileys',
		'message' => 'Message',
		'disable_smileys' => 'Disable Smileys in post'
		'btn_submit' => 'Post Topic'
	);
}

if($BBCODE_update){
	$BBCODE_form['id'] = 'editpost'; # for eoCMS
	$BBCODE_form['subject_id'] = 'subject'; # for eoCMS
	$BBCODE_form['subject_name'] = 'subject';
	if(!is_array($BBCODE_lang)){
		$BBCODE_LANG['subject'] = 'Subject';
		$BBCODE_LANG['btn_submit'] = 'Update Post';
	}
	if($BBCODE_disable_smileys!==false) $BBCODE_disable_smileys = true;
}

if($BBCODE_subject)
	$BBCODE_form['subject'] = $BBCODE_subject;
if($BBCODE_message)
	$BBCODE_form['message'] = $BBCODE_message;

if(!$BBCODE_path)
	$BBCODE_path = './js/'; # for eoCMS

if(!$BBCODE_head && !$BBCODE_override){
	$BBCODE_head .= '<script language=JavaScript src=' . $BBCODE_path . 'bbcodepress/bbcodepress-lite.js></script>';
}
if(!$BBCODE_buttons && !$BBCODE_override){
	include_once($BBCODE_path . 'bbcodepress/bbcodepress-lite.php');
	$textarea_name = $BBCODE_form['message_id'];
	$bbcode_image_path = (isset($settings['site_theme']))?'./themes/' . $settings['site_theme'] . '/images/':'./images/'; # for eoCMS
	$BBCODE_buttons = retHtmlButtons(loadButtons($BBCODE_path . 'bbcodepress/bbcode.lst'),'button'); # for eoCMS
}
if(!$BBCODE_smileys && !$BBCODE_override){
	include_once($BBCODE_path . 'bbcodepress/bbcodepress-lite.php');
	$textarea_name = $BBCODE_form['message_id'];
	$smiley_image_path = './images/emoticons/'; # for eoCMS
	$BBCODE_smileys = retHtmlButtons(loadButtons($BBCODE_path . 'bbcodepress/smiley.lst'),'smiley'); # for eoCMS
}

if(!$BBCODE_override)
	$BBCODE_override = $BBCODE_smileys . $BBCODE_buttons;

$BBCODE_body .= '<form action="' . $BBCODE_form['action'] . '" method="' . $BBCODE_form['method'] . '" name="' . $BBCODE_form['name'] . ' id="' . $BBCODE_form['id'] . '" style="' . $BBCODE_form['style'] . '>
<table id=bbcode-area>
  <tr>
    <td id=bbcode-word-topic>' . $BBCODE_LANG['subject'] . ':</td>
    <td id=bbcode-title><input type="text" name="' . $BBCODE_form['subject_name'] . '" class="required bbcode-subject" id="' . $BBCODE_form['subject_id'] . '" maxlength="' . $BBCODE_form['subject_maxlength'] . '" value="' . $BBCODE_form['subject'] . '" style="' . $BBCODE_form['subject_style'] . '"></td>
  </tr>
  <tr>
    <td id=bbcode-word-message>' . $BBCODE_LANG['message'] . ':</td>
    <td id=bbcode-message-area>' . $BBCODE_overide . '<textarea rows="' . $BBCODE_form['rows'] . '" cols="' . $BBCODE_form['cols'] . '" name="' . $BBCODE_form['message_name'] . '" id="' . $BBCODE_form['message_id'] . '" type="text" class="required bbcode-message" style="' . $BBCODE_form['message_style'] . '">' . $BBCODE_form['message'] . '</textarea></td>
  </tr>';
if(!$BBCODE_disable_smileys)
$body .= '
  <tr>
    <td colspan=2 id=bbcode-disable-smiley>' . $BBCODE_LANG['disable_smileys'] . '? <input type="checkbox" name="' . $BBCODE_form['disable_name'] . '" id="' . $BBCODE_form['disable_id'] . '" class="bbcode-no-smiley" style="' . $BBCODE_form['disable_style'] . '></td>
  </tr>';
$body .= '
  <tr>
    <td colspan=2 id=bbcode-submit><p align="center" class="bbcode-btn-center-padding"><input type="submit" value="' . $BBCODE_LANG['btn_submit'] . '" name="' . $BBCODE_form['submit_name'] . '" id="' . $BBCODE_form['submit_id'] . '" class="bbcode-submit-btn" style="' . $BBCODE_form['submit_style'] . '"></p></td>
  </tr>' . $BBCODE_form['token'] . '
</table>
</form>';

?>