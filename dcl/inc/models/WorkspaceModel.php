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

LoadStringResource('db');
class WorkspaceModel extends DbProvider
{
	public function __construct()
	{
		parent::__construct();
		$this->TableName = 'dcl_workspace';
		LoadSchema($this->TableName);

		parent::Clear();
	}
	
	public function ListActive()
	{
		if ($this->Query("SELECT workspace_id, workspace_name FROM dcl_workspace WHERE active = 'Y' ORDER BY workspace_name") == -1)
			return -1;
	}
	
	public function GetUsers($workspace_id)
	{
		
	}
	
	public function GetProducts($workspace_id)
	{
		$workspace_id = (int)$workspace_id;
		if ($workspace_id > 0)
		{
			$oDB = new DbProvider;
			$oDB->Query("SELECT w.product_id, p.name FROM dcl_workspace_product w, products p WHERE w.product_id = p.id AND w.workspace_id = $workspace_id ORDER BY p.name");
			
			return $oDB->FetchAllRows();
		}
		
		return array();
	}

	public function SetCurrentWorkspace($workspace_id, $updateSession = true)
	{
		global $g_oSession;

		if ($workspace_id > 0)
		{
			$aProducts = $this->GetProducts($workspace_id);

			if (count($aProducts) == 0)
			{
				$g_oSession->Unregister('workspace');
				$g_oSession->Unregister('workspace_products');
			}
			else
			{
				$sProducts = '';
				foreach ($aProducts as $row)
				{
					if ($sProducts != '')
						$sProducts .= ',';

					$sProducts .= $row[0];
				}

				$g_oSession->Register('workspace', $workspace_id);
				$g_oSession->Register('workspace_products', $sProducts);
			}
		}
		else
		{
			$g_oSession->Unregister('workspace');
			$g_oSession->Unregister('workspace_products');
		}

		if ($updateSession)
			$g_oSession->Edit();
	}
}
