    'database.dsn' => 'mysql:host=localhost;dbname=saml',
    'database.prefix' => '',
    'database.persistent' => false,
    'database.slaves' => [],
    'proxy' => null,
    'proxy.auth' => false,

    'admin.protectindexpage' => true,
    'admin.protectmetadata' => false,
    'admin.checkforupdates' => true,

    'trusted.url.domains' => [],
    'trusted.url.regex' => false,
    'enable.http_post' => false,

    'debug' => [
        'saml' => true,
        'backtraces' => true,
        'validatexml' => false,
    ],
    'showerrors' => true,
    'errorreporting' => true,

    # The following line is causing an error
    'logging.level' => 6,
    #'logging.handler' => 'syslog',
    #'logging.format' => '%date{%b %d %H:%M:%S} %process %level %stat[%trackid] %msg',
    #'logging.facility' => defined('LOG_LOCAL5') ? constant('LOG_LOCAL5') : LOG_USER,
    #'logging.processname' => 'simplesamlphp',
    #'logging.logfile' => 'simplesamlphp.log',
    'statistics.out' => [# Log statistics to the normal log.
        /*
        array(
            'class' => 'core:Log',
            'level' => 'notice',
        ),
        */
        # Log statistics to files in a directory. One file per day.
        /*
        array(
            'class' => 'core:File',
            'directory' => '/var/log/stats',
        ),
        */
    ],



    /*************
     | PROTOCOLS |
     *************/

    /*
     * Which functionality in SimpleSAMLphp do you want to enable. Normally you would enable only
     * one of the functionalities below, but in some cases you could run multiple functionalities.
     * In example when you are setting up a federation bridge.
    */
    'enable.saml20-idp' => true,
    'enable.shib13-idp' => false,
    'enable.adfs-idp' => false,
    'enable.wsfed-sp' => false,
    'enable.authmemcookie' => false,

    /*
     * Default IdP for WS-Fed.
    */
    'default-wsfed-idp' => 'urn:federation:pingfederate:localhost',

    /*
     * Whether SimpleSAMLphp should sign the response or the assertion in SAML 1.1 authentication
     * responses.
     *
     * The default is to sign the assertion element, but that can be overridden by setting this
     * option to TRUE. It can also be overridden on a pr. SP basis by adding an option with the
     * same name to the metadata of the SP.
    */
    'shib13.signresponse' => true,



    /***********
     | MODULES |
     ***********/

    /*
     * Configuration to override module enabling/disabling.
     *
     * Example:
     *
     * 'module.enable' => array(
     *      'exampleauth' => TRUE, # Setting to TRUE enables.
     *      'saml' => FALSE, # Setting to FALSE disables.
     *      'core' => NULL, # Unset or NULL uses default.
     * ),
     *
    */



    /*************************
     | SESSION CONFIGURATION |
     *************************/

    /*
     * This value is the duration of the session in seconds. Make sure that the time duration of
     * cookies both at the SP and the IdP exceeds this duration.
    */
    'session.duration' => 8 * (60 * 60), # 8 hours

    /*
     * Sets the duration, in seconds, data should be stored in the datastore. As the data store is used for
     * login and logout requests, this option will control the maximum time these operations can take.
     * The default is 4 hours (4*60*60) seconds, which should be more than enough for these operations.
    */
    'session.datastore.timeout' => (4 * 60 * 60), # 4 hours

    /*
     * Sets the duration, in seconds, auth state should be stored
    */
    'session.state.timeout' => (60 * 60), # 1 hour

    /*
     * Option to override the default settings for the session cookie name
    */
    'session.cookie.name' => 'SimpleSAMLSessionID',

    /*
     * Expiration time for the session cookie, in seconds
     *
     * Defaults to 0, which means that the cookie expires when the browser is closed
     *
     * Example:
     *  'session.cookie.lifetime' => 30*60,
    */
    'session.cookie.lifetime' => 0,

    /*
     * Limit the path of the cookies
     *
     * Can be used to limit the path of the cookies to a specific subdirectory
     *
     * Example:
     *  'session.cookie.path' => '/simplesaml/',
    */
    #'session.cookie.path' => '/',
    'session.cookie.path' => '/',

    /*
     * Cookie domain
     *
     * Can be used to make the session cookie available to several domains
     *
     * Example:
     *  'session.cookie.domain' => '.example.org',
     */
    'session.cookie.domain' => '.gov.bc.ca',

    /*
     * Set the secure flag in the cookie
     *
     * Set this to TRUE if the user only accesses your service
     * through https. If the user can access the service through
     * both http and https, this must be set to FALSE
     */
    'session.cookie.secure' => false,
    'session.cookie.samesite' => 'Lax',

    /*
     * Options to override the default settings for php sessions
     */
    'session.phpsession.cookiename' => 'RS_SimpleSAML',
    'session.phpsession.savepath' => null,
    'session.phpsession.httponly' => true,

    /*
     * Option to override the default settings for the auth token cookie
     */
    'session.authtoken.cookiename' => 'SimpleSAMLAuthToken',

    /*
     * Options for remember me feature for IdP sessions. Remember me feature
     * has to be also implemented in authentication source used
     *
     * Option 'session.cookie.lifetime' should be set to zero (0), i.e. cookie
     * expires on browser session if remember me is not checked
     *
     * Session duration ('session.duration' option) should be set according to
     * 'session.rememberme.lifetime' option
     *
     * It's advised to use remember me feature with session checking function
     * defined with 'session.check_function' option
     */
    'session.rememberme.enable' => false,
    'session.rememberme.checked' => false,
    'session.rememberme.lifetime' => (14 * 86400),

    /*
     * Custom function for session checking called on session init and loading.
     * See docs/simplesamlphp-advancedfeatures.txt for function code example.
     *
     * Example:
     *   'session.check_function' => array('sspmod_example_Util', 'checkSession'),
     */



    /**************************
     | MEMCACHE CONFIGURATION |
     **************************/

    /*
     * Configuration for the 'memcache' session store. This allows you to store
     * multiple redundant copies of sessions on different memcache servers.
     *
     * 'memcache_store.servers' is an array of server groups. Every data
     * item will be mirrored in every server group.
     *
     * Each server group is an array of servers. The data items will be
     * load-balanced between all servers in each server group.
     *
     * Each server is an array of parameters for the server. The following
     * options are available:
     *  - 'hostname': This is the hostname or ip address where the
     *    memcache server runs. This is the only required option.
     *  - 'port': This is the port number of the memcache server. If this
     *    option isn't set, then we will use the 'memcache.default_port'
     *    ini setting. This is 11211 by default.
     *  - 'weight': This sets the weight of this server in this server
     *    group. http://php.net/manual/en/function.Memcache-addServer.php
     *    contains more information about the weight option.
     *  - 'timeout': The timeout for this server. By default, the timeout
     *    is 3 seconds.
     *
     * Example of redundant configuration with load balancing:
     * This configuration makes it possible to lose both servers in the
     * a-group or both servers in the b-group without losing any sessions.
     * Note that sessions will be lost if one server is lost from both the
     * a-group and the b-group.
     *
     * 'memcache_store.servers' => array(
     *     array(
     *         array('hostname' => 'mc_a1'),
     *         array('hostname' => 'mc_a2'),
     *     ),
     *     array(
     *         array('hostname' => 'mc_b1'),
     *         array('hostname' => 'mc_b2'),
     *     ),
     * ),
     *
     * Example of simple configuration with only one memcache server,
     * running on the same computer as the web server:
     * Note that all sessions will be lost if the memcache server crashes.
     *
     * 'memcache_store.servers' => array(
     *     array(
     *         array('hostname' => 'localhost'),
     *     ),
     * ),
     *
     */
    'memcache_store.servers' => array(
        array(
            array('hostname' => 'localhost'),
        ),
    ),

    /*
     * This value allows you to set a prefix for memcache-keys. The default
     * for this value is 'simpleSAMLphp', which is fine in most cases.
     *
     * When running multiple instances of SSP on the same host, and more
     * than one instance is using memcache, you probably want to assign
     * a unique value per instance to this setting to avoid data collision.
     */
    'memcache_store.prefix' => '',

    /*
     * This value is the duration data should be stored in memcache. Data
     * will be dropped from the memcache servers when this time expires.
     * The time will be reset every time the data is written to the
     * memcache servers.
     *
     * This value should always be larger than the 'session.duration'
     * option. Not doing this may result in the session being deleted from
     * the memcache servers while it is still in use.
     *
     * Set this value to 0 if you don't want data to expire.
     *
     * Note: The oldest data will always be deleted if the memcache server
     * runs out of storage space.
     */
    'memcache_store.expires' => 36 * (60 * 60), # 36 hours.



    /*************************************
     | LANGUAGE AND INTERNATIONALIZATION |
     *************************************/

    /*
     * Languages available, RTL languages, and what language is the default.
    */
    'language.available' => [
        'en', 'no', 'nn', 'se', 'da', 'de', 'sv', 'fi', 'es', 'ca', 'fr', 'it', 'nl', 'lb',
        'cs', 'sk', 'sl', 'lt', 'hr', 'hu', 'pl', 'pt', 'pt_BR', 'tr', 'ja', 'zh', 'zh_TW',
        'ru', 'et', 'he', 'id', 'sr', 'lv', 'ro', 'eu', 'el', 'af', 'zu', 'xh', 'st'
    ],
    'language.rtl' => ['ar', 'dv', 'fa', 'ur', 'he'],
    'language.default' => 'en',

    /*
     * Options to override the default settings for the language parameter
    */
    'language.parameter.name' => 'language',
    'language.parameter.setcookie' => true,

    /*
     * Options to override the default settings for the language cookie
    */
    'language.cookie.name' => 'language',
    'language.cookie.domain' => null,
    'language.cookie.path' => '/',
    'language.cookie.secure' => false,
    'language.cookie.httponly' => false,
    'language.cookie.lifetime' => (60 * 60 * 24 * 900),

    /*
     * Which i18n backend to use.
     *
     * "SimpleSAMLphp" is the home made system, valid for 1.x.
     * For 2.x, only "gettext/gettext" will be possible.
     *
     * Home-made templates will always use "SimpleSAMLphp".
     * To use twig (where avaliable), select "gettext/gettext".
    */
    'language.i18n.backend' => 'SimpleSAMLphp',

    /*
     * Custom getLanguage function called from SimpleSAML\Locale\Language::getLanguage().
     * Function should return language code of one of the available languages or NULL.
     * See SimpleSAML\Locale\Language::getLanguage() source code for more info.
     *
     * This option can be used to implement a custom function for determining
     * the default language for the user.
     *
     * Example:
     *   'language.get_language_function' => array('sspmod_example_Template', 'getLanguage'),
    */

    /*
     * Extra dictionary for attribute names.
     * This can be used to define local attributes.
     *
     * The format of the parameter is a string with <module>:<dictionary>.
     *
     * Specifying this option will cause us to look for modules/<module>/dictionaries/<dictionary>.definition.json
     * The dictionary should look something like:
     *
     * {
     *     "firstattribute": {
     *         "en": "English name",
     *         "no": "Norwegian name"
     *     },
     *     "secondattribute": {
     *         "en": "English name",
     *         "no": "Norwegian name"
     *     }
     * }
     *
     * Note that all attribute names in the dictionary must in lowercase.
     *
     * Example: 'attributes.extradictionary' => 'ourmodule:ourattributes',
    */
    'attributes.extradictionary' => null,



    /**************
     | APPEARANCE |
     **************/

    /*
     * Which theme directory should be used?
    */
    'theme.use' => 'default',

    /*
     * Templating options
     *
     * By default, twig templates are not cached. To turn on template caching:
     * Set 'template.cache' to an absolute path pointing to a directory that
     * SimpleSAMLphp has read and write permissions to.
    */
    #'template.cache' => '',

    /*
     * Set the 'template.auto_reload' to true if you would like SimpleSAMLphp to
     * recompile the templates (when using the template cache) if the templates
     * change. If you don't want to check the source templates for every request,
     * set it to false.
     */
    'template.auto_reload' => false,



    /*********************
     | DISCOVERY SERVICE |
     *********************/

    /*
     * Whether the discovery service should allow the user to save his choice of IdP.
    */
    'idpdisco.enableremember' => true,
    'idpdisco.rememberchecked' => true,

    /*
     * The disco service only accepts entities it knows.
    */
    'idpdisco.validate' => true,
    'idpdisco.extDiscoveryStorage' => null,

    /*
     * IdP Discovery service look configuration.
     * Wether to display a list of idp or to display a dropdown box. For many IdP' a dropdown box
     * gives the best use experience.
     *
     * When using dropdown box a cookie is used to highlight the previously chosen IdP in the dropdown.
     * This makes it easier for the user to choose the IdP
     *
     * Options: [links,dropdown]
    */
    'idpdisco.layout' => 'dropdown',



    /*************************************
     | AUTHENTICATION PROCESSING FILTERS |
     *************************************/

    /*
     * Authentication processing filters that will be executed for all IdPs
     * Both Shibboleth and SAML 2.0
    */
    'authproc.idp' => [
        /* Enable the authproc filter below to add URN prefixes to all attributes
         10 => array(
             'class' => 'core:AttributeMap', 'addurnprefix'
         ), */
        /* Enable the authproc filter below to automatically generated eduPersonTargetedID.
        20 => 'core:TargetedID',
        */

        # Adopts language from attribute to use in UI
        30 => 'core:LanguageAdaptor',

        45 => [
            'class'         => 'core:StatisticsWithAttribute',
            'attributename' => 'realm',
            'type'          => 'saml20-idp-SSO',
        ],

        /* When called without parameters, it will fallback to filter attributes ‹the old way›
         * by checking the 'attributes' parameter in metadata on IdP hosted and SP remote.
         */
        50 => 'core:AttributeLimit',

        /*
         * Search attribute "distinguishedName" for pattern and replaces if found
        60 => array(
            'class' => 'core:AttributeAlter',
            'pattern' => '/OU=studerende/',
            'replacement' => 'Student',
            'subject' => 'distinguishedName',
            '%replace',
        ),
         */

        /*
         * Consent module is enabled (with no permanent storage, using cookies).
        90 => array(
            'class' => 'consent:Consent',
            'store' => 'consent:Cookie',
            'focus' => 'yes',
            'checked' => TRUE
        ),
         */
        # If language is set in Consent module it will be added as an attribute.
        99 => 'core:LanguageAdaptor',
    ],

    /*
     * Authentication processing filters that will be executed for all SPs
     * Both Shibboleth and SAML 2.0
    */
    'authproc.sp' => [
        /*
        10 => array(
            'class' => 'core:AttributeMap', 'removeurnprefix'
        ),
        */

        /*
         * Generate the 'group' attribute populated from other variables, including eduPersonAffiliation.
         60 => array(
            'class' => 'core:GenerateGroups', 'eduPersonAffiliation'
        ),
        */
        /*
         * All users will be members of 'users' and 'members'
        61 => array(
            'class' => 'core:AttributeAdd', 'groups' => array('users', 'members')
        ),
        */

        # Adopts language from attribute to use in UI
        90 => 'core:LanguageAdaptor',

    ],



    /**************************
     | METADATA CONFIGURATION |
     **************************/

    /*
     * This option configures the metadata sources. The metadata sources is given as an array with
     * different metadata sources. When searching for metadata, SimpleSAMLphp will search through
     * the array from start to end.
     *
     * Each element in the array is an associative array which configures the metadata source.
     * The type of the metadata source is given by the 'type' element. For each type we have
     * different configuration options.
     *
     * Flat file metadata handler:
     * - 'type': This is always 'flatfile'.
     * - 'directory': The directory we will load the metadata files from. The default value for
     *                this option is the value of the 'metadatadir' configuration option, or
     *                'metadata/' if that option is unset.
     *
     * XML metadata handler:
     * This metadata handler parses an XML file with either an EntityDescriptor element or an
     * EntitiesDescriptor element. The XML file may be stored locally, or (for debugging) on a remote
     * web server.
     * The XML metadata handler defines the following options:
     * - 'type': This is always 'xml'.
     * - 'file': Path to the XML file with the metadata.
     * - 'url': The URL to fetch metadata from. THIS IS ONLY FOR DEBUGGING - THERE IS NO CACHING OF THE RESPONSE.
     *
     * MDQ metadata handler:
     * This metadata handler looks up for the metadata of an entity at the given MDQ server.
     * The MDQ metadata handler defines the following options:
     * - 'type': This is always 'mdq'.
     * - 'server': Base URL of the MDQ server. Mandatory.
     * - 'validateFingerprint': The fingerprint of the certificate used to sign the metadata. You don't need this
     *                          option if you don't want to validate the signature on the metadata. Optional.
     * - 'cachedir': Directory where metadata can be cached. Optional.
     * - 'cachelength': Maximum time metadata can be cached, in seconds. Defaults to 24
     *                  hours (86400 seconds). Optional.
     *
     * PDO metadata handler:
     * This metadata handler looks up metadata of an entity stored in a database.
     *
     * Note: If you are using the PDO metadata handler, you must configure the database
     * options in this configuration file.
     *
     * The PDO metadata handler defines the following options:
     * - 'type': This is always 'pdo'.
     *
     * Examples:
     *
     * This example defines two flatfile sources. One is the default metadata directory, the other
     * is a metadata directory with auto-generated metadata files.
     *
     * 'metadata.sources' => array(
     *     array('type' => 'flatfile'),
     *     array('type' => 'flatfile', 'directory' => 'metadata-generated'),
     * ),
     *
     * This example defines a flatfile source and an XML source.
     * 'metadata.sources' => array(
     *     array('type' => 'flatfile'),
     *     array('type' => 'xml', 'file' => 'idp.example.org-idpMeta.xml'),
     * ),
     *
     * This example defines an mdq source.
     * 'metadata.sources' => array(
     *      array(
     *          'type' => 'mdq',
     *          'server' => 'http://mdq.server.com:8080',
     *          'cachedir' => '/var/simplesamlphp/mdq-cache',
     *          'cachelength' => 86400
     *      )
     * ),
     *
     * This example defines an pdo source.
     * 'metadata.sources' => array(
     *     array('type' => 'pdo')
     * ),
     *
     * Default:
     * 'metadata.sources' => array(
     *     array('type' => 'flatfile')
     * ),
    */
    'metadata.sources' => [
        ['type' => 'flatfile'],
    ],

    /*
     * Should signing of generated metadata be enabled by default.
     *
     * Metadata signing can also be enabled for an individual SP or IdP by setting the
     * same option in the metadata for the SP or IdP.
    */
    'metadata.sign.enable' => false,

    /*
     * The default key & certificate which should be used to sign generated metadata. These
     * are files stored in the cert dir.
     * These values can be overridden by the options with the same names in the SP or
     * IdP metadata.
     *
     * If these aren't specified here or in the metadata for the SP or IdP, then
     * the 'certificate' and 'privatekey' option in the metadata will be used.
     * if those aren't set, signing of metadata will fail.
    */
    'metadata.sign.privatekey' => null,
    'metadata.sign.privatekey_pass' => null,
    'metadata.sign.certificate' => null,



    /****************************
     | DATA STORE CONFIGURATION |
     ****************************/

    /*
     * Configure the data store for SimpleSAMLphp.
     *
     * - 'phpsession': Limited datastore, which uses the PHP session.
     * - 'memcache': Key-value datastore, based on memcache.
     * - 'sql': SQL datastore, using PDO.
     * - 'redis': Key-value datastore, based on redis.
     *
     * The default datastore is 'phpsession'.
     *
     * (This option replaces the old 'session.handler'-option.)
    */
    #'store.type'                    => 'phpsession',
    'store.type'                    => 'sql',

    /*
     * The DSN the sql datastore should connect to.
     *
     * See http://www.php.net/manual/en/pdo.drivers.php for the various
     * syntaxes.
    */
    #'store.sql.dsn'                 => 'sqlite:/path/to/sqlitedatabase.sq3',
    'store.sql.dsn'                 => 'sqlite:/var/www/resourcespace/filestore/tmp/simplesamldb.sq3',

    /*
     * The username and password to use when connecting to the database.
    */
    'store.sql.username' => null,
    'store.sql.password' => null,

    /*
     * The prefix we should use on our tables.
    */
    'store.sql.prefix' => 'SimpleSAMLphp',

    /*
     * The hostname and port of the Redis datastore instance.
    */
    'store.redis.host' => 'localhost',
    'store.redis.port' => 6379,

    /*
     * The prefix we should use on our Redis datastore.
    */
    'store.redis.prefix' => 'SimpleSAMLphp',
];
