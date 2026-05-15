<?php
// src/Core/SEOGenerator.php
// Generador automático de meta tags, sitemap y schema.org

class SEOGenerator {
    
    private $site_name = "Municipio Gaspar Marcano - Juan Griego";
    private $site_url = "https://municipiogaspar.gob.ve";
    private $default_image = "/assets/img/og-image.jpg";
    
    public function generarMetaTags($titulo, $descripcion, $tipo = 'website', $imagen = null) {
        $titulo_completo = $titulo . ' | ' . $this->site_name;
        $descripcion_final = $descripcion ?: "Portal oficial del Municipio Gaspar Marcano, Juan Griego. Turismo, inversiones, noticias y gestión institucional en la Isla de Margarita.";
        $imagen_final = $imagen ?: $this->default_image;
        
        $metas = [
            // Metas básicas
            '<title>' . htmlspecialchars($titulo_completo) . '</title>',
            '<meta name="description" content="' . htmlspecialchars($descripcion_final) . '">',
            '<meta name="keywords" content="Juan Griego, Gaspar Marcano, Nueva Esparta, Margarita, turismo, inversiones, alcaldía, Yul Armas, 7 Transformaciones">',
            '<meta name="author" content="Alcaldía del Municipio Gaspar Marcano">',
            '<meta name="robots" content="index, follow">',
            
            // Open Graph (Facebook, LinkedIn)
            '<meta property="og:title" content="' . htmlspecialchars($titulo_completo) . '">',
            '<meta property="og:description" content="' . htmlspecialchars($descripcion_final) . '">',
            '<meta property="og:image" content="' . $this->site_url . $imagen_final . '">',
            '<meta property="og:url" content="' . $this->site_url . '">',
            '<meta property="og:type" content="' . $tipo . '">',
            '<meta property="og:site_name" content="' . $this->site_name . '">',
            
            // Twitter Cards
            '<meta name="twitter:card" content="summary_large_image">',
            '<meta name="twitter:title" content="' . htmlspecialchars($titulo_completo) . '">',
            '<meta name="twitter:description" content="' . htmlspecialchars($descripcion_final) . '">',
            '<meta name="twitter:image" content="' . $this->site_url . $imagen_final . '">',
            
            // Geo (para turismo)
            '<meta name="geo.region" content="VE-S">',
            '<meta name="geo.placename" content="Juan Griego, Nueva Esparta">',
            '<meta name="geo.position" content="10.9667;-63.9667">',
            '<meta name="ICBM" content="10.9667, -63.9667">'
        ];
        
        return implode("\n    ", $metas);
    }
    
    public function generarSitemap($conn) {
        $urls = [];
        
        // Páginas estáticas
        $paginas = ['', 'noticias', 'turismo', 'eventos', 'inversionistas', 'documentos', 'historia', 'contacto'];
        foreach ($paginas as $pagina) {
            $urls[] = [
                'loc' => $this->site_url . '/' . $pagina,
                'priority' => $pagina === '' ? '1.0' : '0.8',
                'changefreq' => 'weekly'
            ];
        }
        
        // Contenido dinámico (noticias, turismo, eventos)
        $tablas = ['contenido', 'inversiones', 'documentos'];
        foreach ($tablas as $tabla) {
            $result = $conn->query("SELECT slug, created_at FROM $tabla WHERE status = 1");
            while ($row = $result->fetch_assoc()) {
                $urls[] = [
                    'loc' => $this->site_url . '/' . $tabla . '.php?slug=' . $row['slug'],
                    'lastmod' => $row['created_at'],
                    'priority' => '0.6',
                    'changefreq' => 'monthly'
                ];
            }
        }
        
        // Generar XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= '    <url>' . "\n";
            $xml .= '        <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            if (isset($url['lastmod'])) {
                $xml .= '        <lastmod>' . date('Y-m-d', strtotime($url['lastmod'])) . '</lastmod>' . "\n";
            }
            $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '        <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '    </url>' . "\n";
        }
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    public function generarRobotsTxt() {
        return "User-agent: *\n" .
               "Allow: /\n" .
               "Disallow: /admin/\n" .
               "Disallow: /cron/\n" .
               "Sitemap: " . $this->site_url . "/sitemap.xml\n";
    }
}
?>