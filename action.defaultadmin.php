<?php  /* -*- Mode: PHP; tab-width: 4; c-basic-offset: 2 -*- */
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if (!isset($gCms)) exit;

$tab = '';
if (isset($params['active_tab']))
{
	$tab = $params['active_tab'];
	$this->SetCurrentTab($tab);
}

#The tabs
echo $this->StartTabHeaders();
echo $this->SetTabHeader('items', $this->Lang('items'));
echo $this->SetTabHeader('config', $this->Lang('config'));
echo $this->EndTabHeaders();

#The content of the tabs
echo $this->StartTabContent();
echo $this->StartTab('items', $params);
include(dirname(__FILE__).'/function.admin_itemstab.php');
echo $this->EndTab();
echo $this->StartTab('config', $params);
include(dirname(__FILE__).'/function.admin_configtab.php');
echo $this->EndTab();
echo $this->EndTabContent();

# vim:ts=4 sw=4 noet
