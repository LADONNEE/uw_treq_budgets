<?php
/**
 * Configuration for School
 * Provides custom values from env file
 */

return [

    'scl_short_lowercase'=> env('SCL_SHORT_LOWCASE', 'school'),
    'scl_short_camelcase'=> env('SCL_SHORT_CAMELCASE', 'SchoolName'),
    'scl_long'=> env('SCL_LONG', 'Name of the School'),
    'scl_city_state'=> env('SCL_CITY_STATE', 'City, State'),
    
    'scl_url_saml'=> env('SCL_URL_SAML', ''),
    'scl_url_prod'=> env('SCL_URL_PROD', 'example.com'),
    'scl_url_portal'=> env('SCL_URL_PORTAL', 'school.example.com'),
    'scl_url_help'=> env('SCL_URL_HELP', 'school.example.com'),
    'scl_url_privacy'=> env('SCL_URL_PRIVACY', 'school.example.com/privacy'),
    'scl_url_terms'=> env('SCL_URL_TERMS', 'school.example.com/terms'),
    'scl_url_gov'=> env('SCL_URL_GOV', 'school.example.com/city'),
    'scl_url_doc_fdm'=> env('SCL_URL_DOC_FDM', 'school.example.com/fdm'),
    
    'scl_email_general'=> env('SCL_EMAIL_GENERAL', 'mail@example.com'),
    'scl_email_treq'=> env('SCL_EMAIL_TREQ', 'mail@example.com'),
    'scl_email_helpdesk'=> env('SCL_EMAIL_HELPDESK', 'help@example.com'),
    'scl_email_domain'=> env('SCL_EMAIL_DOMAIN', 'example.com'),
    
];
