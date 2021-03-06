<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2012 Free Software Foundation
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

function smarty_function_dcl_publish($params, &$smarty)
{
	if (!isset($params['topic']))
	{
		trigger_error('dcl_publish: missing parameter topic');
		return;
	}
	
	// Unset topic and use array_merge just to be sure it's first
	$topic = $params['topic'];
	unset($params['topic']);
	
	ob_start();
	call_user_func_array('PubSub::Publish', array_merge(array($topic), $params));
	$content = ob_get_contents();

	if (ob_get_length())
		ob_end_clean();
	
	return $content;
}