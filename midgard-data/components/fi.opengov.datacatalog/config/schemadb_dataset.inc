'default' => array
(
    'description' => 'default dataset',
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
        'description' => array
        (
            'title' => 'description',
            'storage' => 'description',
            'type'    => 'text',
            'widget' => 'text',
            'required' => true,
        ),
        'url' => array
        (
            'title' => 'url',
            'storage' => 'url',
            'type'    => 'text',
            'widget'  => 'text',
            'required' => true,
        ),
        'tags' => Array
        (
            'title' => 'tags',
            'type' => 'tags',
            'widget' => 'text',
            'hidden' => false,
        ),
        'organization' => array
        (
            'title' => 'organization',
            'storage' => 'organization',
            'required' => true,
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
                'class' => 'fi_opengov_datacatalog_info_dba',
                'component' => 'fi.opengov.datacatalog',
                'min_chars' => 2,
                'id_field' => 'id',
                'searchfields' => array
                (
                    'title',
                ),
                'result_headers' => array
                (
                    array
                    (
                        'name' => 'title',
                        'title' => $_MIDCOM->i18n->get_string('organization', 'fi_opengov_datacatalog'),
                    ),
                ),
                'constraints' => array
                (
                    array
                    (
                        'field' => 'type',
                        'op' => '=',
                        'value' => 'organization',
                    ),
                ),
                'default_search' => '**',
                'creation_mode_enabled' => true,
                'creation_handler' => "{$_MIDGARD['self']}data/organization/create/chooser/",
                'creation_default_key' => 'title',   
            ),
        ),
        'license' => array
        (
            'title' => 'license',
            'storage' => 'license',
            'required' => true,
            'type' => 'select',
            'type_config' => array
            (
                'allow_multiple' => false,
                'allow_other' => false,
                'require_corresponding_option' => false,
                'options' => array(),
            ),
            'widget' => 'chooser',
            'widget_config' => array
            (
                'class' => 'fi_opengov_datacatalog_info_dba',
                'component' => 'fi.opengov.datacatalog',
                'min_chars' => 2,
                'id_field' => 'id',
                'result_headers' => array
                (
                    array
                    (
                        'name' => 'title',
                        'title' => $_MIDCOM->i18n->get_string('license', 'fi_opengov_datacatalog'),
                    ),
                ),
                'searchfields' => array
                (
                    'title',
                ),
                'constraints' => array
                (
                    array
                    (
                        'field' => 'type',
                        'op' => '=',
                        'value' => 'license',
                    ),
                ),
                'default_search' => '**',
                'creation_mode_enabled' => true,
                'creation_handler' => "{$_MIDGARD['self']}data/license/create/chooser/",
                'creation_default_key' => 'title',   
            ),
        ),
        'format' => array
        (
            'title' => 'format',
            'storage' => null,
            'required' => true,
            'type' => 'mnrelation',
            'type_config' => array
            (
                'mapping_class_name' => 'fi_opengov_datacatalog_dataset_info_dba',
                'master_fieldname' => 'dataset',
                'member_fieldname' => 'info',
                'master_is_id' => true,
                'options' => array(),
                'require_corresponding_option' => false,
                'allow_other' => false,
                'allow_multiple' => true,
            ),
            'widget' => 'chooser',
            'widget_config' => array
            (
                'class' => 'fi_opengov_datacatalog_info_dba',
                'component' => 'fi.opengov.datacatalog',
                'min_chars' => 2,
                'id_field' => 'id',
                'searchfields' => array
                (
                    'title',
                ),
                'result_headers' => array
                (
                    array
                    (
                        'name' => 'title',
                        'title' => $_MIDCOM->i18n->get_string('format', 'fi_opengov_datacatalog'),
                    ),
                ),
                'constraints' => array
                (
                    array
                    (
                        'field' => 'type',
                        'op' => '=',
                        'value' => 'format',
                    ),
                ),
                'default_search' => '**',
                'creation_mode_enabled' => true,
                'creation_handler' => "{$_MIDGARD['self']}data/format/create/chooser/",
                'creation_default_key' => 'title',               
            ),
        ),
    ),
),
