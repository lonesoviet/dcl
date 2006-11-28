<?php
/*
 * $Id: function.dcl_select_personnel.php,v 1.1.1.1 2006/11/27 05:30:52 mdean Exp $
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

function smarty_function_dcl_select_personnel($params, &$smarty)
{
	if (!isset($params['name']))
		$params['name'] = 'personnel';

	if (!isset($params['id']))
		$params['id'] = $params['name'];

	if (!isset($params['default']))
		$params['default'] = '';

	$params['active'] = (!isset($params['active']) || $params['active'] == true) ? 'Y' : 'N';

	if (!isset($params['size']))
		$params['size'] = 1;

	$sFilter = '';
	if ($params['active'] == 'Y')
		$sFilter = "active = 'Y'";

	if (isset($params['project']))
	{
		// Show people in the project only
		$sSQL = 'SELECT DISTINCT p.id, p.short FROM personnel p, workorders a, projectmap b WHERE ';
		$sSQL .= "a.jcn = b.jcn AND (b.seq = 0 OR a.seq = b.seq) AND b.projectid = " . $params['project'];
		$sSQL .= ' AND p.id = a.responsible ';
	}
	else
	{
		if (isset($params['entity']) && isset($params['perm']))
		{
			$sSQL = 'select distinct p.id, p.short from personnel p join dcl_user_role ur on p.id = ur.personnel_id ';
			$sSQL .= 'join dcl_role_perm rp on ur.role_id = rp.role_id where ((entity_id = ';
			$sSQL .= $params['entity'] . ' and perm_id = ' . $params['perm'] . ') or (entity_id = ';
			$sSQL .= DCL_ENTITY_GLOBAL . ' and perm_id = ' . DCL_PERM_ADMIN . '))';

			if (isset($params['active']) && $params['active'] == 'Y')
				$sSQL .= " AND p.active = 'Y' ";
		}
		else
		{
			$sSQL = 'select p.id, p.short from personnel p ';

			if (isset($params['active']) && $params['active'] == 'Y')
				$sSQL .= "WHERE p.active = 'Y' ";
		}
	}

	$sSQL .= 'ORDER BY p.short';

	$oSelect =& CreateObject('dcl.htmlSelect');
	$oSelect->vDefault = $params['default'];
	$oSelect->sName = $params['name'];
	$oSelect->iSize = $params['size'];
	$oSelect->sZeroOption = STR_CMMN_SELECTONE;
	$oSelect->SetFromQuery($sSQL);

	return $oSelect->GetHTML();
}
?>