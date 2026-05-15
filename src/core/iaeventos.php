<?php
// src/Core/IAEventos.php
// Agente de IA para gestión de eventos y festividades

class IAEventos {
    
    private $eventos_clave = [
        'San Nicolás' => ['fija' => '12-06', 'importancia' => 'Alta', 'tipo' => 'Religioso'],
        'Semana Santa' => ['variable' => true, 'importancia' => 'Alta', 'tipo' => 'Religioso'],
        'Ferias' => ['importancia' => 'Media', 'tipo' => 'Cultural'],
        'Aniversario' => ['importancia' => 'Alta', 'tipo' => 'Cívico']
    ];
    
    public function optimizarEvento($titulo, $fecha, $lugar) {
        $alerta = $this->generarAlerta($fecha);
        $hashtags = $this->generarHashtags($titulo, $lugar);
        $tiempo_restante = $this->calcularTiempoRestante($fecha);
        $keywords = implode(', ', $hashtags);
        
        $recomendacion = $this->recomendarDifusion($fecha, $titulo);
        
        return [
            'alerta' => $alerta,
            'hashtags' => implode(' ', $hashtags),
            'tiempo_restante' => $tiempo_restante,
            'keywords' => $keywords,
            'recomendacion' => $recomendacion
        ];
    }
    
    private function generarAlerta($fecha) {
        if (!$fecha) return "Complete la fecha del evento";
        
        $hoy = new DateTime();
        $evento = new DateTime($fecha);
        $diferencia = $hoy->diff($evento)->days;
        
        if ($fecha == $hoy->format('Y-m-d')) return "⚠️ ¡ESTE EVENTO ES HOY! Active difusión urgente.";
        if ($diferencia <= 3) return "🔔 Evento en menos de $diferencia días. ¡Active campaña de recordatorio!";
        if ($diferencia <= 7) return "📢 Evento en $diferencia días. Momento ideal para segunda difusión.";
        if ($diferencia <= 30) return "✅ Evento en $diferencia días. Programe publicación en redes sociales.";
        return "📅 Evento planificado con anticipación. Puede programar recordatorios mensuales.";
    }
    
    private function generarHashtags($titulo, $lugar) {
        $base = ['#JuanGriego', '#GasparMarcano', '#EventosNuevaEsparta', '#7Transformaciones'];
        $hashtags = $base;
        
        // Extraer palabras clave del título
        $palabras = explode(' ', strtolower($titulo));
        foreach ($palabras as $p) {
            if (strlen($p) > 4) $hashtags[] = '#' . ucfirst($p);
        }
        
        if ($lugar) {
            $lugar_limpio = str_replace(' ', '', ucwords($lugar));
            $hashtags[] = '#' . $lugar_limpio;
        }
        
        return array_slice(array_unique($hashtags), 0, 8);
    }
    
    private function calcularTiempoRestante($fecha) {
        if (!$fecha) return "Fecha no definida";
        
        $hoy = new DateTime();
        $evento = new DateTime($fecha);
        
        if ($evento < $hoy) return "⏪ Evento ya finalizado";
        
        $diff = $hoy->diff($evento);
        if ($diff->days == 0) return "🔥 ¡HOY MISMO! 🔥";
        if ($diff->days == 1) return "⏰ MAÑANA - ¡Último momento para promocionar!";
        
        return "📅 Faltan {$diff->days} días para el evento";
    }
    
    private function recomendarDifusion($fecha, $titulo) {
        if (!$fecha) return "Complete la fecha para recibir recomendaciones";
        
        $hoy = new DateTime();
        $evento = new DateTime($fecha);
        $dias = $hoy->diff($evento)->days;
        
        if ($dias <= 1) return "PUBLICACIÓN INMEDIATA. Use historias de Instagram y Facebook con cuenta regresiva.";
        if ($dias <= 7) return "PUBLICAR AHORA. Ideal para captar atención temprana. Añada el evento a Google Calendar.";
        if ($dias <= 30) return "PROGRAMAR PUBLICACIÓN. Use teasers semanales para generar expectativa.";
        
        return "CREAR BORRADOR. Es muy pronto para publicar. Guarde como borrador y revise en 2 semanas.";
    }
}
?>