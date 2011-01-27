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

	//No errors so far, now handle the upload (if any)
	//We keep no file still valid so we can handle webinars
	$filename = '';
	$size = 0;
	$mime_type = '';
	$destdir = $this->GetItemUploadDirectory($item_id);
	if (!$error)
	{
		if (isset($_FILES[$id.'file']))
		{
			$filename = $_FILES[$id.'file']['name'];
			$size = $_FILES[$id.'file']['size'];
			$mime_type = $_FILES[$id.'file']['type'];
		}
		cge_dir::mkdirr($destdir);
		if (!is_dir($destdir))
			die('directory still does not exist');
		$handler = new cg_fileupload($id, $destdir);
		//$handler->set_accepted_filetypes($this->GetPreference('allowed_filetypes'));
		$res = $handler->handle_upload('file');
		var_dump($res);
		$err = $handler->get_error();
		var_dump($err);
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
		}
	}

	if ( $error )
	{
		echo $this->ShowErrors($error);
	}
	else
	{
		$max_order_num = $db->GetOne("SELECT max(order_num) FROM " . cms_db_prefix() . "module_feuchecklist_items");
		if (!$max_order_num)
		{
			$max_order_num = 0;
		}

		$max_order_num++;

		$time = trim($db->DBTimeStamp(time()), "'");
		$query = 'INSERT INTO ' . cms_db_prefix() . 'module_feuchecklist_items ('.implode(',', $field_names) .',create_date,modified_date,order_num) VALUES ('.implode(',',array_fill(0, count($field_names), '?')).',?,?,?)';
		$fields_to_send = array_values(compact($field_names));
		$fields_to_send[] = $time;
		$fields_to_send[] = $time;
		$fields_to_send[] = $max_order_num;
		$dbr = $db->Execute($query, $fields_to_send);

		if( !$dbr )
		{
			echo "QUERY: ".$db->sql.'<br/>';
			echo "ERROR: ".$db->ErrorMsg().'<br/>';
			die();
		}
		
		$new_id = $db->Insert_ID();

		if ($filename != '')
		{
			$old_destdir = $destdir;
			$new_destdir = $this->GetItemUploadDirectory($new_id);
			cge_dir::mkdirr($new_destdir);
			@rename($old_destdir . DIRECTORY_SEPARATOR . $filename, $new_destdir . DIRECTORY_SEPARATOR . $filename);
		}

		$params = array('tab_message'=> 'itemadded', 'active_tab' => 'items');
		$this->Redirect($id, 'defaultadmin', $returnid, $params);
	}
}

#Display template
$smarty->assign('startform', $this->CreateFormStart($id, 'additem', $returnid, 'post', 'multipart/form-data'));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('inputsubject', $this->CreateInputText($id, 'subject', $subject, 30, 255));
$smarty->assign('inputreference', $this->CreateInputText($id, 'reference', $reference, 30, 255));
$smarty->assign('inputfile', $this->CreateFileUploadInput($id, 'file', '', 80));

$smarty->assign('hidden', '');
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', lang('submit')));
$smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', lang('cancel')));

echo $this->ProcessTemplate('edititem.tpl');

# vim:ts=4 sw=4 noet
