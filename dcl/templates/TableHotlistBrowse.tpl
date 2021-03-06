{extends file="_Layout.tpl"}
{block name=title}Browse Hotlists{/block}
{block name=content}
<div class="dcl_filter">
	<span><label for="filterStatus">Hotlists:</label> {dcl_hotlist_link value=$VAL_SELECTEDTAGS selected=$VAL_SELECTEDTAGS browse=Y}</span>
</div>
{assign var=groupcount value=$groups|@count}
{assign var=colcount value=$columns|@count}
{if $rownum}{assign var=colcount value=$colcount+1}{/if}
{if $checks}{assign var=colcount value=$colcount+1}
	<form name="searchAction" method="post" action="{$URL_MAIN_PHP}"><input type="hidden" name="menuAction" value="" />{$VAL_VIEWSETTINGS}
{/if}
{if $caption ne ""}<h4>{$caption|escape}</h4>{/if}
<table class="table table-striped">
{strip}
{section loop=$columns name=col}
	{if $smarty.section.col.first}<thead>
	{if $toolbar}
	<tr><th colspan="{$colcount}"><div class="btn-group">
	{section loop=$toolbar name=tb}
	<a class="btn btn-default" href="javascript:;" onclick="document.forms.searchAction.elements.menuAction.value='{$toolbar[tb].link}'; submitBatch();">{$toolbar[tb].text|escape}</a>
	{/section}
	</div></th></tr>
	{/if}
	<tr>{if $checks}<th>{if $groupcount == 0}<input type="checkbox" name="group_check" onclick="javascript: toggle(this);">{/if}</th>{/if}{if $rownum}<th></th>{/if}{/if}{if !in_array($smarty.section.col.index, $groups)}<th>{$columns[col].title|escape}</th>{/if}{if $smarty.section.col.last}</tr></thead>{/if}
{/section}
{/strip}
{section loop=$footer name=item}
{if $smarty.section.item.first}<tfoot><tr>{if $checks}<td></td>{/if}{if $rownum}<td></td>{/if}{/if}{if !in_array($smarty.section.item.index, $groups)}<td class="{$columns[$smarty.section.item.index].type}">{$footer[item]|escape}</td>{/if}{if $smarty.section.item.last}</tr></tfoot>{/if}
{/section}
{section loop=$records name=row}{strip}
	{if $smarty.section.row.first}
		<tbody>
		{section loop=$groups name=group}
			{assign var=groupcol value=$groups[group]}
			{if $smarty.section.group.first}<tr class="group"><td colspan="{$colcount}">{/if}
			{if $checks}<input type="checkbox" name="group_check" onclick="javascript: toggle(this);">{/if}
			{$columns[$groupcol].title|escape}&nbsp;[&nbsp;{$records[row][$groupcol]|escape}&nbsp;]&nbsp;
			{if $smarty.section.group.last}</td></tr>{/if}
		{/section}
	{elseif count($groups) > 0}
		{assign var=newgroup value=false}
		{foreach from=$groups item=value key=key}
			{if $records[row][$value] != $records[row.index_prev][$value]}
				{assign var=newgroup value=true}
			{/if}
		{/foreach}
		{if $newgroup == "true"}
			</tbody><tbody>
			{section loop=$groups name=group}
				{assign var=groupcol value=$groups[group]}
				{if $smarty.section.group.first}<tr class="group"><td colspan="{$colcount}">{/if}
				{if $checks}<input type="checkbox" name="group_check" onclick="javascript: toggle(this);">{/if}
				{$columns[$groupcol].title|escape}&nbsp;[&nbsp;{$records[row][$groupcol]|escape}&nbsp;]&nbsp;
				{if $smarty.section.group.last}</td></tr>{/if}
			{/section}
		{/if}
	{/if}
	<tr>
	{if $checks}{assign var=woid value=$groupcount}{assign var=seq value=$groupcount+1}<td class="rowcheck"><input type="checkbox" name="selected[]" value="{$records[row][$woid]}.{$records[row][$seq]}"></td>{/if}
	{if $rownum}<td class="rownum">{$smarty.section.row.iteration}</td>{/if}
	{section loop=$records[row] name=item}
		{assign var=columnindex value=$smarty.section.item.index}
		{if $columnindex > 2}{assign var=columnindex value=$columnindex-2}{/if}
		{if $columnindex > 2}{assign var=columnindex value=$columnindex-1}{/if}
		{if $smarty.section.item.index != 1 && $smarty.section.item.index != 2 && $smarty.section.item.index != 4 && !in_array($smarty.section.item.index, $groups) && $smarty.section.item.index < (count($records[row]) + $VAL_ENDOFFSET)}<td class="{$columns[$columnindex].type}">
		{if $columnindex == 0}
			{if $records[row][0] == $smarty.const.DCL_ENTITY_WORKORDER}<a href="{$URL_MAIN_PHP}?menuAction=WorkOrder.Detail&jcn={$records[row][1]}&seq={$records[row][2]}">{$records[row][1]|escape}-{$records[row][2]|escape}</a>
			{elseif $records[row][0] == $smarty.const.DCL_ENTITY_TICKET}<a href="{$URL_MAIN_PHP}?menuAction=boTickets.view&ticketid={$records[row][1]}">{$records[row][1]|escape}</a>
			{else}{$records[row][item]|escape}
			{/if}
		{else}
			{if $columns[$columnindex].type == "html"}{$records[row][item]}
			{else}
				{if $columnindex == 9}{dcl_hotlist_link value=$records[row][item] selected=$VAL_SELECTEDTAGS browse=Y}
				{elseif $columnindex == 2 && $records[row][4] != ""}<a href="{$URL_MAIN_PHP}?menuAction=Project.Detail&id={$records[row][4]}">[{$records[row][4]|escape}] {$records[row][item]|escape}</a>
				{else}{$records[row][item]|escape}
				{/if}
			{/if}</td>
		{/if}
		{/if}
	{/section}
	</tr>
	{if $smarty.section.row.last}</tbody>{/if}
{/strip}{/section}
</table>
{if $checks}</form>{/if}
{/block}
