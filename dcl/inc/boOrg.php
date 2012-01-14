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

LoadStringResource('bo');

class boOrg extends boAdminObject
{
	function boOrg()
	{
		parent::boAdminObject();

		$this->oDB = new OrganizationModel();
		$this->sKeyField = 'org_id';
		$this->Entity = DCL_ENTITY_ORG;

		$this->sCreatedDateField = 'created_on';
		$this->sCreatedByField = 'created_by';
		$this->sModifiedDateField = 'modified_on';
		$this->sModifiedByField = 'modified_by';
		
		$this->aIgnoreFieldsOnUpdate = array('created_on', 'created_by');
	}

	function modify($aSource)
	{
		RequirePermission(DCL_ENTITY_ORG, DCL_PERM_MODIFY);

		$aSource['active'] = @Filter::ToYN($aSource['active']);
		parent::modify($aSource);
		
		$hasOrgTypes = isset($aSource['org_type_id']) && count($aSource['org_type_id']) > 0;
		$sql = 'DELETE FROM dcl_org_type_xref WHERE org_id = ' . $aSource['org_id'];
		if ($hasOrgTypes)
		{
			$sTypes = join(',', $aSource['org_type_id']);
			$sql .= ' AND org_type_id NOT IN (' . $sTypes . ')';
		}

		$this->oDB->Execute($sql);
		if (!$hasOrgTypes)
			return;
		
		$organizationTypeXrefModel = new OrganizationTypeXrefModel();
		$organizationTypeXrefModel->org_id = $aSource['org_id'];
		
		foreach ($aSource['org_type_id'] as $org_type_id)
		{
			if (!$organizationTypeXrefModel->Exists(array('org_id' => $aSource['org_id'], 'org_type_id' => $org_type_id)))
			{
				$organizationTypeXrefModel->org_type_id = $org_type_id;
				$organizationTypeXrefModel->Add();
			}
		}
	}
	
	function delete($aSource)
	{
		RequirePermission(DCL_ENTITY_ORG, DCL_PERM_DELETE);
		$id = @Filter::RequireInt($aSource['org_id']);
		
		if (!$this->oDB->HasFKRef($id))
		{
			$this->oDB->Execute("DELETE FROM dcl_org_addr WHERE org_id = $id");
			$this->oDB->Execute("DELETE FROM dcl_org_alias WHERE org_id = $id");
			$this->oDB->Execute("DELETE FROM dcl_org_contact WHERE org_id = $id");
			$this->oDB->Execute("DELETE FROM dcl_org_email WHERE org_id = $id");
			$this->oDB->Execute("DELETE FROM dcl_org_note WHERE org_id = $id");
			$this->oDB->Execute("DELETE FROM dcl_org_phone WHERE org_id = $id");
			$this->oDB->Execute("DELETE FROM dcl_org_type_xref WHERE org_id = $id");
		}
		
		parent::delete($aSource);
	}
}
