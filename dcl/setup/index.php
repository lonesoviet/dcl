<?php
/*
 * Derived from XOOPS Setup
 *
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2014 Free Software Foundation
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

mb_internal_encoding('UTF-8');
mb_detect_order('UTF-8');
mb_regex_encoding('UTF-8');

define('DCL_INSTALLER_RUNNING', 1);

include_once './passwd.php';
if(INSTALL_USER != '' || INSTALL_PASSWD != '')
{
    if (!isset($_SERVER['PHP_AUTH_USER']))
	{
        header('WWW-Authenticate: Basic realm="DCL Installer"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Access denied.';
        exit;
    }
	else
	{
        if(INSTALL_USER != '' && $_SERVER['PHP_AUTH_USER'] != INSTALL_USER)
		{
            header('HTTP/1.0 401 Unauthorized');
            echo 'Access denied.';
            exit;
        }
        if(INSTALL_PASSWD != $_SERVER['PHP_AUTH_PW'])
		{
            header('HTTP/1.0 401 Unauthorized');
            echo 'Access denied.';
            exit;
        }
    }
}

include_once './class/textsanitizer.php';
$myts =& TextSanitizer::getInstance();

if ( isset($_POST) ) {
    foreach ($_POST as $k=>$v) {
        $$k = $myts->stripSlashesGPC($v);
    }
}

if ( !empty($_POST['lang']) ) {
    $language = $_POST['lang'];
} else {
    $language = isset($_COOKIE['install_lang']) ? $_COOKIE['install_lang'] : "english";
}

if ( file_exists("./language/".$language."/install.php") ) {
    include_once "./language/".$language."/install.php";
} elseif ( file_exists("./language/english/install.php") ) {
    include_once "./language/english/install.php";
    $language = 'english';
} else {
    echo 'no language file.';
    exit();
}
setcookie("install_lang", $language);

define('_OKIMG', '<span class="glyphicon glyphicon-ok-sign"></span> ');
define('_NGIMG', '<span class="glyphicon glyphicon-remove-sign"></span> ');

$b_back = '';
$b_reload = '';
$b_next = '';
$dcl_domain = 'default';

// options for mainfile.php
$xoopsOption['nocommon'] = true;
define('DCL_INSTALL', 1);

if(!empty($_POST['op']))
    $op = $_POST['op'];
elseif(!empty($_GET['op']))
    $op = $_GET['op'];
else
    $op = '';

///// main

switch ($op) {

default:
case "langselect":
    $title = _INSTALL_L0;
    $content = "<p>Choose language to be used for the installation process</p>"
              ."<select name='lang'>";

    $langarr = getDirList("./language/");
    foreach ($langarr as $lang) {
        $content .= "<option value='".$lang."'";
        if (mb_strtolower($lang) == $language) {
            $content .= ' selected="selected"';
        }
        $content .= ">".$lang."</option>";
    }
    $content .= "</select>";

    $b_next = array('start', _INSTALL_L80 );
    include 'install_tpl.php';
    break;

case "start":
    $title = _INSTALL_L0;
    include './language/'.$language.'/welcome.php';
    $b_next = array('modcheck', _INSTALL_L81 );
    include 'install_tpl.php';
    break;

case "modcheck":
    $writeok = array("inc/");
    $title = _INSTALL_L82;
    $content = '';
    $error = false;
    foreach ($writeok as $wok) {
        if (!is_dir("../".$wok)) {
            if ( file_exists("../".$wok) ) {
                @chmod("../".$wok, 0666);
                if (! is_writeable("../".$wok)) {
                    $content .= _NGIMG.sprintf(_INSTALL_L83, $wok)."<br />";
                    $error = true;
                }else{
                    $content .= _OKIMG.sprintf(_INSTALL_L84, $wok)."<br />";
                }
            }
        } else {
            @chmod("../".$wok, 0777);
            if (! is_writeable("../".$wok)) {
                $content .= _NGIMG.sprintf(_INSTALL_L85, $wok)."<br />";
                $error = true;
            }else{
                $content .= _OKIMG.sprintf(_INSTALL_L86, $wok)."<br />";
            }
        }
    }

    if(! $error)
	{
        $content .= "<p>"._OKIMG._INSTALL_L87."</p>";
        $b_next = array('dbform', _INSTALL_L89 );
    }
	else
	{
        $content .= "<p>"._NGIMG._INSTALL_L46."</p>";
        $b_reload = true;
    }

    include 'install_tpl.php';
    break;

case "dbform":
    include_once 'class/settingmanager.php';
    $sm = new setting_manager();
    $sm->readConstant();
    $content = $sm->editform();
    $title = _INSTALL_L90;
    $b_next = array('dbconfirm',_INSTALL_L91);
    include 'install_tpl.php';
    break;

case "dbconfirm":
    include_once 'class/settingmanager.php';
    $sm = new setting_manager(true);

    $content = $sm->checkData();
    if (!empty($content)) {
        $content .= $sm->editform();
        $b_next = array('dbconfirm',_INSTALL_L91);
        include 'install_tpl.php';
        break;
    }

    $title = _INSTALL_L53;
    $content = $sm->confirmForm();
    $b_next = array('dbsave',_INSTALL_L92 );
    $b_back = array('', _INSTALL_L93 );
    include 'install_tpl.php';
    break;

case "dbsave":
    include_once "./class/mainfilemanager.php";
    $title = _INSTALL_L88;
    $mm = new mainfile_manager();

    $ret = $mm->copyDistFile();
    if(! $ret){
        $content = _INSTALL_L60;
        include 'install_tpl.php';
        exit();
    }

    $mm->setRewrite('dbType', $myts->stripSlashesGPC($_POST['dbType']));
    $mm->setRewrite('dbHost', $myts->stripSlashesGPC($_POST['dbHost']));
    $mm->setRewrite('dbPort', $myts->stripSlashesGPC($_POST['dbPort']));
    $mm->setRewrite('dbUser', $myts->stripSlashesGPC($_POST['dbUser']));
    $mm->setRewrite('dbPassword', $myts->stripSlashesGPC($_POST['dbPassword']));
    $mm->setRewrite('dbName', $myts->stripSlashesGPC($_POST['dbName']));
    $mm->setRewrite('dcl_root', $myts->stripSlashesGPC($_POST['dcl_root']));
    $mm->setRewrite('dcl_www_root', $myts->stripSlashesGPC($_POST['dcl_www_root']));

    $ret = $mm->doRewrite();
    if(! $ret){
        $content = _INSTALL_L60;
        include 'install_tpl.php';
        exit();
    }

    $content = $mm->report();
    $content .= "<p>"._OKIMG._INSTALL_L62."</p>\n";
    $b_next = array('mainfile', _INSTALL_L94 );
    include 'install_tpl.php';
    break;

case "mainfile":
    // checking XOOPS_ROOT_PATH and XOOPS_URL
    include_once "../inc/config.php";
    $title = _INSTALL_L94;
    $content = "<p>\n";

    $detected = str_replace("\\", "/", getcwd()); // "
    $detected = str_replace("/setup", "", $detected);
    if ( mb_substr($detected, -1) != "/" )
		$detected .= '/';

    if (empty($detected)){
        $content .= _NGIMG._INSTALL_L95.'<br />';
    }
    elseif ( DCL_ROOT != $detected ) {
        $content .= _NGIMG.sprintf(_INSTALL_L96,$detected). '<br />';
    }else {
        $content .= _OKIMG._INSTALL_L97.'<br />';
    }

    if(!is_dir(DCL_ROOT)){
        $content .= _NGIMG._INSTALL_L99.'<br />';
    }

    $content .= "</p>\n";

    $content .= "<p>\n";
    $content .= _INSTALL_L11."<b>".DCL_ROOT."</b><br />";
    $content .= "</p>\n";
    $content .= "<p>"._INSTALL_L13."</p>\n";

    $b_next = array('initial', _INSTALL_L102 );
    $b_back = array('start', _INSTALL_L103 );
    $b_reload = true;

    include 'install_tpl.php';
    //mainfile_settings();
    break;

case "initial":
    // confirm database setting
    include_once "../inc/config.php";
    $content = "<table class=\"table table-striped\">\n";
    $content .= "<tr><td>"._INSTALL_L27."&nbsp;&nbsp;</td><td><b>".$dcl_domain_info[$dcl_domain]['dbHost']."</b></td></tr>\n";
    $content .= "<tr><td>".'Database Port'."&nbsp;&nbsp;</td><td><b>".$dcl_domain_info[$dcl_domain]['dbPort']."</b></td></tr>\n";
    $content .= "<tr><td>"._INSTALL_L28."&nbsp;&nbsp;</td><td><b>".$dcl_domain_info[$dcl_domain]['dbUser']."</b></td></tr>\n";
    $content .= "<tr><td>"._INSTALL_L29."&nbsp;&nbsp;</td><td><b>".$dcl_domain_info[$dcl_domain]['dbName']."</b></td></tr>\n";
    $content .= "</table><br />\n";
    $content .= "<p>";
    $content .= _INSTALL_L13."\n";
    $content .= "</p>\n";
    $b_next = array('checkDB', _INSTALL_L104);
    $b_back = array('start', _INSTALL_L103);
    $b_reload = true;
    $title = _INSTALL_L102;
    include 'install_tpl.php';
    break;

case "checkDB":
    include_once "../inc/config.php";
    $oDB = new DbProvider;
    $title = _INSTALL_L104;
    $content = "<p>\n";

    if (!$oDB->CanConnectServer())
	{
        $content .= _NGIMG._INSTALL_L106."<br />";
        $content .= "<br />"._INSTALL_L107;
        $content .= "</p>\n";
        $b_back = array('start', _INSTALL_L103);
        $b_reload = true;
    }
	else
	{
        $content .= _OKIMG._INSTALL_L108."<br />";
        if (!$oDB->CanConnectDatabase())
		{
            $content .= _NGIMG.sprintf(_INSTALL_L109, $dcl_domain_info[$dcl_domain]['dbName'])."<br />";
            $content .= "</p>\n";

            $content .= "<p>"._INSTALL_L21."<br />"
                        ."<b>".$dcl_domain_info[$dcl_domain]['dbName']."</b></p>"
                        ."<p>"._INSTALL_L22."</p>";

            $b_next = array('createDB', _INSTALL_L105);
            $b_back = array('start', _INSTALL_L103);
            $b_reload = true;
        }
		else
		{
			unset($GLOBALS['__DB_CONN__']);
			$oDB->Connect();
			// If this, the most basic of tables, does not exist, the database is probably empty of DCL
			if (!$oDB->TableExists('workorders'))
			{
            	$content .= _OKIMG.sprintf(_INSTALL_L110, $dcl_domain_info[$dcl_domain]['dbName'])."<br />";
            	$content .= "</p>\n";
            	$content .= "<p>"._INSTALL_L111."</p>";
            	$b_next = array('createTables', _INSTALL_L40);
			}
			else
			{
				// For upgrades, we need to manually update the database to UTF8 for MySQL and PostgreSQL.  This code
				// will prevent the upgrade from proceeding until this is done since the database and PHP are now geared
				// towards UTF-8
				$dbType = $dcl_domain_info[$dcl_domain]['dbType'];
				$dbName = $dcl_domain_info[$dcl_domain]['dbName'];
				$needsEncodingUpgrade = false;
				if ($dbType == 'mysql')
				{
					$oDB->Query('SHOW VARIABLES LIKE "character_set_database"');
					if ($oDB->next_record())
					{
						$encoding = $oDB->f(1);
						if (strtolower($encoding) != 'utf8')
						{
							// All of the tables available at the time of upgrade to 0.9.5RC20
							$tables = array(
								'actions', 'attributesetsmap', 'attributesets', 'dcl_addr_type', 'dcl_chklst', 'dcl_chklst_tpl', 'dcl_config', 'dcl_contact_addr', 'dcl_contact_email',
								'dcl_contact_note', 'dcl_contact_phone', 'dcl_contact', 'dcl_contact_license', 'dcl_contact_type', 'dcl_contact_type_xref', 'dcl_contact_url',
								'dcl_email_type', 'dcl_entity', 'dcl_entity_hotlist', 'dcl_entity_hotlist_audit', 'dcl_entity_perm', 'dcl_entity_source', 'dcl_entity_tag',
								'dcl_environment', 'dcl_environment_org', 'dcl_environment_product', 'dcl_environment_wo', 'dcl_environment_outage_type', 'dcl_environment_outage',
								'dcl_environment_outage_wo', 'dcl_error_log', 'dcl_hotlist', 'dcl_note_type', 'dcl_org_addr', 'dcl_org_alias', 'dcl_org_contact', 'dcl_org_email',
								'dcl_org_note', 'dcl_org_phone', 'dcl_org', 'dcl_org_product_xref', 'dcl_org_type', 'dcl_org_type_xref', 'dcl_org_url', 'dcl_perm', 'dcl_phone_type',
								'dcl_preferences', 'dcl_product_build', 'dcl_product_build_except', 'dcl_product_build_item', 'dcl_product_build_sccs', 'dcl_product_module',
								'dcl_product_version', 'dcl_product_version_item', 'dcl_product_version_status', 'dcl_projects', 'dcl_projects_audit', 'dcl_role_perm', 'dcl_role',
								'dcl_sccs', 'dcl_sccs_xref', 'dcl_sec_audit', 'dcl_session', 'dcl_status_type', 'dcl_tag', 'dcl_url_type', 'dcl_user_role', 'dcl_wiki', 'dcl_wo_account',
								'dcl_wo_account_audit', 'dcl_wo_id', 'dcl_wo_task', 'dcl_wo_type', 'dcl_workspace_product', 'dcl_workspace_user', 'dcl_workspace', 'departments',
								'faqanswers', 'faq', 'faqquestions', 'faqtopics', 'personnel', 'priorities', 'products', 'projectmap', 'projectmap_audit', 'severities', 'statuses',
								'ticketresolutions', 'tickets', 'tickets_audit', 'timecards', 'views', 'watches', 'workorders', 'workorders_audit'
							);

							$content .= _NGIMG . 'IMPORTANT!  UTF-8 encoding upgrade needed!  If you do not have any need to convert encoding, please follow these instructions (for items marked SQL, execute the SQL query against your DCL database):';
							$content .= '<ol>';
							$content .= '<li>Backup your database in case something goes wrong!</li>';
							$content .= "<li>SQL: ALTER DATABASE `$dbName` CHARACTER SET utf8 COLLATE utf8_unicode_ci</li>";

							foreach ($tables as $table)
							{
								$oDB->Query("SHOW TABLES LIKE '$table'");
								if ($oDB->next_record())
									$content .= "<li>SQL: ALTER TABLE $table CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci</li>";
							}

							$content .= '</ol>';
							$content .= 'Alternatively, you can do the following to convert character encoding (replace latin1 in iconv command with your original charset):';
							$content .= '<ol>';
							$content .= '<li>Backup your database in case something goes wrong!</li>';
							$content .= "<li>SQL: DROP DATABASE `$dbName`</li>";
							$content .= "<li>SQL: CREATE DATABASE `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci</li>";
							$content .= "<li>iconv --from-code latin1 --to-code utf-8 backup.sql > backup-utf8.sql</li>";
							$content .= "<li>Import the backup file into the newly created UTF-8 database (make sure you connect as UTF-8)!</li>";
							$content .= '</ol>';

							$needsEncodingUpgrade = true;
						}
					}
				}
				else if ($dbType == 'pgsql')
				{
					$oDB->Query('show server_encoding');
					if ($oDB->next_record())
					{
						$encoding = $oDB->f(0);
						if (strtolower($encoding) != 'utf8')
						{
							$content .= _NGIMG . 'IMPORTANT!  UTF-8 encoding upgrade needed!  Please follow these instructions (for items marked SQL, execute the SQL query against your DCL database; replace latin1 in iconv command with your original charset):';
							$content .= '<ol>';
							$content .= '<li>Backup your database in case something goes wrong!</li>';
							$content .= "<li>SQL: DROP DATABASE $dbName</li>";
							$content .= "<li>SQL: CREATE DATABASE $dbName ENCODING 'UTF8'</li>";
							$content .= "<li>iconv --from-code latin1 --to-code utf-8 backup.sql > backup-utf8.sql</li>";
							$content .= "<li>Import the backup file into the newly created UTF-8 database (make sure you connect as UTF-8)!</li>";
							$content .= '</ol>';

							$needsEncodingUpgrade = true;
						}
					}
				}

				if (!$needsEncodingUpgrade)
				{
					$content .= _OKIMG.'Table workorders exists, continue with upgrade.<br />';
					$b_next = array('updateTables', 'Update Tables');
				}
				else
				{
					$b_reload = true;
				}

				$content .= "</p>\n";
			}
        }
    }

    include 'install_tpl.php';
    break;

case "createDB":
    include_once "../inc/config.php";
    $oDB = new DbProvider;

    if (!$oDB->CreateDatabase()){
        $content = "<p>"._INSTALL_L31."</p>";
        $b_next = array('checkDB', _INSTALL_L104);
        $b_back = array('start', _INSTALL_L103);
    }else{
        $content = "<p>".sprintf(_INSTALL_L43, $dcl_domain_info[$dcl_domain]['dbName'])."</p>";
        $b_next = array('checkDB', _INSTALL_L104);
    }
    include 'install_tpl.php';
    break;

case "createTables":
    include_once "../inc/config.php";
	include_once "../inc/functions.inc.php";
	include_once 'tables_current.inc.php';

	$oProc = new SchemaManager($dcl_domain_info[$dcl_domain]['dbType']);
	$oProc->m_odb = new DbProvider;

	$content = '<div style="text-align: left; width: 50%; padding-left: 200px;">';
	ob_start();
	$bSuccess = $oProc->ExecuteScripts($phpgw_baseline, true);
	$content .= ob_get_contents();

	if (ob_get_length())
		ob_end_clean();

	if (!$bSuccess)
	{
		$content .= '<br/>' . _NGIMG . '&nbsp;<b>Install Tables Failed</b>';
		$b_back = array('start', _INSTALL_L103);
    }
	else
	{
		$content .= '<br/>' . _OKIMG . '&nbsp;<b>All Tables Installed Successfully</b>';
		$b_next = array('insertData', _INSTALL_L116);
    }

	$content .= '</div>';

    include 'install_tpl.php';
    break;

case 'updateTables':
	include_once "../inc/config.php";
	include_once "../inc/functions.inc.php";
	include_once 'tables_baseline.inc.php';

	$UPGRADE_VERSIONS = array();
	include_once 'tables_update.inc.php';
	include_once 'class/setup.php';

	// Get current version
	$oDB = new DbProvider;
	if ($oDB->TableExists('dcl_config'))
	{
		$dclVersion = $oDB->ExecuteScalar("SELECT dcl_config_varchar FROM dcl_config WHERE dcl_config_name = 'DCL_VERSION'");

		// These versions are translated to pseudo-versions for upgrade purposes
		switch ($dclVersion)
		{
			case '20010321': $dclVersion = '0.5.1'; break;
			case '20010327': $dclVersion = '0.5.2'; break;
			case '20010413': $dclVersion = '0.5.3'; break;
			case '20010715': $dclVersion = '0.5.4'; break;
			case '20010729': $dclVersion = '0.5.5'; break;
			case '20010911': $dclVersion = '0.5.6'; break;
			case '20010916': $dclVersion = '0.5.7'; break;
			case '20010918': $dclVersion = '0.5.8'; break;
			case '20010923': $dclVersion = '0.5.9'; break;
			case '20011203': $dclVersion = '0.5.10'; break;
			case '20011209': $dclVersion = '0.5.11'; break;
			case '20011210': $dclVersion = '0.5.12'; break;
			case '20011215': $dclVersion = '0.5.13'; break;
			case '20020120': $dclVersion = '0.5.14'; break;
			case '20020215': $dclVersion = '0.5.15'; break;
			case '20020706': $dclVersion = '0.5.16'; break;
			case '20021021': $dclVersion = '0.5.17'; break;
			case '20021023': $dclVersion = '0.5.18'; break;
			case '0.9.3':
				// 0.9.4 new installs report as 0.9.3, so check for a 0.9.4 table to be sure
				if ($oDB->TableExists('dcl_sccs'))
					$dclVersion = '0.9.4';
					
				break;
			case '0.9.5':
				if ($oDB->TableExists('dcl_sec_audit'))
					$dclVersion = '0.9.5RC6';
				elseif ($oDB->TableExists('dcl_org_product_xref'))
					$dclVersion = '0.9.5RC5';
				elseif ($oDB->TableExists('dcl_tag'))
				{
					// RC4 doesn't really matter - because it just refreshes a table
					// Re-doing that, while it might take some time, shouldn't hurt anything...
					$dclVersion = '0.9.5RC3';
				}
				elseif ($oDB->TableExists('dcl_wo_task'))
					$dclVersion = '0.9.5RC2';
				else
					$dclVersion = '0.9.5RC1';
				break;
		}

		$phpgw_setup = new DCLSetup();
		$phpgw_setup->oProc = new SchemaManager($dcl_domain_info[$dcl_domain]['dbType']);
		$phpgw_setup->oProc->m_odb = new DbProvider;
		$phpgw_setup->oProc->m_aTables = $phpgw_baseline;

		$setup_info = array();
		include_once 'setup.inc.php'; // gets target version

		$result = true;
		if ($dclVersion != $setup_info['dcl']['version'] && count($UPGRADE_VERSIONS) > 0)
		{
			$content = '<div>';

			// Upgrade required
			$setup_info['dcl']['currentver'] = $dclVersion;
			$bDeltaOnly = ($UPGRADE_VERSIONS[0] != $setup_info['dcl']['currentver']);
			$phpgw_setup->oProc->m_bDeltaOnly = $bDeltaOnly;
			$content .= '<b>Upgrading DCL From Version ' . $dclVersion . '...</b><br/>';
			for ($i = 0; $i < count($UPGRADE_VERSIONS); $i++)
			{
				// Once we have a match, it starts including database upgrade commands
				if ($UPGRADE_VERSIONS[$i] == $dclVersion && $bDeltaOnly)
				{
					$phpgw_setup->oProc->m_bDeltaOnly = false;
					$bDeltaOnly = false;
				}

				$fName = 'dcl_upgrade' . str_replace('.', '_', $UPGRADE_VERSIONS[$i]);
				$result = $result && ($UPGRADE_VERSIONS[$i] != $fName());

				if (!$bDeltaOnly)
					$content .= _OKIMG . '&nbsp;Version ' . $setup_info['dcl']['currentver'] . ' Completed.<br/>';
			}

			$content .= '</div>';
		}
		else
		{
			// Up to date
			$content = _OKIMG . '&nbsp;<b>DCL is already up to date.</b>';
			$result = true;
		}

		$b_back = array();
		if (!$result) {
			$content .= "<p>"._INSTALL_L135."</p>\n";
			$b_back = array();
		} else {
			$content .= "<p>"._INSTALL_L136."</p>\n";
			$b_next = array('finish', _INSTALL_L117);
		}
	}
	else
	{
		// No dcl_config, it's OLD!  20010321 introduced the dcl_config table, so if it's a couple of years
		// old, tell user that it will have to be upgraded to this version before upgrade can be done here
		// Think of this as penance for waiting so long to upgrade ;-)
		$content = 'You need to run the upgrade scripts for your database up to 20010321.  This version';
		$content .= ' introduced the dcl_config table that tracks system settings and current version.';
		$content .= '  See the doc directory for how the upgrade process works.  After upgrading, select';
		$content .= ' Reload to continue the upgrade process.';
        $b_reload = true;
	}

	include 'install_tpl.php';
	break;

case "insertData":
    include_once "../inc/config.php";
	include_once "../inc/functions.inc.php";
    include_once './default_records.inc.php';
    
    $oDB = new DbProvider;
	include_once 'setup.inc.php'; // gets current version
    $oDB->Execute("INSERT INTO dcl_config (dcl_config_name, dcl_config_field, dcl_config_varchar) VALUES ('DCL_VERSION', 'dcl_config_varchar', " . $oDB->Quote($setup_info['dcl']['version']) . ")");

	$content = '<div style="width: 50%; text-align: left; padding-left: 200px;">';
    $content .= $GLOBALS['__import_content__'];
	$content .= '</div>';

    $b_next = array('finish', _INSTALL_L117);
    $title = _INSTALL_L116;
    include 'install_tpl.php';

    break;

case 'finish':

    $title = _INSTALL_L32;
    $content = "";
    include './language/'.$language.'/finish.php';
    include 'install_tpl.php';
    break;
}

/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname)
{
    $dirlist = array();
    if (is_dir($dirname) && $handle = opendir($dirname)) {
        while (false !== ($file = readdir($handle))) {
            if ( !preg_match("/^[.]{1,2}$/",$file) ) {
                if (mb_strtolower($file) != 'cvs' && is_dir($dirname.$file) ) {
                    $dirlist[$file]=$file;
                }
            }
        }
        closedir($handle);
        asort($dirlist);
        reset($dirlist);
    }
    return $dirlist;
}

function check_language($language){
     if ( file_exists('../modules/system/language/'.$language.'/modinfo.php') ) {
        return $language;
    } else {
        return 'english';
    }
}
