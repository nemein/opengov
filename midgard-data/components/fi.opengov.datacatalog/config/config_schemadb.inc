/**
 * This schema is used to show the settings form on
 * for the folder of your component
 */
'config' => Array 
(
    'description' => 'Default Configuration Schema', /* This is a topic */
    'fields'      => Array
    ( 
        'slogan_fin' => array
        (
            'title' => 'slogan_fin',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'slogan_fin',
            ),
            'type' => 'text',
            'widget' => 'text',
        ),         
        'slogan_eng' => array
        (
            'title' => 'slogan_eng',
            'storage' => array
            (
                'location' => 'configuration',
                'domain' => 'fi.opengov.datacatalog',
                'name' => 'slogan_eng',
            ),
            'type' => 'text',
            'widget' => 'text',
        ),         
    )
),
