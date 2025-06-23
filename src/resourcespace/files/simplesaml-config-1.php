$plugins[] = 'simplesaml';

$simplesamlconfig['config'] = [
    'baseurlpath' => $baseurl . '/plugins/simplesaml/lib/public/',
    'certdir' => 'cert/',
    'loggingdir' => '/filestore/simplesaml/log/',
    'datadir' => '/filestore/simplesaml/data/',
    'tempdir' => '/filestore/tmp/simplesaml/',
    'timezone' => 'America/Vancouver',
