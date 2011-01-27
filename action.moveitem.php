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

$item_id = get_parameter_value($params, 'item_id', '');
$move = get_parameter_value($params, 'move', '');

$order = $db->GetOne("SELECT order_num FROM ".cms_db_prefix()."module_feuchecklist_items WHERE id = ?", array($item_id));
$time = $db->DBTimeStamp(time());

if ($move == 'up')
{
	$query = 'UPDATE '.cms_db_prefix().'module_feuchecklist_items SET order_num = (order_num + 1), modified_date = '.$time.' WHERE order_num = ?';
	$db->Execute($query, array($order - 1));
	$query = 'UPDATE '.cms_db_prefix().'module_feuchecklist_items SET order_num = (order_num - 1), modified_date = '.$time.' WHERE id = ?';
	$db->Execute($query, array($item_id));
}
else if ($move == 'down')
{
	$query = 'UPDATE '.cms_db_prefix().'module_feuchecklist_items SET order_num = (order_num - 1), modified_date = '.$time.' WHERE order_num = ?';
	$db->Execute($query, array($order + 1));
	$query = 'UPDATE '.cms_db_prefix().'module_feuchecklist_items SET order_num = (order_num + 1), modified_date = '.$time.' WHERE id = ?';
	$db->Execute($query, array($item_id));
}

$params = array('tab_message'=> 'itemmoved', 'active_tab' => 'items');
$this->Redirect($id, 'defaultadmin', $returnid, $params);
