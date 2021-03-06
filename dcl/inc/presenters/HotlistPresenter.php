<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2012 Free Software Foundation
 *
 * Double Choco Latte is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Double Choco Latte is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * Select License Info from the Help menu to view the terms and conditions of this license.
 */

LoadStringResource('wo');

class HotlistPresenter
{
	public function Cloud(HotlistModel $model)
	{
		commonHeader();
		
		$allRecs = $model->FetchAllRows();

		$oTable = new TableHtmlHelper();
		$oTable->setCaption('Popular Hotlists');
		$oTable->addColumn(STR_CMMN_TAGS, 'html');
		$oTable->addColumn('Count', 'numeric');
		
		for ($i = 0; $i < count($allRecs); $i++)
		{
			$allRecs[$i][0] = '<a class="dcl-hotlist" href="' . menuLink('', 'menuAction=Hotlist.Browse&tag=' . urlencode($allRecs[$i][0])) . '">' . htmlspecialchars($allRecs[$i][0], ENT_QUOTES, 'UTF-8') . '</a>';
		}

		$oTable->setData($allRecs);
		$oTable->setShowRownum(true);
		$oTable->sTemplate = 'TableView.tpl';
		$oTable->render();
	}
	
	public function BrowseByTag(EntityHotlistModel $model)
	{
		commonHeader();

		$allRecs = $model->FetchAllRows();

		$oTable = new TableHtmlHelper();
		$oTable->setCaption('Browsing Hotlists');
		$oTable->addColumn(STR_CMMN_ID, 'numeric');
		$oTable->addColumn(STR_CMMN_NAME, 'string');
		$oTable->addColumn(STR_WO_PROJECT, 'string');
		$oTable->addColumn(STR_WO_STATUS, 'string');
		$oTable->addColumn(STR_WO_RESPONSIBLE, 'string');
		$oTable->addColumn(STR_WO_LASTACTION, 'date');
		$oTable->addColumn('Last Time Card By', 'string');
		$oTable->addColumn('Last Time Card Summary', 'string');
		$oTable->addColumn('Priority', 'numeric');
		$oTable->addColumn('Hotlists', 'string');

		$oHotlistDB = new EntityHotlistModel();
		for ($i = 0; $i < count($allRecs); $i++)
		{
			$sHotlists = $oHotlistDB->getTagsWithPriorityForEntity($allRecs[$i][0], $allRecs[$i][1], $allRecs[$i][2]);
			$allRecs[$i][8] = $model->FormatTimeStampForDisplay($allRecs[$i][8]);
			$allRecs[$i][12] = $sHotlists;
		}

		$oTable->setData($allRecs);
		$oTable->setShowRownum(true);

		if (!isset($_REQUEST['includeClosed']) || $_REQUEST['includeClosed'] == 'Y')
		{
			$oTable->assign('VAL_INCLUDECLOSED', 'Y');
		}
		else
		{
			$oTable->assign('VAL_INCLUDECLOSED', 'N');
		}

		$hotlistModel = new HotlistModel();
		$hotlists = $hotlistModel->ListByName($_REQUEST['tag']);
		
		$oTable->assign('VAL_SELECTEDTAGS', $hotlists);
		$oTable->sTemplate = 'TableHotlistBrowse.tpl';
		$oTable->render();
	}
	
	public function Prioritize(HotlistModel $hotlistModel, EntityHotlistModel $entityHotlistModel)
	{
		commonHeader();

		$smartyHelper = new SmartyHelper();
		$items = $entityHotlistModel->FetchAllRows();
		$smartyHelper->assignByRef('items', $items);
		$smartyHelper->assign('VAL_HOTLIST_ID', $hotlistModel->hotlist_id);
		$smartyHelper->assign('VAL_HOTLIST_NAME', $hotlistModel->hotlist_tag);
		$smartyHelper->Render('HotlistPrioritize.tpl');
	}
	
	public function Create()
	{
		commonHeader();
		
		$smartyHelper = new SmartyHelper();

		$smartyHelper->assign('menuAction', 'Hotlist.Insert');
		$smartyHelper->assign('VAL_TITLE', 'Add New Hotlist');
		$smartyHelper->assign('VAL_ACTIVE', 'Y');

		$smartyHelper->Render('HotlistForm.tpl');
	}
	
	public function Edit(HotlistModel $model)
	{
		commonHeader();
		
		$smartyHelper = new SmartyHelper();

		$smartyHelper->assign('menuAction', 'Hotlist.Update');
		$smartyHelper->assign('VAL_TITLE', 'Edit Hotlist');
		$smartyHelper->assign('VAL_ID', $model->hotlist_id);
		$smartyHelper->assign('VAL_NAME', $model->hotlist_tag);
		$smartyHelper->assign('VAL_DESCRIPTION', $model->hotlist_desc);
		$smartyHelper->assign('VAL_ACTIVE', $model->active);

		$smartyHelper->Render('HotlistForm.tpl');
	}
	
	public function Delete(HotlistModel $model)
	{
		commonHeader();
		
		ShowDeleteYesNo('Hotlist', 'Hotlist.Destroy', $model->hotlist_id, $model->hotlist_tag);
	}
}
