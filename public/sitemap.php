<?php
// public/sitemap.php
header('Content-Type: application/xml; charset=utf-8');
ob_clean(); // Limpiar cualquier salida previa

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$base_url = 'https://municipiogaspar.gob.ve';

// Páginas estáticas
$paginas = [
    ['loc' => $base_url, 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['loc' => $base_url . '/noticias.php', 'priority' => '0.8', 'changefreq' => 'daily'],
    ['loc' => $base_url . '/turismo.php', 'priority' => '0.8', 'changefreq' => 'weekly'],
    ['loc' => $base_url . '/eventos.php', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['loc' => $base_url . '/inversionistas.php', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['loc' => $base_url . '/documentos.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base_url . '/historia.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['loc' => $base_url . '/contacto.php', 'priority' => '0.5', 'changefreq' => 'monthly'],
    ['loc' => $base_url . '/mapa.php', 'priority' => '0.7', 'changefreq' => 'monthly']
];

foreach ($paginas as $pagina) {
    echo '    <url>' . "\n";
    echo '        <loc>' . htmlspecialchars($pagina['loc']) . '</loc>' . "\n";
    echo '        <changefreq>' . $pagina['changefreq'] . '</changefreq>' . "\n";
    echo '        <priority>' . $pagina['priority'] . '</priority>' . "\n";
    echo '    </url>' . "\n";
}

echo '</urlset>';
?>