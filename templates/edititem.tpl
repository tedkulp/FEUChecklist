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
		<p class="pagetext">&nbsp;</p>
		<p class="pageinput">{$hidden}{$submit}{if isset($cancel)}{$cancel}{/if}</p>
	</div>
{$endform}
