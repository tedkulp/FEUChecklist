<?php // -*- mode:php; tab-width:4; indent-tabs-mode:t; c-basic-offset:4; -*-
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org

if (!isset($gCms)) exit;

$db = $this->GetDb();

$dict = NewDataDictionary($db);
$flds = "
	id I KEY AUTO,
	subject C(255),
	reference C(255),
	links X,
	filename C(255),
	filedesc C(255),
	order_num I NOT NULL,
	due_date " . CMS_ADODB_DT . ",
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . "
";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_feuchecklist_items", $flds);
$dict->ExecuteSQLArray($sqlarray);

$dict = NewDataDictionary($db);
$flds = "
	item_id I,
	user_id I,
	create_date " . CMS_ADODB_DT . ",
	modified_date " . CMS_ADODB_DT . "
";

$sqlarray = $dict->CreateTableSQL(cms_db_prefix()."module_feuchecklist_checked_items", $flds);
$dict->ExecuteSQLArray($sqlarray);

$this->CreatePermission('Modify FEUChecklist', 'Modify FEUChecklist');

$this->RegisterEvents();
