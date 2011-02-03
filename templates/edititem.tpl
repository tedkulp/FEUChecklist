{*
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
*}

{$startform}
	<div class="pageoverflow">
		<p class="pagetext">*{$mod->Lang('subject')}:</p>
		<p class="pageinput">{$inputsubject}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mod->Lang('reference')}:</p>
		<p class="pageinput">{$inputreference}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">*{$mod->Lang('due_date')}:</p>
		<p class="pageinput">{html_select_date prefix='due_date' time=$selectdue_date start_year='-5' end_year='+5'}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mod->Lang('file')}:</p>
		{if $filename}
			<p class="pageinput"><em>{$mod->Lang('existingfile')}:<br />{$filename}</em></p>
		{/if}
		<p class="pageinput"><br />{$inputfile}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">{$mod->Lang('filedesc')}:</p>
		<p class="pageinput">{$inputfiledesc}</p>
	</div>
	<div class="pageoverflow">
		<p class="pagetext">&nbsp;</p>
		<p class="pageinput">{$hidden}{$submit}{if isset($cancel)}{$cancel}{/if}</p>
	</div>
{$endform}
