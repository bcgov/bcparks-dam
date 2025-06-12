$plugins[] = 'simplesaml';

$simplesamlconfig['config'] = [
    'baseurlpath' => $baseurl . '/plugins/simplesaml/lib/public/',
    'certdir' => 'cert/',
    'loggingdir' => 'log/',
    'datadir' => 'data/',
    'tempdir' => '/filestore/tmp/simplesaml/',
    'timezone' => 'America/Vancouver',
