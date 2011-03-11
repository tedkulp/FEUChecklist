<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if( !isset($gCms) ) exit;

$feu = $this->GetModuleInstance('FrontEndUsers');
if (!$feu)
	return;

$userarray = array();

$dbresult = $feu->GetUsersInGroup();

if (count($dbresult))
{
	foreach ($dbresult as &$row)
	{
		$onerow = new stdClass();

		foreach($row as $k=>$v)
		{
			$onerow->$k = $v;
		}

		$onerow->editurl = $this->CreateLink($id, 'edituser', $returnid, '', array('user_id' => $row['id']), '', true);
		$onerow->editlink = $this->CreateLink($id, 'edituser', $returnid, $gCms->variables['admintheme']->DisplayImage('icons/system/edit.gif', $this->Lang('edit'),'','','systemicon'), array('user_id' => $row['id']));

		$userarray[] = $onerow;
	}
}

$smarty->assign_by_ref('users', $userarray);
$smarty->assign('usercount', count($userarray));

#Display template
echo $this->ProcessTemplate('userlist.tpl');

# vim:ts=4 sw=4 noet
