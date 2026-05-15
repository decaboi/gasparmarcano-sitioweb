<?php
// admin/config/social_media_config.php
// Configuración de APIs de redes sociales
// OBTENER TOKENS DESDE:
// - Facebook Graph API: https://developers.facebook.com/
// - Instagram Basic Display: https://developers.facebook.com/docs/instagram-basic-display-api/

return [
    'facebook' => [
        'app_id' => getenv('FB_APP_ID') ?: 'TU_APP_ID',
        'app_secret' => getenv('FB_APP_SECRET') ?: 'TU_APP_SECRET',
        'access_token' => getenv('FB_ACCESS_TOKEN') ?: 'TU_ACCESS_TOKEN',
        'page_id' => getenv('FB_PAGE_ID') ?: 'TU_PAGE_ID'
    ],
    'instagram' => [
        'business_id' => getenv('IG_BUSINESS_ID') ?: 'TU_BUSINESS_ID',
        'access_token' => getenv('IG_ACCESS_TOKEN') ?: 'TU_ACCESS_TOKEN'
    ],
    'modo_prueba' => true  // En true, no publica realmente, solo guarda en BD
];
?>