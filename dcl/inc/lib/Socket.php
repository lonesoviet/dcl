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

class Socket
{
	public $sHost;
	public $iPort;
	public $iTimeout;
	public $iErr;
	public $sErr;
	public $sResponse;
	public $sResponseMode;
	public $hSocket;
	public $bDebug;

	public function __construct()
	{
		$this->sHost = '';
		$this->iPort = 0;
		$this->iTimeout = 30;
		$this->iErr = 0;
		$this->sErr = '';
		$this->sResponse = '';
		$this->sResponseMode = ''; // To change reading behavior
		$this->hSocket = 0;
		$this->bDebug = false;
	}

	public function Connect($bResponse = false)
	{
		$this->hSocket = fsockopen($this->sHost, $this->iPort, $this->iErr, $this->sErr, $this->iTimeout);
		if (!$this->hSocket)
		{
			LogError('fsockopen(' . $this->sHost . ', ' .  $this->iPort . ', &$this->iErr, &$this->sErr, ' . $this->iTimeout . '):&nbsp;' . '(' . $this->iErr . ')&nbsp;' . $this->sErr, __FILE__, __LINE__, debug_backtrace());
			return -1;
		}

		if ($bResponse)
			$this->Read();

		return 1;
	}

	public function Disconnect()
	{
		if ($this->hSocket)
			fclose($this->hSocket);
	}

	public function Write($sValue, $bResponse = false)
	{
		if ($this->bDebug)
			echo '&gt;&gt;&gt; ', htmlspecialchars($sValue, ENT_QUOTES, 'UTF-8'), '<br>';

		fwrite($this->hSocket, $sValue);
		if ($bResponse)
			$this->Read();
	}

	public function Read()
	{
		if ($this->hSocket)
		{
			$this->sResponse = fgets($this->hSocket, 1024);
			if ($this->bDebug)
				echo '&lt;&lt;&lt; ', htmlspecialchars($this->sResponse, ENT_QUOTES, 'UTF-8'), '<br>';

			if ($this->sResponseMode == 'smtp')
			{
				while (mb_strlen($this->sResponse) > 3 && $this->sResponse[3] == '-')
				{
					$this->sResponse = fgets($this->hSocket, 1024);
					if ($this->bDebug)
						echo '&lt;&lt;&lt; ', htmlspecialchars($this->sResponse, ENT_QUOTES, 'UTF-8'), '<br>';
				}
			}
		}
	}
}
