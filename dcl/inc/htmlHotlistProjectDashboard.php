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

LoadStringResource('prj');
LoadStringResource('wo');

class htmlHotlistProjectDashboard
{
	var $oSmarty;
	var $hotlist;

	function __construct()
	{
		$this->oSmarty = new SmartyHelper();
		$this->hotlist = null;
	}

	function Show()
	{
		global $g_oSec;

		commonHeader();
		if (($id = Filter::ToInt($_REQUEST['id'])) === null)
		{
			throw new InvalidDataException();
		}
		
		if (!$g_oSec->HasPerm(DCL_ENTITY_HOTLIST, DCL_PERM_VIEW, $id))
			throw new PermissionDeniedException();

		$this->hotlist = new HotlistModel();
		if ($this->hotlist->Load($id) == -1)
			throw new InvalidEntityException();

		$this->oSmarty->assign('VAL_HOTLISTID', $id);
		$this->oSmarty->assign('VAL_NAME', $this->hotlist->hotlist_desc);

		$this->oSmarty->Render('HotlistProjectDashboard.tpl');
	}
}
