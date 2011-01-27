<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if( !isset($gCms) ) exit;

$itemarray = array();

$query = "SELECT * FROM ".cms_db_prefix()."module_feuchecklist_items ORDER BY order_num ASC";
$dbresult = $db->Execute($query);

while ($dbresult && $row = $dbresult->FetchRow())
{
	$onerow = new stdClass();

	foreach($row as $k=>$v)
	{
		$onerow->$k = $v;
	}

	$onerow->editurl = $this->CreateLink($id, 'edititem', $returnid, '', array('item_id' => $row['id']), '', true);
	$onerow->editlink = $this->CreateLink($id, 'edititem', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/edit.gif', $this->Lang('edit'),'','','systemicon'), array('item_id' => $row['id']));
	$onerow->deletelink = $this->CreateLink($id, 'deleteitem', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/delete.gif', $this->Lang('delete'),'','','systemicon'), array('item_id' => $row['id']), $this->Lang('areyousure'));

	$itemarray[] = $onerow;
}

$smarty->assign_by_ref('items', $itemarray);
$smarty->assign('itemcount', count($itemarray));
$smarty->assign('addlink', $this->CreateLink($id, 'additem', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/newfolder.gif', $this->Lang('additem'),'','','systemicon'), array(), '', false, false, '') .' '. $this->CreateLink($id, 'additem', $returnid, $this->Lang('additem'), array(), '', false, false, 'class="pageoptions"'));

#Display template
echo $this->ProcessTemplate('itemlist.tpl');

# vim:ts=4 sw=4 noet
