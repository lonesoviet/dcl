<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2011 Free Software Foundation
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

class OrganizationAddressPresenter
{
	public function Create($orgId)
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_ORG, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		$oSmarty = new SmartyHelper();
		$oAddrType = new AddressTypeHtmlHelper();

		$oSmarty->assign('URL_BACK', menuLink('', 'menuAction=Organization.Detail&org_id=' . $orgId));

		$oOrg = new OrganizationModel();
		$oOrg->Load($orgId);
		$oSmarty->assign('VAL_ORGNAME', $oOrg->name);
		$oSmarty->assign('VAL_ORGID', $oOrg->org_id);

		$oSmarty->assign('TXT_FUNCTION', 'Add New Organization Address');
		$oSmarty->assign('VAL_MENUACTION', 'OrganizationAddress.Insert');
		$oSmarty->assign('CMB_ADDRTYPE', $oAddrType->Select());

		$oSmarty->Render('AddrForm.tpl');
	}

	public function Edit(OrganizationAddressModel $model)
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_ORG, DCL_PERM_MODIFY))
			throw new PermissionDeniedException();

		$oSmarty = new SmartyHelper();
		$oAddrType = new AddressTypeHtmlHelper();

		$oSmarty->assign('URL_BACK', menuLink('', 'menuAction=Organization.Detail&org_id=' . $model->org_id));

		$oOrg = new OrganizationModel();
		$oOrg->Load($model->org_id);
		$oSmarty->assign('VAL_ORGNAME', $oOrg->name);
		$oSmarty->assign('VAL_ORGID', $oOrg->org_id);

		$oSmarty->assign('VAL_MENUACTION', 'OrganizationAddress.Update');
		$oSmarty->assign('VAL_ORGADDRID', $model->org_addr_id);
		$oSmarty->assign('VAL_ADD1', $model->add1);
		$oSmarty->assign('VAL_ADD2', $model->add2);
		$oSmarty->assign('VAL_CITY', $model->city);
		$oSmarty->assign('VAL_STATE', $model->state);
		$oSmarty->assign('VAL_ZIP', $model->zip);
		$oSmarty->assign('VAL_COUNTRY', $model->country);
		$oSmarty->assign('VAL_PREFERRED', $model->preferred);
		$oSmarty->assign('CMB_ADDRTYPE', $oAddrType->Select($model->addr_type_id));
		$oSmarty->assign('TXT_FUNCTION', 'Edit Organization Address');

		$oSmarty->Render('AddrForm.tpl');
	}
}
