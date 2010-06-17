'default' => array
(
    'description' => 'blog entry',
    'extends' => array
    (
        'path' => 'file:/net/nehmer/blog/config/schemadb_default.inc',
        'name' => 'default',
    ),    
    'fields' => array
    (
        'dataset' => array
        (
            'title' => 'related_dataset',
            'storage' => array(
                'location' => 'parameter',
                'domain' => 'net.nehmer.blog',
                'name' => 'dataset',                
            ),
            'required' => false,
            'type' => 'select',
            'type_config' => array
            (
                'options' => array(),
                'require_corresponding_option' => false,
                'allow_other' => false,
                'allow_multiple' => false,
            ),
            'widget' => 'chooser',
            'widget_config' => array
            (
                'class' => 'fi_opengov_datacatalog_dataset_dba',
                'component' => 'fi.opengov.datacatalog',
                'min_chars' => 2,
                'id_field' => 'guid',
                'searchfields' => array
                (
                    'title',
                ),
                'result_headers' => array
                (
                    array
                    (
                        'name' => 'title',
                        'title' => $_MIDCOM->i18n->get_string('dataset', 'fi_opengov_datacatalog'),
                    ),
                ),
                'constraints' => array (),
                'default_search' => '**',
                'creation_mode_enabled' => false,
            ),
        ),
    ),
),
