'info' => array
(
    'description' => 'default info schema',
    'fields' => array
    (
        'title' => array
        (
            'title' => 'title',
            'storage' => 'title',
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'url' => array
        (
            'title' => 'url',
            'storage' => 'url',
            'type' => 'text',
            'widget' => 'text',
            'required' => false,
        ),
    ),
),
'license' => array
(
    'description' => 'license',
    'extends' => array
    (
        'name' => 'info',
    ),    
    'fields' => array
    (
        'license_type' => array
        (
            'title' => 'type',
            'storage'    => array
            (
                'location' => 'parameter',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'license_type',
            ),
            'type' => 'select',
            'type_config' => array
            (
                'allow_multiple' => false,
                'options' => array
                (
                    'free' => 'free',
                    'non-free' => 'non-free',
                ),
            ),
            'widget' => 'select',
            'default' => 'open',
            'required' => true,
        ),
    ),
),
'organization' => array
(
    'description' => 'organization',
    'extends' => array
    (
        'name' => 'info',
    ),    
    'fields' => array
    (
        'org_information' => array
        (
            'title' => 'information',
            'storage'    => array
            (
                'location' => 'parameter',
                'domain' => 'fi.opengov.datacatalog',
                'name'    => 'org_information',
            ),
            'type' => 'text',
            'type_config' => Array
            (
                'output_mode' => 'html',
            ),
            'widget' => 'tinymce',
            'required' => true,
        ),
        'org_address' => array
        (
            'title' => 'address',
            'storage'    => array
            (
                'location' => 'parameter',
                'domain' => 'fi.opengov.datacatalog',
                'name'    => 'org_address',
            ),
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'org_contact' => array
        (
            'title' => 'contact',
            'storage'    => array
            (
                'location' => 'parameter',
                'domain' => 'fi.opengov.datacatalog',
                'name'    => 'org_contact',
            ),
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
    ),
),
'format' => array
(
    'description' => 'format',
    'extends' => array
    (
        'name' => 'info',
    ),    
),
