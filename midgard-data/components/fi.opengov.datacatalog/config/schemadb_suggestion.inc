'default' => array
(
    'description' => 'default suggestion',
    'fields' => array
    (
        'title' => array
        (
            'title' => 'suggestion_title',
            'helptext' => 'yada',
            'storage' => 'title',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => true,
        ),
        'organization' => array
        (
            'title' => 'suggestion_organization',
            'storage' => 'organization',
            'type' => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'description' => array
        (
            'title' => 'suggestion_description',
            'storage' => 'description',
            'type'    => 'text',
            'widget' => 'textarea',
            'required' => true,
        ),
        'url' => array
        (
            'title' => 'suggestion_url',
            'storage' => 'url',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => false,
        ),
        'tags' => Array
        (
            'title' => 'suggestion_tags',
            'type' => 'tags',
            'widget' => 'text',
            'hidden' => false,
        ),
        'name' => array
        (
            'title' => 'submitter_name',
            'storage' => 'name',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => true,
        ),
        'email' => array
        (
            'title' => 'submitter_email',
            'storage' => 'email',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => false,
        ),
    ),
)
