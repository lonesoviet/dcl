<?php
	define('STR_CFG_TITLE', "Configuracion DCL");
	define('STR_CFG_DESC', "Esta pantalla le permitira configurar las opciones por defecto y funcionamiento del DCL");
	define('STR_CFG_SYSTEMTITLE', "Configuracion del sistema");
	define('STR_CFG_DATEFORMAT', "Formato de fecha");
	define('STR_CFG_DATEFORMATHELP', "The format to use for displaying dates.");
	define('STR_CFG_DATEFORMATDB', "Formato de fecha del servidor SQL");
	define('STR_CFG_DATEFORMATDBHELP', "The format the database uses for storing dates.");
	define('STR_CFG_TIMESTAMPFORMAT', "Timestamp Display Format");
	define('STR_CFG_TIMESTAMPFORMATHELP', "The format to use for displaying timestamps.");
	define('STR_CFG_TIMESTAMPFORMATDB', "SQL Server Timestamp Format");
	define('STR_CFG_TIMESTAMPFORMATDBHELP', "The format the database uses for storing timestamps.");
	define('STR_CFG_MAXUPLOADFILESIZE', "Tama�o maximo de carga de fichero");
	define('STR_CFG_MAXUPLOADFILESIZEHELP', "Maximum file size allowed for uploading attachments.  0 disables file uploads.  This value only takes effect up to the limit specified in php.ini.");
	define('STR_CFG_LANGUAGE', "Default Language");
	define('STR_CFG_LANGUAGEHELP', "The language that is used by default for users and the login page.");
	define('STR_CFG_PRIVATEKEY', "Private Key");
	define('STR_CFG_PRIVATEKEYHELP', "This key is appended to any values to be MD5 hashed.");
	define('STR_CFG_ROOT', "Web Root For DCL");
	define('STR_CFG_ROOTHELP', "This is the root URL of DCL for this installation. (Example: http://www.mydomain.com/dcl/)");
	define('STR_CFG_GDTYPE', "Graphics Format For Graphs");
	define('STR_CFG_GDTYPEHELP', "The graphics format to use when generating graphs in DCL.");
	define('STR_CFG_LOOKNFEELTITLE', "Look And Feel");
	define('STR_CFG_DEFTEMPLATESET', "Default Template Set");
	define('STR_CFG_WORKORDERTITLE', "Work Orders");
	define('STR_CFG_DEFAULTSTATUSASSIGN', "Default Status For Assigned Work Orders");
	define('STR_CFG_DEFAULTSTATUSASSIGNHELP', "The default status for new work orders.");
	define('STR_CFG_DEFAULTSTATUSUNASSIGN', "Default Status For Unassigned Work Orders");
	define('STR_CFG_DEFAULTSTATUSUNASSIGNHELP', "The default status for new work orders where the originator is not able to assign it to another user.");
	define('STR_CFG_DEFAULTPRIORITY', "Default Priority For Unassigned Work Orders");
	define('STR_CFG_DEFAULTPRIORITYHELP', "The default priority for new unassigned work orders.");
	define('STR_CFG_DEFAULTSEVERITY', "Default Severity For Unassigned Work Orders");
	define('STR_CFG_DEFAULTSEVERITYHELP', "The default severity for new unassigned work orders.");
	define('STR_CFG_AUTODATE', "Auto-Fill Dates");
	define('STR_CFG_AUTODATEHELP', "This option will autofill the start date and deadline with tomorrow\'s date.");
	define('STR_CFG_TIMECARDORDER', "Display Order Of Time Cards");
	define('STR_CFG_TIMECARDORDERHELP', "The order that time cards are displayed.");
	define('STR_CFG_WONOTIFICATIONHTML', "HTML Notifications");
	define('STR_CFG_WONOTIFICATIONHTMLHELP', "Send work order notifications as HTML.  The notification template specified should be in HTML format.");
	define('STR_CFG_WOEMAILTEMPLATE', "Notification Template");
	define('STR_CFG_WOEMAILTEMPLATEHELP', "Notification template to use.  This is located in the templates/custom directory.");
	define('STR_CFG_WOSECONDARYACCOUNTSENABLED', "Multiple Organizations");
	define('STR_CFG_WOSECONDARYACCOUNTSENABLEDHELP', "This allows users to associate more than one organization with a work order.  This is currently not compatible with MySQL.");
	define('STR_CFG_PROJECTTITLE', "Projects");
	define('STR_CFG_DCLPROJECTXMLTEMPLATES', "Use XML Templates (Requires XML Support)");
	define('STR_CFG_DCLPROJECTXMLTEMPLATESHELP', "Enables the option to use a project XML template when creating new projects.  Templates contain work orders and can accept parameters to change certain attributes of them.");
	define('STR_CFG_TICKETTITLE', "Tickets");
	define('STR_CFG_CQQPERCENT', "Percent Chance to Send Quality Questionnaire");
	define('STR_CFG_CQQPERCENTHELP', "The percentage of resolved tickets that will send a quality questionnaire to the contact associated with the ticket.");
	define('STR_CFG_CQQFROM', "Quality Questionnaire From E-mail Address");
	define('STR_CFG_CQQFROMHELP', "The e-mail address the quality questionnaire originates from.");
	define('STR_CFG_CQQSUBJECT', "Quality Questionnaire E-mail Subject");
	define('STR_CFG_CQQSUBJECTHELP', "The e-mail subject line for the quality questionnaire.");
	define('STR_CFG_CQQTEMPLATE', "Quality Questionnaire E-mail Template");
	define('STR_CFG_CQQTEMPLATEHELP', "The template file (located in templates/custom directory) to use for the questionnaire.  This is the body of the e-mail message.");
	define('STR_CFG_EMAILSERVERTITLE', "E-Mail SMTP Server");
	define('STR_CFG_SMTPSERVER', "SMTP Server");
	define('STR_CFG_SMTPSERVERHELP', "Enter the SMTP server address that DCL will use to send e-mail.");
	define('STR_CFG_SMTPPORT', "SMTP Port");
	define('STR_CFG_SMTPPORTHELP', "The port the SMTP server is listening on.  This is normally 25.");
	define('STR_CFG_SMTPTIMEOUT', "SMTP Connection Timeout (seconds)");
	define('STR_CFG_SMTPTIMEOUTHELP', "The number of seconds to wait for a successful connection to the SMTP server.");
	define('STR_CFG_SMTPENABLED', "SMTP Enabled");
	define('STR_CFG_SMTPENABLEDHELP', "This enables e-mails to be sent from DCL.  If this option is turned off, no e-mail is sent from DCL for any reason.");
	define('STR_CFG_SMTPAUTHREQUIRED', "SMTP Auth Required");
	define('STR_CFG_SMTPAUTHREQUIREDHELP', "Check this if your SMTP server requires authentication before sending e-mail.");
	define('STR_CFG_SMTPAUTHUSER', "SMTP Auth User");
	define('STR_CFG_SMTPAUTHUSERHELP', "The user name to authenticate to the server with.");
	define('STR_CFG_SMTPAUTHPWD', "SMTP Auth Password");
	define('STR_CFG_SMTPAUTHPWDHELP', "The password for the SMTP server user.");
	define('STR_CFG_SMTPDEFAULTEMAIL', "Default e-Mail Address");
	define('STR_CFG_SMTPDEFAULTEMAILHELP', "This is the e-mail address that e-mails will appear to come from if the user causing the notification to be sent does not have an e-mail address.");
	define('STR_CFG_DCLPROJECTINCLUDECHILDSTATS', "Include Child Project Statistics In Detail");
	define('STR_CFG_DCLPROJECTINCLUDECHILDSTATSHELP', "When calculating the project statistics for the detail, include the child project work orders in the calculations.");
	define('STR_CFG_DCLPROJECTINCLUDEPARENTSTATS', "Include Parent Project Statistics In Detail");
	define('STR_CFG_DCLPROJECTINCLUDEPARENTSTATSHELP', "When calculating the project statistics for the detail, include the parent project work orders in the calculations.");
	define('STR_CFG_DCLPROJECTBROWSEPARENTSONLY', "Only Show Top Level Projects In Browse");
	define('STR_CFG_DCLPROJECTBROWSEPARENTSONLYHELP', "When browsing projects, only show the projects that have no parents.");
	define('STR_CFG_APPNAME', "Application Name");
	define('STR_CFG_APPNAMEHELP', "This is the name to use for the application.");
	define('STR_CFG_LOGINMESSAGE', "Login Message");
	define('STR_CFG_LOGINMESSAGEHELP', "This is the welcome message to display on the login screen.");
	define('STR_CFG_HTMLTITLE', "Title for HTML Pages");
	define('STR_CFG_HTMLTITLEHELP', "This is the title attribute for the main page.  It will display in the browser title bar and be preceded by the logged in user and domain.");
	define('STR_CFG_FILEPATH', "Path for Files");
	define('STR_CFG_FILEPATHHELP', "The root path where file attachments are stored for this DCL domain.");
	define('STR_CFG_DCLDEFAULTPROJECTSTATUS', "Default Project Status");
	define('STR_CFG_DCLDEFAULTPROJECTSTATUSHELP', "The initial status of all new projects.");
	define('STR_CFG_DCLDEFAULTTICKETSTATUS', "Default Ticket Status");
	define('STR_CFG_DCLDEFAULTTICKETSTATUSHELP', "The initial status of new tickets.");
	define('STR_CFG_TCKNOTIFICATIONHTML', "HTML Notifications");
	define('STR_CFG_TCKNOTIFICATIONHTMLHELP', "Send ticket notifications in HTML format.  If this is selected, make sure you choose a HTML template.");
	define('STR_CFG_TCKEMAILTEMPLATE', "Ticket Notification Template");
	define('STR_CFG_TCKEMAILTEMPLATEHELP', "Template file (located in templates/custom directory) to use for ticket notifications.");
	define('STR_CFG_GATEWAYTICKETTITLE', "e-Mail Gateway for Tickets");
	define('STR_CFG_GATEWAYTICKETENABLED', "e-Mail Gateway for Tickets Enabled");
	define('STR_CFG_GATEWAYTICKETENABLEDHELP', "Enables e-mail gateway for tickets.  This allows you to set up e-mail addresses where users can submit tickets to.");
	define('STR_CFG_GATEWAYTICKETAUTORESPOND', "Send Auto-Response to Sender Upon Receipt");
	define('STR_CFG_GATEWAYTICKETAUTORESPONDHELP', "If this option is selected, a confirmation e-mail is sent to the originator with the ticket number.");
	define('STR_CFG_GATEWAYTICKETAUTORESPONSEEMAIL', "Auto-Response e-Mail Address");
	define('STR_CFG_GATEWAYTICKETAUTORESPONSEEMAILHELP', "The e-mail address the e-mail should appear to come from for the auto-response e-mail.");
	define('STR_CFG_GATEWAYTICKETREPLY', "Allow Replies to Append Ticket Resolutions");
	define('STR_CFG_GATEWAYTICKETREPLYHELP', "If this option is enabled, replies to the auto-response e-mail will append resolutions to the ticket.");
	define('STR_CFG_GATEWAYTICKETSTATUS', "Gateway Ticket Status");
	define('STR_CFG_GATEWAYTICKETSTATUSHELP', "The default status to assign new tickets generated by the gateway.");
	define('STR_CFG_GATEWAYTICKETPRIORITY', "Gateway Ticket Priority");
	define('STR_CFG_GATEWAYTICKETPRIORITYHELP', "The default priority to assign new tickets generated by the gateway.");
	define('STR_CFG_GATEWAYTICKETSEVERITY', "Gateway Ticket Severity");
	define('STR_CFG_GATEWAYTICKETSEVERITYHELP', "The default severity to assign new tickets generated by the gateway.");
	define('STR_CFG_GATEWAYTICKETFILEPATH', "Decode File Path for Ticket Attachments");
	define('STR_CFG_GATEWAYTICKETFILEPATHHELP', "The temporary working directory to use for decoding the attachments in e-mails received by the ticket gateway.");
	define('STR_CFG_GATEWAYTICKETREPLYLOGGEDBY', "Log Replies As User");
	define('STR_CFG_GATEWAYTICKETREPLYLOGGEDBYHELP', "If ticket replies are enabled, this selects the DCL user to log the replies as.");
	define('STR_CFG_GATEWAYTICKETACCOUNT', "Gateway Ticket Organization");
	define('STR_CFG_GATEWAYTICKETACCOUNTHELP', "The default organization to assign a ticket to.");
	define('STR_CFG_SESSIONTIMEOUT', "Session Timeout (Minutes)");
	define('STR_CFG_SESSIONTIMEOUTHELP', "The number of minutes a session is alive without any activity.  Sessions are purged after this time.");
	define('STR_CFG_WIKI', "Wiki");
	define('STR_CFG_WIKIENABLED', "Enable Wiki");
	define('STR_CFG_WIKIENABLEDHELP', "Enables the Wiki.  Wikis can be created for work orders, projects, products, and a global Wiki is also available.");
	define('STR_CFG_GATEWAYWOTITLE', "e-Mail Gateway for Work Orders");
	define('STR_CFG_GATEWAYWOENABLED', "e-Mail Gateway for Work Orders Enabled");
	define('STR_CFG_GATEWAYWOENABLEDHELP', "Enables e-mail gateway for work orders.  This allows you to set up e-mail addresses where users can submit work orders to.");
	define('STR_CFG_GATEWAYWOAUTORESPOND', "Send Auto-Response to Sender Upon Receipt");
	define('STR_CFG_GATEWAYWOAUTORESPONDHELP', "If this option is selected, a confirmation e-mail is sent to the originator with the work order number.");
	define('STR_CFG_GATEWAYWOAUTORESPONSEEMAIL', "Auto-Response e-Mail Address");
	define('STR_CFG_GATEWAYWOAUTORESPONSEEMAILHELP', "The e-mail address the e-mail should appear to come from for the auto-response e-mail.");
	define('STR_CFG_GATEWAYWOREPLY', "Allow Replies to Append Time Cards");
	define('STR_CFG_GATEWAYWOREPLYHELP', "If this option is enabled, replies to the auto-response e-mail will append time cards to the work order.");
	define('STR_CFG_GATEWAYWOSTATUS', "Gateway Work Order Status");
	define('STR_CFG_GATEWAYWOSTATUSHELP', "The default status to assign new work orders generated by the gateway.");
	define('STR_CFG_GATEWAYWOPRIORITY', "Gateway Work Order Priority");
	define('STR_CFG_GATEWAYWOPRIORITYHELP', "The default priority to assign new work orders generated by the gateway.");
	define('STR_CFG_GATEWAYWOSEVERITY', "Gateway Work Order Severity");
	define('STR_CFG_GATEWAYWOSEVERITYHELP', "The default severity to assign new work orders generated by the gateway.");
	define('STR_CFG_GATEWAYWOFILEPATH', "Decode File Path for Work Order Attachments");
	define('STR_CFG_GATEWAYWOFILEPATHHELP', "The temporary working directory to use for decoding the attachments in e-mails received by the work order gateway.");
	define('STR_CFG_GATEWAYWOREPLYLOGGEDBY', "Log Replies As User");
	define('STR_CFG_GATEWAYWOREPLYLOGGEDBYHELP', "If work order replies are enabled, this selects the DCL user to log the replies as.");
	define('STR_CFG_GATEWAYWOACCOUNT', "Gateway Work Order Organization");
	define('STR_CFG_GATEWAYWOACCOUNTHELP', "The default organization to assign a work order to.");
	define('STR_CFG_SCM', "Software Configuration Management");
	define('STR_CFG_SCCSENABLED', "SCCS Integration Enabled");
	define('STR_CFG_SCCSENABLEDHELP', "Enables integration with source code control systems.  This makes ChangeLog an available option for work orders and projects.");
	define('STR_CFG_BUILDMANAGERENABLED', "Build Manager Enabled (Requires SCCS)");
	define('STR_CFG_BUILDMANAGERENABLEDHELP', "Enables the build manager.  This enables a user to create builds and associate ChangeLog entries with the build for tracking purposes.");
	define('STR_CFG_SECAUDITENABLED', "Security Auditing Enabled");
	define('STR_CFG_SECAUDITENABLEDHELP', "Enables security auditing. By default, security auditing tracks logins, logouts, and any time the user hits main.php.  Reports are available in the admin section to view a users history.");
	define('STR_CFG_SECAUDITLOGINONLY', "Only audit login events");
	define('STR_CFG_SECAUDITLOGINONLYHELP', "Restricts security auditing to login and logout events only. By default, security auditing tracks logins, logouts, and any time the user hits main.php.");
?>