'name' => 'fi.opengov.datacatalog',
'version' => '0.0.1',
'state' => 'devel',
'class_definitions' => array
(
    'midcom_dba_classes.inc',
),
'package.xml' => array
(
    'summary' => 'Open Data Catalog',
    'description' => 'Catalog of open public data',
    'maintainers' => array
    (
        'bergie' => array
        (
            'name' => 'Henri Bergius',
            'email' => 'henri.bergius@iki.fi',
            'role' => 'lead',
            'active' => 'yes',
        ),
    ),
    'dependencies' => array
    (
        'midcom.helper.datamanager2' => array(),
        'midcom' => array(),
    ),
),
'customdata' => array
(
    'skip_cache_generation' => true,
),
