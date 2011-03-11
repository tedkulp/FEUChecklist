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

$total_count = $db->GetOne("SELECT count(*) FROM ".cms_db_prefix()."module_feuchecklist_items");
$counts = $db->GetAll("SELECT user_id, count(*) as the_count FROM ".cms_db_prefix()."module_feuchecklist_checked_items GROUP BY user_id ORDER BY user_id");

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

		$count = '0 / ' . $total_count;
		foreach ($counts as $one_count)
		{
			if ($one_count['user_id'] == $row['id'])
			{
				$count = $one_count['the_count'] . ' / ' . $total_count;
				break;
			}
		}
		$onerow->count = $count;
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
