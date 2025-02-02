<?php
# See includes/DefaultSettings.php for all configurable settings
# and their default values, but don't forget to make changes in _this_
# file, not there.
#
# Further documentation for configuration settings may be found at:
# https://www.mediawiki.org/wiki/Manual:Configuration_settings

# Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

# Allow uploads via URL
$wgAllowCopyUploads = true;
$wgEnableAPI = true;
$wgEnableWriteAPI = true;

ini_set('memory_limit', '512M');
$wgReadOnly = getenv('MW_WG_READONLY') ?: false;
ini_set('display_errors', false);

if (getenv('MW_DEBUG')) {    
    $wgShowExceptionDetails = true;
    $wgShowDBErrorBacktrace = true;
    $wgDebugToolbar = true;
}

## Uncomment this to disable output compression
# $wgDisableOutputCompression = true;

$wgSitename = getenv('MW_WG_SITENAME');
$wgMetaNamespace = getenv('MW_WG_METANAMESPACE');

## The URL base path to the directory containing the wiki;
## defaults for all runtime URL paths are based off of this.
## For more information on customizing the URLs
## (like /w/index.php/Page_title to /wiki/Page_title) please see:
## https://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = "";
$wgScriptExtension = ".php";

## Path to articles is set up so that pages are reachable on /Page_Name
$wgArticlePath = "/$1";

## The protocol and server name to use in fully-qualified URLs
$wgServer = getenv('MW_WG_SERVER');

## The relative URL path to the skins directory
$wgStylePath = "$wgScriptPath/skins";

## The relative URL path to the logo.  Make sure you change this from the default,
## or else you'll overwrite your logo when you upgrade!
$wgLogo = getenv("MW_WG_LOGO") ?: "$wgScriptPath/resources/assets/wiki.png";

## UPO means: this is also a user preference option

$wgEnableEmail = getenv("MW_WG_ENABLE_EMAIL") ?: false;
$wgEnableUserEmail = true; # UPO

$wgEmergencyContact = getenv("MW_WG_EMERGENCY_CONTACT") ?: "apache@localhost";
$wgPasswordSender = getenv("MW_WG_PASSWORD_SENDER") ?: "apache@localhost";

$wgEnotifUserTalk = false; # UPO
$wgEnotifWatchlist = false; # UPO
$wgEmailAuthentication = true;

## Database settings
$wgDBtype = "mysql";
$wgDBserver = getenv('MW_WG_DBSERVER') ?: "mysql";
$wgDBname = getenv('MW_WG_DBNAME') ?: "mediawiki";
$wgDBuser = getenv('MW_WG_DBUSER');
$wgDBpassword = getenv('MW_WG_DBPASS');

# MySQL specific settings
$wgDBprefix = "";

# MySQL table options to use during installation or update
$wgDBTableOptions = "ENGINE=InnoDB, DEFAULT CHARSET=utf8";

# Experimental charset support for MySQL 5.0.
$wgDBmysql5 = true;

## Shared memory settings
$wgMainCacheType = CACHE_NONE;
$wgMemCachedServers = array();

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads = true;
$wgUseImageMagick = true;
$wgImageMagickConvertCommand = "/usr/bin/convert";

# InstantCommons allows wiki to use images from http://commons.wikimedia.org
$wgUseInstantCommons = false;

# Allow external images
$wgAllowExternalImages = true;

## If you use ImageMagick (or any other shell command) on a
## Linux server, this will need to be set to the name of an
## available UTF-8 locale
$wgShellLocale = getenv("MW_WG_SHELL_LOCALE") ?: "C.UTF-8";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
#$wgHashedUploadDirectory = false;

## Set $wgCacheDirectory to a writable directory on the web server
## to make your wiki go slightly faster. The directory should not
## be publically accessible from the web.
#$wgCacheDirectory = "$IP/cache";

# Site language code, should be one of the list in ./languages/Names.php
$wgLanguageCode = getenv("MW_WG_LANGUAGECODE");

$wgSecretKey = getenv("MW_WG_SECRET");

# Site upgrade key. Must be set to a string (default provided) to turn on the
# web installer while LocalSettings.php is in place
$wgUpgradeKey = getenv("MW_WG_UPGRADE_KEY") ?: "b31022590a7b3b8f";

if (getenv('MW_DISABLE_API')) {
    $wgEnableAPI = false;
}
if (getenv('MW_DISABLE_FEED')) {
    $wgFeed = false;
}

if (getenv('MW_REFERRER_POLICY')) {
    $wgReferrerPolicy = getenv('MW_REFERRER_POLICY');
}

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "https://creativecommons.org/licenses/by-sa/3.0/";
$wgRightsText = "Creative Commons Attribution-ShareAlike";
$wgRightsIcon = "$wgResourceBasePath/resources/assets/licenses/cc-by-sa.png";

# Path to the GNU diff3 utility. Used for conflict resolution.
$wgDiff3 = "/usr/bin/diff3";

# Allow additional extensions to be uploaded
$wgFileExtensions[] = 'mp3';
$wgFileExtensions[] = 'wav';
$wgFileExtensions[] = 'pdf';
$wgFileExtensions[] = 'ogg';
$wgFileExtensions[] = 'tmx';
$wgFileExtensions[] = 'ase';
$wgFileExtensions[] = 'svg';

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'vector', 'monobook':
$wgDefaultSkin = "vector";

# Enabled skins.
# The following skins were automatically enabled:
wfLoadSkin( 'MonoBook' );
wfLoadSkin( 'Vector' );
# This one is available on demand, move along, there is nothing to see here
getenv('MW_SKIN_MATERIAL_ENABLE') !== false && wfLoadSkin( 'Material' );

# Subpages
if (getenv('MW_NS_WITH_SUBPAGES_MAIN')) {
    $wgNamespacesWithSubpages[NS_MAIN] = true;
}
if (getenv('MW_NS_WITH_SUBPAGES_TEMPLATE')) {
    $wgNamespacesWithSubpages[NS_TEMPLATE] = true;
}

# Enabled Extensions. Most extensions are enabled by including the base extension file here
# but check specific extension documentation for more details
# The following extensions were automatically enabled:
wfLoadExtension( 'Interwiki' );
wfLoadExtension( 'Renameuser' );
wfLoadExtension( 'SyntaxHighlight_GeSHi' );
wfLoadExtension( 'ParserFunctions' );
if (getenv('MV_PARSERFUNCTIONS_ENABLE_STRING_FUNCTIONS')) {
    $wgPFEnableStringFunctions = true;
}
wfLoadExtension( 'ImageMap' );

# VisualEditor Extension
wfLoadExtension( 'VisualEditor' );

# Enable by default for everybody
$wgDefaultUserOptions['visualeditor-enable'] = 1;

# Don't allow users to disable it
# $wgHiddenPrefs[] = 'visualeditor-enable';

# OPTIONAL: Enable VisualEditor's experimental code features
# #$wgDefaultUserOptions['visualeditor-enable-experimental'] = 1;

$wgVirtualRestConfig['modules']['parsoid'] = array(
  // URL to the Parsoid instance
  'url' => getenv("MW_WG_PARSOID_URL") ?: 'http://parsoid:8000',
  'domain' => 'wiki',
  'prefix' => '',
  'forwardCookies' => true,
);

# TemplateData Extension
wfLoadExtension( 'TemplateData' );

# CategoryTree Extension
wfLoadExtension( 'CategoryTree' );
$wgUseAjax = true;
if (getenv('MW_CATEGORYTREE_SIDEBAR_ROOT')) {
    $wgCategoryTreeSidebarRoot = getenv('MW_CATEGORYTREE_SIDEBAR_ROOT');
}

# ExternalData
wfLoadExtension( 'ExternalData' );

if (getenv('MW_EXTERNALDATA_DIRECTORY_PATH')) {
    $edgDirectoryPath = json_decode(getenv('MW_EXTERNALDATA_DIRECTORY_PATH'), true);
}
	
# Semantic Stuff
wfLoadExtension( 'SemanticMediaWiki' );
enableSemantics( 'wiki.tuxemon.org' );
wfLoadExtension( 'SemanticCompoundQueries' );
#wfLoadExtension( 'SemanticDrilldown' );
#require_once "$IP/extensions/SemanticInternalObjects/SemanticInternalObjects.php";
wfLoadExtension( 'PageForms' );

# HierarchyBuilder Extension
wfLoadExtension( 'HierarchyBuilder' );

# more exts
wfLoadExtension( 'Arrays' );
wfLoadExtension( 'HeaderTabs' );
wfLoadExtension( 'ApprovedRevs' );

# NativeSvgHandler needs opt-in due to xss concerns
if (getenv('MW_NATIVESVGHANDLER')) {
    wfLoadExtension( 'NativeSvgHandler' );
}

# for easing migrations
wfLoadExtension( 'ReplaceText' );
$wgGroupPermissions['bureaucrat']['replacetext'] = true;

if (getenv("MW_USERMERGE")) {
    wfLoadExtension( 'UserMerge' );
    $wgGroupPermissions['bureaucrat']['usermerge'] = getenv('MW_PERMISSION_BUREAUCRAT_USERMERGE') ?: false;;
}

if (getenv('MW_WG_RAWHTML') === 'true') {
    $wgRawHtml = true;
}

# Rights config
$wgGroupPermissions['*']['read'] = getenv("MW_PERMISSIONS_READ") ?: true;
$wgGroupPermissions['user']['read'] = getenv("MW_PERMISSIONS_USER_READ") ?: true;

$wgGroupPermissions['*']['edit'] = getenv("MW_PERMISSIONS_EDIT") ?: false;
$wgGroupPermissions['user']['edit'] = getenv("MW_PERMISSIONS_USER_EDIT") ?: true;

$wgGroupPermissions['*']['createpage'] = getenv("MW_PERMISSIONS_CREATEPAGE") ?: false;
$wgGroupPermissions['user']['createpage'] = getenv("MW_PERMISSIONS_USER_CREATEPAGE") ?: true;

$wgGroupPermissions['*']['editmyprivateinfo'] = getenv("MW_PERMISSIONS_EDITMYPRIVATEINFO") ?: true;

# Rights for auth config
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['*']['autocreateaccount'] = getenv('MW_AUTH_AUTOCREATEUSER') ?: false;

# Auth_remoteuser Extension
if (getenv('MW_AUTH_REMOTEUSER')) {
    $wgAuthRemoteuserUserName = getenv('MW_AUTH_REMOTEUSER_USER_NAME') ?: null;
}

# Pluggable Auth
if (getenv('MW_AUTH_PLUGGABLE')) {
    wfLoadExtension( 'PluggableAuth' );
    $wgPluggableAuth_EnableAutoLogin = getenv('MW_AUTH_PLUGGABLE_ENABLE_AUTO_LOGIN') ?: false;
    $wgPluggableAuth_EnableLocalLogin = getenv('MW_AUTH_PLUGGABLE_ENABLE_LOCAL_LOGIN') ?: false;
    $wgPluggableAuth_EnableLocalProperties = getenv('MW_AUTH_PLUGGABLE_ENABLE_LOCAL_PROPERTIES') ?: false;
}

# OpenID Connect
if (getenv('MW_AUTH_OIDC')) {
    wfLoadExtension( 'OpenIDConnect' );
    $wgOpenIDConnect_Config[getenv('MW_AUTH_OIDC_IDP_URL')] = [
        'clientID' => getenv('MW_AUTH_OIDC_CLIENT_ID'),
        'clientsecret' => getenv('MW_AUTH_OIDC_CLIENT_SECRET'),
    ];
    if (getenv('MW_AUTH_OIDC_SCOPE')) {
	$wgOpenIDConnect_Config[getenv('MW_AUTH_OIDC_IDP_URL')]['scope'] = explode(" ", getenv('MW_AUTH_OIDC_SCOPE'));
    }
    $wgOpenIDConnect_UseRealNameAsUserName = getenv('MW_AUTH_OIDC_USE_REAL_NAME_AS_USERNAME') ?: false;
    $wgOpenIDConnect_UseEmailNameAsUserName = getenv('MW_AUTH_OIDC_USER_EMAIL_NAME_AS_USERNAME') ?: false;
    $wgOpenIDConnect_MigrateUsersByUserName = getenv('MW_AUTH_OIDC_MIGRATE_USERS_BY_USERNAME') ?: false;
    $wgOpenIDConnect_MigrateUsersByEmail = getenv('MW_AUTH_OIDC_MIGRATE_USERS_BY_EMAIL') ?: false;
    $wgOpenIDConnect_ForceLogout = getenv('MW_AUTH_OIDC_FORCE_LOGOUT') ?: false;
    // override this when you can't change the 'sub' claim because you want to update keycloak
    $wgOpenIDConnect_SubjectUserInfoClaim = getenv('MW_AUTH_OIDC_SUBJECT_USERINFO_CLAIM') ?: 'sub';
}

if (getenv('MW_FILE_EXTENSION_ALLOW_SVG')) {
    $wgFileExtensions[] = 'svg';
}

# Spam
wfLoadExtension( 'StopForumSpam' );
wfLoadExtension( 'ConfirmEdit' );
wfLoadExtension( 'SmiteSpam' );
