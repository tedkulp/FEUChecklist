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

$destdir = $this->GetItemUploadDirectory($item_id);
$old_filename = $db->GetOne("SELECT filename FROM ".cms_db_prefix() . "module_feuchecklist_items WHERE id = ?", array($item_id));
if ($old_filename)
{
	@unlink(cms_join_path($destdir, $old_filename));
	@rmdir($destdir);
}

$db->Execute("DELETE FROM ".cms_db_prefix()."module_feuchecklist_checked_items WHERE item_id = ?", array($item_id));

$db->Execute("DELETE FROM ".cms_db_prefix()."module_feuchecklist_items WHERE id = ?", array($item_id));

$params = array('tab_message'=> 'itemdeleted', 'active_tab' => 'items');
$this->Redirect($id, 'defaultadmin', $returnid, $params);
