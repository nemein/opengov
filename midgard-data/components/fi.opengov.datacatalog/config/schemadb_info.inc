'info' => array
(
    'description' => 'default info schema',
    'fields' => array
    (
        'title' => array
        (
            'title' => 'title',
            'storage' => 'title',
            'type' => 'string',
            'widget' => 'text',
            'required' => true,
        ),
        'url' => array
        (
            'title' => 'url',
            'storage' => 'url',
            'type' => 'urlname',
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
        'type' => array
        (
            'title' => 'type',
            'storage' => 'parameter',
            'type' => 'select',
            'type_config' => array
            (
                'allow_multiple' => false,
                'options' => array
                (
                    'open' => 'open',
                    'close' => 'close',
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
        'information' => array
        (
            'title' => 'information',
            'storage' => 'parameter',
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'address' => array
        (
            'title' => 'address',
            'storage' => 'parameter',
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'contact' => array
        (
            'title' => 'contact',
            'storage' => 'parameter',
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
    ),
),
