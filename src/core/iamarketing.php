<?php
// src/Core/IAMarketing.php
// Agente de IA para Marketing Digital - Alcaldía Gaspar Marcano
// Basado en el Código de Ética de IA de Venezuela y Plan de la Patria 7T

class IAMarketing {
    
    // Palabras clave institucionales (Plan 7T + Turismo)
    private $keywords = [
        '#JuanGriego', '#7Transformaciones', '#TurismoVenezuela', 
        '#GasparMarcano', '#NuevaEsparta', '#PlanDeLaPatria',
        '#AlcaldeYulArmas', '#VenezuelaTuristica', '#BahiaDeJuanGriego'
    ];
    
    // Horarios óptimos según análisis de audiencia (simulado)
    private $mejores_horarios = [
        'Lunes' => '9:00 AM',
        'Martes' => '11:00 AM', 
        'Miércoles' => '3:00 PM',
        'Jueves' => '10:00 AM',
        'Viernes' => '7:00 PM',
        'Sábado' => '7:00 PM',
        'Domingo' => '10:00 AM'
    ];
    
    public function optimizarNoticia($titulo, $resumen, $cuerpo) {
        // 1. Generar hashtags relevantes
        $hashtags = $this->generarHashtags($titulo);
        
        // 2. Generar descripción SEO
        $descripcion_seo = $this->generarDescripcionSEO($resumen, $titulo);
        
        // 3. Recomendar horario de publicación
        $horario = $this->recomendarHorario();
        
        // 4. Decidir si publicar ahora o después
        $status_recomendado = $this->decidirStatus($titulo, $cuerpo);
        
        // 5. Generar copy para redes sociales
        $copy_redes = $this->generarCopyRedes($titulo, $resumen);
        
        // 6. Recomendación final
        $recomendacion = $status_recomendado == 1 
            ? "La IA recomienda PUBLICAR AHORA. Alta probabilidad de engagement." 
            : "La IA recomienda GUARDAR COMO BORRADOR. Optimice el título o contenido.";
        
        return [
            'hashtags' => implode(' ', $hashtags),
            'descripcion_seo' => $descripcion_seo,
            'horario' => $horario,
            'status_recomendado' => $status_recomendado,
            'copy_redes' => $copy_redes,
            'recomendacion' => $recomendacion
        ];
    }
    
    private function generarHashtags($titulo) {
        $hashtags = $this->keywords;
        
        // Extraer palabras clave del título
        $palabras = explode(' ', strtolower($titulo));
        $temas_relevantes = ['playa', 'historia', 'iglesia', 'cultura', 'inversion', 'turismo', 'pesca', 'artesania'];
        
        foreach ($palabras as $palabra) {
            if (in_array($palabra, $temas_relevantes)) {
                $hashtags[] = '#' . ucfirst($palabra) . 'JuanGriego';
            }
        }
        
        return array_unique($hashtags);
    }
    
    private function generarDescripcionSEO($resumen, $titulo) {
        if (strlen($resumen) > 120) {
            return substr($resumen, 0, 157) . '...';
        }
        return $titulo . ' | Portal oficial del Municipio Gaspar Marcano, Juan Griego. ' . $resumen;
    }
    
    private function recomendarHorario() {
        $dia = date('l');
        $horario = $this->mejores_horarios[$dia] ?? '10:00 AM';
        return "$dia a las $horario";
    }
    
    private function decidirStatus($titulo, $cuerpo) {
        // Criterios para publicación automática:
        // - Título tiene más de 30 caracteres
        // - Contenido tiene más de 500 caracteres
        // - Contiene alguna palabra clave positiva
        
        $palabras_clave = ['Juan Griego', '7T', 'inversion', 'turismo', 'alcalde', 'plan'];
        $contiene_keyword = false;
        
        foreach ($palabras_clave as $kw) {
            if (stripos($titulo, $kw) !== false || stripos($cuerpo, $kw) !== false) {
                $contiene_keyword = true;
                break;
            }
        }
        
        if (strlen($titulo) > 30 && strlen(strip_tags($cuerpo)) > 500 && $contiene_keyword) {
            return 1; // Publicar ahora
        }
        return 0; // Guardar como borrador
    }
    
    private function generarCopyRedes($titulo, $resumen) {
        $emoji = ['🌊', '🏝️', '⛪', '🎉', '🇻🇪', '🔥'][array_rand(['🌊', '🏝️', '⛪', '🎉', '🇻🇪', '🔥'])];
        $copy = $emoji . " " . $titulo . "\n\n";
        $copy .= substr($resumen, 0, 150) . "...\n\n";
        $copy .= "📍 Juan Griego, Municipio Gaspar Marcano\n";
        $copy .= "#7Transformaciones #PlanDeLaPatria #JuanGriegoTurístico\n";
        $copy .= "🔗 Más información en municipiogaspar.gob.ve";
        return $copy;
    }
}
?>