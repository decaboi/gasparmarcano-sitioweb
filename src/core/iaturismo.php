<?php
// src/Core/IATurismo.php
// Agente de IA para promoción turística - Alcaldía Gaspar Marcano

class IATurismo {
    
    private $temporadas = [
        'playa' => ['temporada' => 'Diciembre - Abril', 'clima' => 'Soleado, 28-32°C'],
        'cultura' => ['temporada' => 'Todo el año', 'clima' => 'Templado, 25-30°C'],
        'montaña' => ['temporada' => 'Noviembre - Febrero', 'clima' => 'Fresco, 20-25°C'],
        'gastronomia' => ['temporada' => 'Septiembre - Octubre (Festivales)', 'clima' => 'Ideal para probar platos típicos']
    ];
    
    private $datos_curiosos = [
        'Juan Griego fue fundado por un corsario griego en 1730',
        'La Iglesia San Nicolás de Bari es del siglo XVIII',
        'La bahía es ideal para ver el atardecer más hermoso de Margarita',
        'Cada 6 de diciembre se celebra la Fiesta de San Nicolás'
    ];
    
    public function optimizarAtractivo($titulo, $resumen, $cuerpo) {
        $tipo = $this->detectarTipo($titulo . ' ' . $resumen);
        $temporada = $this->temporadas[$tipo]['temporada'] ?? $this->temporadas['playa']['temporada'];
        $etiquetas = $this->generarEtiquetas($titulo, $tipo);
        $dato_curioso = $this->datos_curiosos[array_rand($this->datos_curiosos)];
        $keywords = implode(', ', $etiquetas);
        $descripcion = substr($resumen, 0, 160) ?: "Descubre $titulo en Juan Griego, destino turístico de Nueva Esparta.";
        
        $recomendacion = "Mejor época para visitar: $temporada. $dato_curioso";
        
        return [
            'temporada_ideal' => $temporada,
            'etiquetas_viaje' => implode(' ', $etiquetas),
            'dato_curioso' => $dato_curioso,
            'keywords' => $keywords,
            'descripcion' => $descripcion,
            'recomendacion' => $recomendacion
        ];
    }
    
    private function detectarTipo($texto) {
        $texto = strtolower($texto);
        if (strpos($texto, 'playa') !== false || strpos($texto, 'mar') !== false || strpos($texto, 'bahia') !== false) return 'playa';
        if (strpos($texto, 'iglesia') !== false || strpos($texto, 'historia') !== false || strpos($texto, 'museo') !== false) return 'cultura';
        if (strpos($texto, 'cerro') !== false || strpos($texto, 'montaña') !== false || strpos($texto, 'senderismo') !== false) return 'montaña';
        if (strpos($texto, 'comida') !== false || strpos($texto, 'restaurante') !== false || strpos($texto, 'gastronomia') !== false) return 'gastronomia';
        return 'playa';
    }
    
    private function generarEtiquetas($titulo, $tipo) {
        $base = ['#JuanGriego', '#GasparMarcano', '#NuevaEsparta', '#VenezuelaTuristica', '#7Transformaciones'];
        $tipo_map = [
            'playa' => ['#PlayasDeVenezuela', '#SolYPlaya', '#CaribeVenezolano'],
            'cultura' => ['#HistoriaViva', '#PatrimonioCultural', '#JuanGriegoHistorico'],
            'montaña' => ['#EcoTurismo', '#CerroElCopey', '#Aventura'],
            'gastronomia' => ['#SaborMargariteño', '#GastronomiaLocal', '#PescadoFresco']
        ];
        $etiquetas = array_merge($base, $tipo_map[$tipo]);
        $palabras = explode(' ', strtolower($titulo));
        foreach ($palabras as $p) {
            if (strlen($p) > 4) $etiquetas[] = '#' . ucfirst($p);
        }
        return array_slice(array_unique($etiquetas), 0, 8);
    }
}
?>