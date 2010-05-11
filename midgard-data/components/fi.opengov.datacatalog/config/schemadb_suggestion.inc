'default' => array
(
    'description' => 'default suggestion',
    'fields' => array
    (
        'title' => array
        (
            'title' => 'title',
            'storage' => 'title',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => true,
        ),
        'organization' => array
        (
            'title' => 'organization',
            'storage' => null,
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'description' => array
        (
            'title' => 'description',
            'storage' => 'description',
            'type'    => 'text',
            'widget' => 'tinymce',
            'required' => true,
        ),
        'url' => array
        (
            'title' => 'url',
            'storage' => 'url',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => false,
        ),
        'tags' => Array
        (
            'title' => 'tags',
            'storage' => null,
            'type' => 'tags',
            'widget' => 'text',
            'hidden' => false,
        ),
        'name' => array
        (
            'title' => 'name',
            'storage' => 'name',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => true,
        ),
        'email' => array
        (
            'title' => 'email'
            'storage' => 'email',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => false,
        ),
    ),
),
