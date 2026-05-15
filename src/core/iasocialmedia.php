<?php
// src/Core/IASocialMedia.php
// Agente de IA para Community Management - Municipio Gaspar Marcano

class IASocialMedia {
    
    private $mejores_horarios = [
        'facebook' => ['10:00', '15:00', '20:00'],
        'instagram' => ['09:00', '12:00', '18:00', '21:00'],
        'twitter' => ['08:00', '12:00', '17:00']
    ];
    
    public function generarContenidoRedes($titulo, $resumen, $url, $tipo = 'noticia') {
        // Generar copy para Facebook
        $fb_copy = $this->generarFacebookCopy($titulo, $resumen, $url);
        
        // Generar copy para Instagram
        $ig_copy = $this->generarInstagramCopy($titulo, $resumen, $url);
        
        // Generar hashtags virales
        $hashtags = $this->generarHashtagsVirales($titulo, $tipo);
        
        // Recomendar mejor horario
        $horario = $this->recomendarHorario();
        
        return [
            'facebook' => [
                'mensaje' => $fb_copy,
                'hashtags' => $hashtags,
                'horario_recomendado' => $horario['facebook']
            ],
            'instagram' => [
                'mensaje' => $ig_copy,
                'hashtags' => $hashtags,
                'horario_recomendado' => $horario['instagram']
            ],
            'recomendacion_general' => $this->recomendacionEstrategica($tipo)
        ];
    }
    
    private function generarFacebookCopy($titulo, $resumen, $url) {
        $emoji = ['📢', '🔔', '🇻🇪', '🏛️', '🎉'][array_rand(['📢', '🔔', '🇻🇪', '🏛️', '🎉'])];
        $copy = $emoji . " " . $titulo . "\n\n";
        $copy .= substr($resumen, 0, 200);
        if (strlen($resumen) > 200) $copy .= "...";
        $copy .= "\n\n🔗 Leer más: " . $url;
        return $copy;
    }
    
    private function generarInstagramCopy($titulo, $resumen, $url) {
        $emoji = ['✨', '🌟', '🔥', '💫', '⭐'][array_rand(['✨', '🌟', '🔥', '💫', '⭐'])];
        $copy = $emoji . " " . $titulo . "\n\n";
        $copy .= substr($resumen, 0, 150);
        if (strlen($resumen) > 150) $copy .= "...";
        $copy .= "\n\n🔗 Link en la bio / " . $url;
        return $copy;
    }
    
    private function generarHashtagsVirales($titulo, $tipo) {
        $base_tags = ['#JuanGriego', '#GasparMarcano', '#NuevaEsparta', '#7Transformaciones', '#Venezuela', '#PlanDeLaPatria'];
        
        $tipo_tags = [
            'noticia' => ['#NoticiasMunicipales', '#GestionYulArmas', '#AlcaldiaActiva'],
            'turismo' => ['#TurismoVenezuela', '#PlayasDeMargarita', '#JuanGriegoTeEspera'],
            'evento' => ['#EventosGasparMarcano', '#CulturaMargariteña', '#ViveJuanGriego'],
            'inversion' => ['#InversionSegura', '#DesarrolloEconómico', '#VenezuelaInvierte']
        ];
        
        $tags = array_merge($base_tags, $tipo_tags[$tipo] ?? $tipo_tags['noticia']);
        
        // Extraer palabras clave del título
        $palabras = explode(' ', strtolower($titulo));
        foreach ($palabras as $p) {
            if (strlen($p) > 5 && !in_array('#' . $p, $tags)) {
                $tags[] = '#' . ucfirst($p);
            }
        }
        
        return array_slice(array_unique($tags), 0, 10);
    }
    
    private function recomendarHorario() {
        $hora_actual = date('H');
        
        return [
            'facebook' => $this->mejores_horarios['facebook'][array_rand($this->mejores_horarios['facebook'])],
            'instagram' => $this->mejores_horarios['instagram'][array_rand($this->mejores_horarios['instagram'])],
            'explicacion' => $this->explicarHorario($hora_actual)
        ];
    }
    
    private function explicarHorario($hora_actual) {
        if ($hora_actual < 12) {
            return "La audiencia en Margarita suele estar activa después del desayuno (9-11 AM).";
        } elseif ($hora_actual < 18) {
            return "El horario de la tarde (3-5 PM) es ideal para llegar a quienes están en el trabajo o estudiando.";
        } else {
            return "Las noches (7-9 PM) tienen el mayor engagement en Instagram y Facebook.";
        }
    }
    
    private function recomendacionEstrategica($tipo) {
        $recomendaciones = [
            'noticia' => "🚀 Para noticias, publique en Facebook a las 10:00 AM y en Instagram a las 6:00 PM. Use historias para destacar los puntos clave.",
            'turismo' => "🏝️ Para contenido turístico, use Instagram Reels con música caribeña. Publique los jueves a las 7:00 PM.",
            'evento' => "🎉 Para eventos, cree un 'recordatorio' en Facebook. Publique 7 y 3 días antes, y el día del evento a las 9:00 AM.",
            'inversion' => "💰 Para oportunidades de inversión, use LinkedIn y Facebook Business. Publique los martes a las 11:00 AM."
        ];
        return $recomendaciones[$tipo] ?? $recomendaciones['noticia'];
    }
}
?>