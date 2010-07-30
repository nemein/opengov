'name' => 'fi.opengov.datacatalog',
'version' => '0.0.1',
'state' => 'devel',
'class_definitions' => array
(
    'midcom_dba_classes.inc',
),
'watches' => array
(
    array
    (
        'classes' => array
        (
            'midcom_baseclasses_database_article',
            'fi_opengov_datacatalog_dataset_dba',
        ),
        'operations' => MIDCOM_OPERATION_DBA_UPDATE,
    ),
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
        'ferenc' => array
        (
            'name' => 'Ferenc Szekely',
            'email' => 'ferenc.szekely@gmail.com',
            'role' => 'lead',
            'active' => 'yes',
        ),
    ),
    'dependencies' => array
    (
        'midcom.helper.datamanager2' => array(),
        'net.nehmer.blog' => array(),
        'net.nehmer.comments' => array(),
        'net.nemein.tag' => array(),
        'org.openpsa.httplib' => array(),
        'midcom' => array(),
    ),
),
