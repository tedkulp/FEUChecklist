<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if (!isset($gCms)) exit;

$feu = $this->GetModuleInstance('FrontEndUsers');
if (!$feu)
	return;

$user_id = get_parameter_value($params, 'user_id', '');

$checked_items = array();

$ret = $db->GetAll("SELECT item_id FROM " . cms_db_prefix() . "module_feuchecklist_checked_items WHERE user_id = ?", array($user_id));
if ($ret)
{
	foreach ($ret as $one_row)
	{
		$checked_items[] = $one_row['item_id'];
	}
}

if (isset($params['checked']))
{
	foreach ($params['checked'] as $k=>$v)
	{
		if ($v == '1')
		{
			if (!in_array($k, $checked_items))
			{
				$checked_items[] = $k;
			}
		}
		else
		{
			if (in_array($k, $checked_items))
			{
				$checked_items = array_diff($checked_items, array($k));
			}
		}
	}

	if (isset($params['submit']))
	{
		$db->Execute("DELETE FROM " . cms_db_prefix() . "module_feuchecklist_checked_items WHERE user_id = ?", array($user_id));
		foreach ($checked_items as $one_id)
		{
			$db->Execute("INSERT INTO " . cms_db_prefix() . "module_feuchecklist_checked_items (user_id, item_id, create_date, modified_date) VALUES (?,?,now(),now())", array($user_id, $one_id));
		}

		$params = array('tab_message'=> 'userupdated', 'active_tab' => 'users');
		$this->Redirect($id, 'defaultadmin', $returnid, $params);
	}
}

$items = $db->GetAll("SELECT * FROM " . cms_db_prefix() . "module_feuchecklist_items ORDER BY order_num");
$count = 0;

foreach ($items as &$one_item)
{
	$due_date = strtotime($one_item['due_date']);
	if ($due_date <= time())
	{
		$one_item['class'] = 'PastDue';
	}
	else if (($due_date - (60 * 60 * 24 * 6)) <= time()) //6 days
	{
		$one_item['class'] = 'DueSoon';
	}
	else
	{
		$one_item['class'] = 'DueLater';
	}

	$one_item['filelink'] = '&nbsp;';
	if (!empty($one_item['filename']))
	{
		$link = $this->GetItemUploadPath($one_item['id']) . '/' . $one_item['filename'];
		$text = $one_item['filename'];
		if (!empty($one_item['filedesc']))
		{
			$text = $one_item['filedesc'];
		}
		$one_item['filelink'] = "<a target=\"_blank\" href=\"{$link}\" title=\"{$one_item['filename']}\">{$text}</a>";
	}

	$checkbox = $this->CreateInputHidden($id, 'checked['.$one_item['id'].']', 0) .
		$this->CreateInputCheckbox($id, 'checked['.$one_item['id'].']', '1', in_array($one_item['id'], $checked_items));
	$one_item['checkbox'] = $checkbox;

	if (in_array($one_item['id'], $checked_items))
	{
		$one_item['class'] = 'Ticked';
	}

	$one_item['item_count'] = $count;
	$count++;
}

$smarty->assign('items', $items);
$smarty->assign('startform', $this->CreateFormStart($id, 'edituser', $returnid, 'post', '', false, '', array('user_id' => $user_id)));
$smarty->assign('endform', $this->CreateFormEnd());
$smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', $this->Lang('submit')));

echo $this->ProcessTemplate('checklist.tpl');

# vim:ts=4 sw=4 noet
