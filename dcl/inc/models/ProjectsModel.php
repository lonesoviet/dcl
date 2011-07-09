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

class ProjectsModel extends DbProvider
{
	public function __construct()
	{
		parent::__construct();
		$this->TableName = 'dcl_projects';
		LoadSchema($this->TableName);
		$this->AuditEnabled = true;
		parent::Clear();
	}

	public function Add()
	{
		global $dcl_info;

		$this->status = $dcl_info['DCL_DEFAULT_PROJECT_STATUS'];
		$this->createdon = $this->GetDateSQL();
		return parent::Add();
	}

	public function Delete()
	{
		$query = 'DELETE FROM projectmap WHERE projectid=' . $this->projectid;
		$this->Execute($query);

		$this->Audit(array('projectid' => $this->projectid));
		$query = 'DELETE FROM dcl_projects WHERE projectid=' . $this->projectid;

		return $this->Execute($query);
	}

	public function ParentIsNotChild($projectid, $parentid)
	{
		$isNotChild = true;
		$db = new DbProvider;

		if ($db->Query("SELECT projectid FROM dcl_projects WHERE parentprojectid=$projectid") != -1)
		{
			while ($db->next_record() && $isNotChild)
			{
				if ($db->f(0) == $parentid)
					$isNotChild = false;
				else
					$isNotChild = $this->ParentIsNotChild($db->f(0), $parentid);
			}

			$db->FreeResult();
		}

		return $isNotChild;
	}

	public function GetProjectParents($projectid, $includethis = false)
	{
		$obj = new DbProvider;

		if ($includethis == false)
			$projectids = '';
		else
			$projectids = $projectid;

		if ($obj->Query("SELECT parentprojectid FROM dcl_projects WHERE projectid =$projectid") != -1)
		{
			if ($obj->next_record())
			{
				$parent_id = $obj->f(0);
				$obj->FreeResult();

				if ($parent_id == 0 || $parent_id == null)
					return $projectids;

				if ($projectids != '')
					$projectids .= ',';

				$projectids .= $this->GetProjectParents($parent_id, true);
			}
		}

		return $projectids;
	}


	public function GetProjectChildren($projectid, $includethis = false)
	{
		$obj = new DbProvider;

		if ($includethis == false)
			$projectids = '';
		else
			$projectids = $projectid;

		if ($obj->Query("SELECT projectid FROM dcl_projects WHERE parentprojectid=$projectid") != -1)
		{
			while ($obj->next_record())
			{
				if(($obj->cur > 1) || $includethis)
					$projectids .= ',';

				$projectids .= $this->GetProjectChildren($obj->f(0), true);
			}

			$obj->FreeResult();
		}

		return $projectids;
	}

	public function GetProjectStatistics($projectid, $includeChildren = false, $includeParents = false)
	{
		$retval = array(
				'totaltasks' => 0,
				'esthours' => 0.0,
				'totalhours' => 0.0,
				'etchours' => 0.0,
				'resources' => 0,
				'tasksclosed' => 0
			);

		$obj = new DbProvider;

		$projectids = $projectid;

		if ($includeChildren)
		{
			$children = $this->GetProjectChildren($projectid);
			if($children != '')
				$projectids .= ",".$children;
		}

		if ($includeParents)
		{
			$parents = $this->GetProjectParents($projectid);
			if($parents != '')
				$projectids .= ",".$parents;
		}

		$sql = 'SELECT count(*),sum(esthours),sum(totalhours),sum(etchours) FROM workorders a, projectmap b WHERE ';
		$sql .= "a.jcn=b.jcn AND (b.seq=0 OR a.seq=b.seq) AND b.projectid in ($projectids) ";
		if ($obj->Query($sql) != -1)
		{
			if ($obj->next_record())
			{
				$retval['totaltasks'] = $obj->f(0);
				$retval['esthours'] = $obj->f(1);
				$retval['totalhours'] = $obj->f(2);
				$retval['etchours'] = $obj->f(3);
			}

			$obj->FreeResult();
		}

		$sql = 'SELECT distinct responsible FROM workorders a, projectmap b, statuses c WHERE ';
		$sql .= "a.jcn=b.jcn AND (b.seq=0 OR a.seq=b.seq) AND b.projectid in ($projectids) AND a.status=c.id AND c.dcl_status_type!=2";
		if ($obj->Query($sql) != -1)
		{
			// spin through results to get count - Oracle has no numrows and mysql doesn't support count(distinct) in older versions :-(
			while ($obj->next_record())
				$retval['resources']++;

			$obj->FreeResult();
		}

		$sql = 'SELECT count(*) FROM workorders a, projectmap b, statuses c WHERE ';
		$sql .= "a.jcn=b.jcn AND (b.seq=0 OR a.seq=b.seq) AND b.projectid in ($projectids) AND a.status=c.id AND c.dcl_status_type=2";
		if ($obj->Query($sql) != -1)
		{
			if ($obj->next_record())
				$retval['tasksclosed'] = $obj->f(0);

			$obj->FreeResult();
		}

		return $retval;
	}

	public function Load($projectid)
	{
		return parent::Load(array('projectid' => $projectid));
	}

	public function Exists($sName)
	{
		$obj = new DbProvider;
		$obj->Query('SELECT count(*) FROM dcl_projects WHERE ' . $this->GetUpperSQL('name') . ' = ' . $this->Quote(strtoupper($sName)));
		$obj->next_record();
		return ($obj->f(0) > 0);
	}
}
