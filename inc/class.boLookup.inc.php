<?php
/*
 * $Id: class.boLookup.inc.php,v 1.1.1.1 2006/11/27 05:30:50 mdean Exp $
 *
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

LoadStringResource('bo');
class boLookup
{
	function add()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_ADD))
			return PrintPermissionDenied();

		$obj =& CreateObject('dcl.htmlLookup');
		$obj->Show();
	}

	function dbadd()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_ADD))
			return PrintPermissionDenied();

		$obj =& CreateObject('dcl.dbLookup');
		$obj->InitFromGlobals();
		$obj->Add();

		$objHTML =& CreateObject('dcl.htmlLookup');
		$objHTML->showall();
	}

	function modify()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_MODIFY))
			return PrintPermissionDenied();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['id'])) === null)
		{
			trigger_error('Data sanitize failed.');
			return;
		}
		
		$oDB =& CreateObject('dcl.dbLookup');
		if ($oDB->Load($iID) != -1)
		{
			$obj = CreateObject('dcl.htmlLookup');
			$obj->id = $oDB->dcl_lookup_id;
			$obj->active = $oDB->dcl_lookup_active;
			$obj->name = $oDB->dcl_lookup_name;
			$obj->mode = DCL_MODE_EDIT;
			$obj->Show();
		}
		else
			trigger_error(sprintf(STR_BO_LOOKUPNOTFOUND, $iID));
	}

	function dbmodify()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_MODIFY))
			return PrintPermissionDenied();

		$obj =& CreateObject('dcl.dbLookup');
		$obj->InitFromGlobals();
		$obj->Edit();

		$objHTML =& CreateObject('dcl.htmlLookup');
		$objHTML->showall();
	}

	function delete()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_DELETE))
			return PrintPermissionDenied();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['id'])) === null)
		{
			trigger_error('Data sanitize failed.');
			return;
		}
		
		$obj =& CreateObject('dcl.dbLookup');
		if ($obj->Load($iID) == -1)
			return;
			
		ShowDeleteYesNo('Lookup', 'boLookup.dbdelete', $obj->dcl_lookup_id, $obj->dcl_lookup_name);
	}

	function dbdelete()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_DELETE))
			return PrintPermissionDenied();

		if (($iID = @DCL_Sanitize::ToInt($_REQUEST['id'])) === null)
		{
			trigger_error('Data sanitize failed.');
			return;
		}
		
		$obj =& CreateObject('dcl.dbLookup');
		if ($obj->Load($iID) == -1)
			return;

		if ($obj->HasFKRef($iID))
		{
			$obj->Delete();
			print(STR_BO_DELETED);
		}
		else
		{
			$obj->SetActive(false);
			print(STR_BO_DEACTIVATED);
		}

		$objHTML =& CreateObject('dcl.html' . $classSubName);
		$objHTML->showall();
	}

	function showall()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_VIEW))
			return PrintPermissionDenied();

		$obj =& CreateObject('dcl.htmlLookup');
		$obj->showall();
	}

	function view()
	{
		global $g_oSec;
		
		commonHeader();
		if (!$g_oSec->HasPerm(DCL_ENTITY_LOOKUP, DCL_PERM_VIEW))
			return PrintPermissionDenied();

		$obj =& CreateObject('dcl.htmlLookup');
	}
}
?>
