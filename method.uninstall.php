<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if (!isset($gCms)) exit;

$db =& $this->GetDb();

$dict = NewDataDictionary( $db );

$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_feuchecklist_checked_items");
$dict->ExecuteSQLArray($sqlarray);

$sqlarray = $dict->DropTableSQL(cms_db_prefix()."module_feuchecklist_items");
$dict->ExecuteSQLArray($sqlarray);

$this->DeleteTemplate();
$this->RemovePreference();
$this->RemovePermission('Modify FEUChecklist');

# vim:ts=4 sw=4 noet
