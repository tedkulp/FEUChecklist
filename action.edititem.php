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

$field_names = array('subject', 'reference', 'due_date');

$item_id = get_parameter_value($params, 'item_id', '');
$subject = get_parameter_value($params, 'subject', '');
$reference = get_parameter_value($params, 'reference', '');
$due_date = get_parameter_value($params, 'due_date', '');
if (isset($_POST['due_dateMonth']))
{
	$due_date = sprintf('%04d-%02d-%02d 23:59:59', $_POST['due_dateYear'], $_POST['due_dateMonth'], $_POST['due_dateDay']);
}

if (isset($params['submit']))
{
	// submit is pressed.
	$error = '';

	if ($subject == '')
	{
		$error = $this->Lang('nosubjectgiven');
	}

	//No errors so far, now handle the upload (if any)
	//We keep no file still valid so we can handle webinars
	$filename = '';
	if (!$error)
	{
		if (isset($_FILES[$id.'file']) && $_FILES[$id.'file']['tmp_name'] != '' && $_FILES[$id.'file']['name'] != '')
		{
			$filename = $_FILES[$id.'file']['name'];
			$size = $_FILES[$id.'file']['size'];
			$mime_type = $_FILES[$id.'file']['type'];

			$destdir = $this->GetItemUploadDirectory($item_id);
			cge_dir::mkdirr($destdir);
			if (!is_dir($destdir))
				die('directory still does not exist');
			$handler = new cg_fileupload($id, $destdir);
			//$handler->set_accepted_filetypes($this->GetPreference('allowed_filetypes'));
			$res = $handler->handle_upload('file');
			$err = $handler->get_error();
			if (!$res && $err != cg_fileupload::NOFILE)
			{
				$error = sprintf("%s: %s",$this->Lang('file'), $this->GetUploadErrorMessage($err));
			}
			else if (!$res)
			{
				$error = $this->Lang('undefinederroruploading');
			}
			else
			{
				$filename = $res;
				$field_names[] = 'filename';
				$old_filename = $db->GetOne("SELECT filename FROM ".cms_db_prefix() . "module_feuchecklist_items WHERE id = ?", array($item_id));
				if ($old_filename && $old_filename != $filename)
					@unlink(cms_join_path($destdir, $old_filename));
			}
		}
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
		$filename = $row['filename'];
		$due_date = $row['due_date'];
	}
}

#Display template
$smarty->assign('startform', $this->CreateFormStart($id, 'edititem', $returnid, 'post', 'multipart/form-data'));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('inputsubject', $this->CreateInputText($id, 'subject', $subject, 30, 255));
$smarty->assign('inputreference', $this->CreateInputText($id, 'reference', $reference, 30, 255));
$smarty->assign('inputfile', $this->CreateFileUploadInput($id, 'file', '', 80));
$smarty->assign('filename', $filename);
$smarty->assign('inputdue_date', $this->CreateInputText($id, 'due_date', $due_date, 30, 255));
$smarty->assign('selectdue_date', $due_date);

$smarty->assign('hidden', $this->CreateInputHidden($id, 'item_id', $item_id));
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('edititem.tpl');

# vim:ts=4 sw=4 noet
