<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

$cgextensions = cms_join_path($gCms->config['root_path'],'modules', 'CGExtensions','CGExtensions.module.php');
if (!is_readable($cgextensions))
{
	echo '<h1><font color="red">ERROR: The CGExtensions module could not be found.</font></h1>';
	return;
}
require_once($cgextensions);

class FEUChecklist extends CGExtensions
{
	function GetName()
	{
		return 'FEUChecklist';
	}

	function GetChangeLog()
	{
		return $this->ProcessTemplate('changelog.tpl');
	}
	
	function GetFriendlyName()
	{
		return $this->Lang('friendlyname');
	}

	function GetVersion()
	{
		return '0.1';
	}

	function GetHelp()
	{
		return $this->Lang('help');
	}

	function GetAuthor()
	{
		return 'Ted Kulp';
	}

	function GetAuthorEmail()
	{
		return 'ted@shiftrefresh.net';
	}

	function IsPluginModule()
	{
		return true;
	}

	function HasAdmin()
	{
		return true;
	}

	function IsAdminOnly()
	{
		return false;
	}

	function GetAdminSection()
	{
		return 'content';
	}

	function GetAdminDescription()
	{
		return $this->Lang('moddescription');
	}

	function MinimumCMSVersion()
	{
		return "1.9.2";
	}

	function GetDependencies()
	{
		return array('CGExtensions' => '1.19.4', 'CGSimpleSmarty' => '1.4.4', 'FrontEndUsers' => '1.12.8');
	}

	function VisibleToAdminUser()
	{
		return true;
		//$this->CheckPermission('Modify Site Preferences');
	}

	function SetParameters()
	{
		$this->RegisterModulePlugin();
	}

	function RegisterEvents()
	{
		$this->AddEventHandler('FrontEndUsers', 'OnCreateUser', false);
		$this->AddEventHandler('FrontEndUsers', 'OnUpdateUser', false);
		$this->AddEventHandler('FrontEndUsers', 'OnDeleteUser', false);
	}

	function DoEvent($originator, $eventname, &$params)
	{
		if ($originator == 'FrontEndUsers')
		{
			switch ($eventname)
			{
				case 'OnCreateUser':
					//$params['name']
					//$params['id']
					$this->AddUser($params['id']);
					break;

				case 'OnDeleteUser':
					//$params['username']
					//$params['id']
					//$params['props'] = array()
					$this->DeleteUser($params['id']);
					break;
			}
		}
	}

	function AddUser($user_id)
	{
	}

	function DeleteUser($user_id)
	{
		$gCms = cmsms();
		$db = $gCms->GetDb();
		$db->Execute("DELETE FROM " . cms_db_prefix() . "module_feuchecklist_checked_items WHERE user_id = ?", array($user_id));
	}

	function GetItemUploadPath($item_id = '')
	{
		$gCms = cmsms();
		$config = $gCms->GetConfig();
		if ($item_id == '')
			$item_id = 'tmp';
		return $config['uploads_url'] . '/feu_checklist_files/item_' . $item_id;
	}

	function GetItemUploadDirectory($item_id = '')
	{
		$gCms = cmsms();
		$config = $gCms->GetConfig();
		if ($item_id == '')
			$item_id = 'tmp';
		return cms_join_path($config['uploads_path'], 'feu_checklist_files', 'item_' . $item_id);
	}

	function ItemUploadDirectoryExists($item_id = '')
	{
		$path = $this->GetItemUploadDirectory($item_id);
		return is_dir($path);
	}

}

# vim:ts=4 sw=4 noet
