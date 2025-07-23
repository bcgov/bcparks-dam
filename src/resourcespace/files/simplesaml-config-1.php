$plugins[] = 'simplesaml';

$simplesamlconfig['config'] = [
    'baseurlpath' => $baseurl . '/plugins/simplesaml/lib/public/',
    'certdir' => 'cert/',
    'loggingdir' => '/var/www/resourcespace/filestore/simplesaml/log/',
    'datadir' => '/var/www/resourcespace/filestore/simplesaml/data/',
    'tempdir' => '/var/www/resourcespace/filestore/tmp/simplesaml/',
    'timezone' => 'America/Vancouver',
