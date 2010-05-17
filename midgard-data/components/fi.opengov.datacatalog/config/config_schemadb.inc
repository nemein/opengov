/**
 * This schema is used to show the settings form on
 * for the folder of your component
 */
'config' => Array 
(
    'description' => 'Default Configuration Schema', /* This is a topic */
    'fields'      => Array
    ( 
        'allow_comments' => array
        (
            'title' => 'allow_comments',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'allow_comments',
            ),
            'type' => 'boolean',
            'widget' => 'checkbox',
        ),
        'allow_anonymous' => array
        (
            'title' => 'suggestion_allow_anonymous',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'allow_anonymous',
            ),
            'type' => 'boolean',
            'widget' => 'checkbox',
        ),
        'dataset_topic_id' => array
        (
            'title' => 'dataset_topic_id',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'dataset_topic_id',
            ),
            'type' => 'number',
            'widget' => 'text',
        ),
        'blog_topic_id' => array
        (
            'title' => 'blog_topic_id',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'blog_topic_id',
            ),
            'type' => 'number',
            'widget' => 'text',
        ),
        'comments_topic_id' => array
        (
            'title' => 'comments_topic_id',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'comments_topic_id',
            ),
            'type' => 'text',
            'widget' => 'text',
        ),
        'number_of_comments_on_frontpage' => array
        (
            'title' => 'number_of_comments_on_frontpage',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'number_of_comments_on_frontpage',
            ),
            'type' => 'number',
            'widget' => 'text',
            'default' => 10,
        ), 
        'use_captcha' => array
        (
            'title' => 'use_captcha',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'use_captcha',
            ),
            'type' => 'boolean',
            'widget' => 'checkbox',
        ),
    )
),
