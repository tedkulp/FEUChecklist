<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if (!isset($gCms)) exit;

if (!$this->CheckPermission('Modify FEUChecklist'))
{
	echo $this->ShowErrors($this->Lang('needpermission', array('Modify FEUChecklist')));
	return;
}

if (isset($params['cancel']))
{
	$this->Redirect($id, 'defaultadmin', $returnid);
}

$field_names = array('subject', 'reference');

$item_id = get_parameter_value($params, 'item_id', '');
$subject = get_parameter_value($params, 'subject', '');
$reference = get_parameter_value($params, 'reference', '');

if (isset($params['submit']))
{
	// submit is pressed.
	$error = '';

	if ($subject == '')
	{
		$error = $this->Lang('nosubjectgiven');
	}

	if ( $error )
	{
		echo $this->ShowErrors($error);
	}
	else
	{
		$time = trim($db->DBTimeStamp(time()), "'");
		$update_string = '';
		foreach($field_names as $the_name)
		{
			$update_string .= $the_name . ' = ?,';
		}
		$update_string .= 'modified_date = ?';
		$query = 'UPDATE ' . cms_db_prefix() . 'module_feuchecklist_items SET ' . $update_string . ' WHERE id = ?';
		$fields_to_send = array_values(compact($field_names));
		$fields_to_send[] = $time;
		$fields_to_send[] = $item_id;
		$dbr = $db->Execute($query, $fields_to_send);

		if( !$dbr )
		{
			echo "QUERY: ".$db->sql.'<br/>';
			echo "ERROR: ".$db->ErrorMsg().'<br/>';
			die();
		}

		$params = array('tab_message' => 'itemupdated', 'active_tab' => 'items');
		$this->Redirect($id, 'defaultadmin', $returnid, $params);
	}
}
else
{
	$query = 'SELECT * FROM '.cms_db_prefix().'module_feuchecklist_items WHERE id = ?';
	$row = $db->GetRow($query, array($item_id));

	if ($row)
	{
		$subject = $row['subject'];
		$reference = $row['reference'];
	}
}

#Display template
$smarty->assign('startform', $this->CreateFormStart($id, 'edititem', $returnid));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('inputsubject', $this->CreateInputText($id, 'subject', $subject, 30, 255));
$smarty->assign('inputreference', $this->CreateInputText($id, 'reference', $reference, 30, 255));

$smarty->assign('hidden', $this->CreateInputHidden($id, 'item_id', $item_id));
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('edititem.tpl');

# vim:ts=4 sw=4 noet
