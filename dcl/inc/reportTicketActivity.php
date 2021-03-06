<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2004 Free Software Foundation
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

LoadStringResource('wost');
LoadStringResource('tck');

class reportTicketActivity
{
	function getparameters($needHdr = true)
	{
		global $dcl_info;

		if ($needHdr == true)
			commonHeader();

		$objPersonnel = new PersonnelHtmlHelper();
		
		$t = new SmartyHelper();
		$t->assign('CMB_RESPONSIBLE', $objPersonnel->Select(DCLID, 'responsible', 'lastfirst', 0, false));

		if (IsSet($_REQUEST['begindate']) && ($beginDate = Filter::ToDate($_REQUEST['begindate'])) !== null)
			$t->assign('VAL_BEGINDATE', $begindate);
		else
			$t->assign('VAL_BEGINDATE', '');

		if (IsSet($_REQUEST['enddate']) && ($beginDate = Filter::ToDate($_REQUEST['enddate'])) !== null)
			$t->assign('VAL_ENDDATE', $enddate);
		else
			$t->assign('VAL_ENDDATE', '');
		
		$t->Render('TicketActivity.tpl');
	}

	function execute()
	{
		commonHeader();

		if (($begindate = Filter::ToDate($_REQUEST['begindate'])) === null ||
			($enddate = Filter::ToDate($_REQUEST['enddate'])) === null ||
			($responsible = Filter::ToInt($_REQUEST['responsible'])) === null
			)
		{
			ShowError('All fields are required.');
			$this->GetParameters(false);
			return;
		}

		$oMeta = new DisplayHelper();

		$obj = new TicketsModel();
		$objT = new TicketResolutionsModel();

		$sColumns = $obj->SelectAllColumns('a.');
		$query = 'select ' . $sColumns . ' from tickets a, ticketresolutions b where a.ticketid=b.ticketid and b.loggedby=' . $responsible;
		$query .= ' and b.loggedon between ' . $obj->DisplayToSQL($begindate . ' 00:00:00') . ' and ' . $obj->DisplayToSQL($enddate . ' 23:59:59');
		$query .= ' order by a.ticketid';

		if ($obj->Query($query) != -1)
		{
			if ($obj->next_record())
			{
				$arrayIndex = -1;
				$count = 0;
				$prevTicketID = -1;
				do
				{
					$obj->GetRow();

					if ($obj->ticketid != $prevTicketID)
					{
						$prevTicketID = $obj->ticketid;
						$arrayIndex++;

						$reportArray[$arrayIndex][0] = $oMeta->GetProduct($obj->product);

						$aOrg = $oMeta->GetOrganization($obj->account);
						$reportArray[$arrayIndex][1] = $aOrg['name'];

						$reportArray[$arrayIndex][2] = $oMeta->GetStatus($obj->status);
						$reportArray[$arrayIndex][3] = $oMeta->GetPriority($obj->priority);
						$reportArray[$arrayIndex][4] = $oMeta->GetSeverity($obj->type);
						$reportArray[$arrayIndex][5] = $obj->createdon;
						$reportArray[$arrayIndex][6] = $obj->closedon;
						$reportArray[$arrayIndex][7] = 1;
						$reportArray[$arrayIndex][8] = '<a href="' . menuLink('', 'menuAction=boTickets.view&ticketid=' . $obj->f('ticketid')) . '">[' . $obj->f('ticketid') . '] ' . htmlspecialchars($obj->summary, ENT_QUOTES, 'UTF-8') . '</a>';
					}
					else
					{
						$reportArray[$arrayIndex][7]++;
					}

					$count++;
				}
				while ($obj->next_record());

				$oTable = new TableHtmlHelper();
				$oTable->addColumn(STR_TCK_PRODUCT, 'string');
				$oTable->addColumn(STR_TCK_ACCOUNT, 'string');
				$oTable->addColumn(STR_TCK_STATUS, 'string');
				$oTable->addColumn(STR_TCK_PRIORITY, 'string');
				$oTable->addColumn(STR_TCK_TYPE, 'string');
				$oTable->addColumn(STR_TCK_CREATED, 'string');
				$oTable->addColumn(STR_TCK_CLOSED, 'string');
				$oTable->addColumn('Calls', 'string');
				$oTable->addColumn(STR_TCK_SUMMARY, 'html');
				$oTable->setCaption(sprintf(STR_WOST_ACTIVITYTITLE, $oMeta->GetPersonnel($responsible), $begindate, $enddate));
				$oTable->setData($reportArray);
				$oTable->setShowRownum(true);
				$oTable->render();
			}
			else
			{
				ShowInfo(STR_WOST_NOACTIVITY);
			}
		}
		else
		{
			ShowError(STR_WOST_QUERYERR);
		}
	}
}
