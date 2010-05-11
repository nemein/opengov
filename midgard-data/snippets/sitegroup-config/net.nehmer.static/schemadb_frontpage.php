'default' => array
(
    'description' => 'article',
    'fields'      => array
    (
        'name' => Array
        (
            // COMPONENT-REQUIRED
            'title' => 'url name',
            'storage' => 'name',
            'type' => 'urlname',
            'widget' => 'text',
            'write_privilege' => array
            (
                'privilege' => 'midcom:urlname',
            ),
        ),
        'title' => Array
        (
            // COMPONENT-REQUIRED
            'title' => 'title',
            'storage' => 'title',
            'required' => true,
            'type' => 'text',
            'widget' => 'text',
        ),
        'content' => Array
        (
            // COMPONENT-REQUIRED
            'title' => 'content',
            'storage' => 'content',
            'type' => 'text',
            'type_config' => Array
            (
                'output_mode' => 'html',
            ),
            'widget' => 'tinymce',
        ),
        'slogan' => Array
        (
            'title' => 'slogan',
            'storage'    => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name'    => 'slogan',
            ),
            'required' => false,
            'type' => 'text',
            'widget' => 'text',
        ),
    ),
), // default
