<?php
function dcl_upgrade0_9_4_3()
{
	global $phpgw_setup, $setup_info;

	$phpgw_setup->oProc->Query("UPDATE dcl_config SET dcl_config_varchar='0.9.4.4' WHERE dcl_config_name='DCL_VERSION'");

	$setup_info['dcl']['currentver'] = '0.9.4.4';
	return $setup_info['dcl']['currentver'];
}
