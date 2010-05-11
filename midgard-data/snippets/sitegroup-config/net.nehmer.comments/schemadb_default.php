'comment' => array
(
    'description' => 'default schema',
    'operations' => array
    (
        'save' => 'post'
    ),
    'fields' => array
    (
        'author' => array
        (
            'title' => 'author',
            'description' => '',
            'helptext' => '',

            'storage' => 'author',
            'required' => true,
            'type' => 'text',
            'widget' => 'text',
        ),
        'content' => array
        (
            'title' => 'content',
            'description' => '',
            'helptext' => '',

            'required' => 'true',
            'storage' => 'content',
            'type' => 'text',
            'type_config' => array ( 'output_mode' => 'markdown' ),
            'widget' => 'textarea',
        ),
        'comment_url' => array
        (
            'title' => 'url',
            'description' => '',
            'helptext' => '',
            'storage'    => array
            (
                'location' => 'parameter',
                'domain' => 'fi.opengov.datacatalog',
                'name'    => 'comment_url',
            ),
            'required' => false,
            'type' => 'text',
            'widget' => 'text',
        ),
        'rating' => array
        (
            'title' => 'rating',
            'storage' => 'rating',
            'type' => 'select',
            'type_config' => array
            (
                'options' => array
                (
                    0 => '',
                    1 => '*',
                    2 => '**',
                    3 => '***',
                    4 => '****',
                    5 => '*****',
                ),
            ),
            'widget' => 'select',
            'hidden' => true,
        ),
        'subscribe' => array
        (
            'title'      => 'subscribe',
            'storage'   => null,
            'type'      => 'boolean',
            'widget'   => 'checkbox',
            'hidden' => !$_MIDCOM->auth->user,
        ),
    ),
),
