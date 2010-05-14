/**
 * This schema is used to show the settings form on
 * for the folder of your component
 */
'config' => Array 
(
    'description' => 'Default Configuration Schema', /* This is a topic */
    'fields'      => Array
    ( 
        'slogan' => array
        (
            'title' => 'slogan',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'slogan',
            ),
            'type' => 'text',
            'widget' => 'text',
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
        'dataset_comments_topic_guid' => array
        (
            'title' => 'dataset_comments_topic_guid',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'dataset_comments_topic_guid',
            ),
            'type' => 'text',
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
