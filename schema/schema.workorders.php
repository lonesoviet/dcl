<?php
/*
 * $Id: schema.workorders.php,v 1.1.1.1 2006/11/27 05:31:00 mdean Exp $
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

$GLOBALS['phpgw_baseline']['workorders'] = array(
	'fd' => array(
		'jcn' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'seq' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'product' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'createby' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'createdon' => array('type' => 'timestamp', 'nullable' => false),
		'closedby' => array('type' => 'int', 'precision' => 4),
		'closedon' => array('type' => 'date'),
		'status' => array('type' => 'int', 'precision' => 4),
		'statuson' => array('type' => 'timestamp', 'nullable' => false),
		'lastactionon' => array('type' => 'timestamp'),
		'deadlineon' => array('type' => 'date'),
		'eststarton' => array('type' => 'date'),
		'estendon' => array('type' => 'date'),
		'starton' => array('type' => 'date'),
		'esthours' => array('type' => 'float', 'precision' => 8),
		'totalhours' => array('type' => 'float', 'precision' => 8),
		'priority' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'severity' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'summary' => array('type' => 'varchar', 'precision' => 100, 'nullable' => false),
		'notes' => array('type' => 'text'),
		'description' => array('type' => 'text', 'nullable' => false),
		'responsible' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'revision' => array('type' => 'varchar', 'precision' => 20),
		'etchours' => array('type' => 'float', 'precision' => 8),
		'module_id' => array('type' => 'int', 'precision' => 4),
		'wo_type_id' => array('type' => 'int', 'precision' => 4, 'nullable' => false),
		'entity_source_id' => array('type' => 'int', 'precision' => 4, 'nullable' => true),
		'is_public' => array('type' => 'char', 'precision' => 1, 'nullable' => false, 'default' => 'N'),
		'contact_id' => array('type' => 'int', 'precision' => 4, 'nullable' => true)
	),
	'pk' => array('jcn', 'seq'),
	'fk' => array(),
	'ix' => array(),
	'uc' => array()
);
?>
