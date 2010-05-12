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
