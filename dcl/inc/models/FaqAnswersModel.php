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
class FaqAnswersModel extends DbProvider
{
	public function __construct()
	{
		parent::__construct();
		$this->TableName = 'faqanswers';
		LoadSchema($this->TableName);
		
		parent::Clear();
	}

	public function Add()
	{
		$this->createon = DCL_NOW;
		return parent::Add();
	}

	public function Edit($aIgnoreFields = '')
	{
		$this->modifyby = DCLID;
		$this->modifyon = DCL_NOW;
		return parent::Edit(array('createby', 'createon'));
	}

	public function LoadByQuestionID($id, $orderby = 'createon desc')
	{
		if (($id = Filter::ToInt($id)) === null)
		{
			throw new InvalidDataException();
		}
		
		$this->Clear();

		$sql = 'SELECT answerid, questionid, answertext, createby, ';
		$sql .= $this->ConvertTimestamp('createon', 'createon');
		$sql .= ', modifyby, ';
		$sql .= $this->ConvertTimestamp('modifyon', 'modifyon');
		$sql .= ", active FROM faqanswers WHERE questionid=$id";
		if ($orderby != '')
			$sql .= " ORDER BY $orderby";

		if (!$this->Query($sql))
			return -1;

		return 1;
	}
		
	public function DeleteByQuestion($id)
	{
		if (($id = Filter::ToInt($id)) === null)
		{
			throw new InvalidDataException();
		}

		$oDB = new DbProvider;
		if ($oDB->Query("SELECT answerid FROM faqanswers WHERE questionid = $id") == -1)
		{
			return -1;
		}
		
		while ($oDB->next_record())
		{
			if ($this->Delete(array('answerid' => $this->f(0))) == -1)
				return -1;
		}
		
		return 1;
	}
}
