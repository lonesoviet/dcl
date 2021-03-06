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

class SecurityAuditModel extends DbProvider
{
	public function __construct()
	{
		parent::__construct();
		$this->TableName = 'dcl_sec_audit';
		$this->cacheEnabled = true;
		
		LoadSchema($this->TableName);
		
		parent::Clear();
	}

	public static function AddAudit($userId, $action, $params = '')
	{
		global $dcl_info;

		if ($dcl_info['DCL_SEC_AUDIT_ENABLED'] == 'Y')
		{
			$securityAuditModel = new SecurityAuditModel();
			$securityAuditModel->id = $userId;
			$securityAuditModel->actionon = DCL_NOW;
			$securityAuditModel->actiontxt = $action;
			$securityAuditModel->actionparam = $params;
			$securityAuditModel->Add();
		}
	}
}
