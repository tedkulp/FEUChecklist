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
		return true;
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
	}

}

# vim:ts=4 sw=4 noet
