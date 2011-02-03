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
{$submit}

<table cellspacing="0" class="pagetable">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>{$mod->Lang('subject')}</th>
			<th>{$mod->Lang('reference')}</th>
			<th>{$mod->Lang('due_date')}</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$items item=entry}
		<tr class="{$entry.class}">
			<td>{$entry.item_count}</td>
			<td>{$entry.subject}</td>
			<td>{$entry.reference}</td>
			<td>{$entry.due_date|date_format}</td>
			<td>{$entry.filelink}</td>
			<td>{$entry.checkbox}</td>
		</tr>
	{/foreach}
	</tbody>
</table>

{$submit}
{$endform}
