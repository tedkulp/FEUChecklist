<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if( !isset($gCms) ) exit;

if (isset($params['cleanform']))
{
	$db->Execute("DELETE FROM " . cms_db_prefix() . "module_feuchecklist_checked_items");
	echo '<p><strong>' . $this->Lang('all_items_cleared') . '</strong></p>';
}

echo $this->CreateFormStart($id, 'defaultadmin', $returnid, 'post', '', false, '', array('active_tab' => 'config'));
$button = $this->CreateInputSubmit($id, 'cleanform', $this->Lang('clearform'), '', '', $this->Lang('areyousure'));
echo '
	<div class="pageoverflow">
		<p class="pagetext">*' . $this->Lang('clear_all_entries') . ':</p>
		<p class="pageinput">' . $button . '</p>
	</div>
	';
echo $this->CreateFormEnd();

# vim:ts=4 sw=4 noet
