{*
# FEUChecklist - A CMS Made Simple Module
# Created By: Ted Kulp <ted@shiftrefresh.net>
# Copyright (c) 2011
#
# CMS - CMS Made Simple
# (c)2004 by Ted Kulp (ted@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
*}

{if $itemcount > 0}
<table cellspacing="0" class="pagetable">
	<thead>
		<tr>
			<th>{$mod->Lang('id')}</th>
			<th>{$mod->Lang('username')}</th>
			<th>{$mod->Lang('num_checked')}</th>
			<th class="pageicon">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$users item=entry}
		{cycle values="row1,row2" assign='rowclass'}
		<tr class="{$rowclass}" onmouseover="this.className='{$rowclass}hover';" onmouseout="this.className='{$rowclass}';">
			<td>{$entry->id}</td>
			<td><a href="{$entry->editurl}">{$entry->username}</a></td>
			<td>{$entry->count}</td>
			<td>{$entry->editlink}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
{/if}
