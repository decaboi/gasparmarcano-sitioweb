<?php
// src/Core/IAInversiones.php
// Agente de IA para análisis de oportunidades de inversión
// Alineado con Ley de Promoción de Inversiones y Plan 7T

class IAInversiones {
    
    private $sectores_info = [
        'Turismo' => [
            'tendencia' => 'Alta (post-pandemia)',
            'incentivos' => 'ZDTE, exoneración de ISLR por 5 años',
            'riesgo' => 'Medio-Bajo',
            'palabras_clave' => ['hotel', 'posada', 'tour', 'playa', 'gastronomia']
        ],
        'Pesca' => [
            'tendencia' => 'Media-Alta (mercado asiático)',
            'incentivos' => 'Exoneración de aranceles para exportación',
            'riesgo' => 'Medio (climático)',
            'palabras_clave' => ['pescado', 'marisco', 'acuicultura', 'langosta']
        ],
        'Infraestructura' => [
            'tendencia' => 'Alta (Plan 7T)',
            'incentivos' => 'Alianzas público-privadas (APP)',
            'riesgo' => 'Bajo',
            'palabras_clave' => ['construccion', 'carretera', 'puerto', 'electricidad']
        ],
        'Agroindustria' => [
            'tendencia' => 'Media (sustitución de importaciones)',
            'incentivos' => 'Financiamiento agrícola, exoneración de IVA',
            'riesgo' => 'Medio',
            'palabras_clave' => ['cacao', 'coco', 'agricultura', 'procesamiento']
        ],
        'Tecnología' => [
            'tendencia' => 'Alta (transformación digital)',
            'incentivos' => 'Ley de Ciencia y Tecnología, Zona Económica Especial',
            'riesgo' => 'Bajo',
            'palabras_clave' => ['software', 'startup', 'digital', 'IA', 'blockchain']
        ]
    ];
    
    public function analizarOportunidad($titulo, $sector, $descripcion) {
        $info = $this->sectores_info[$sector] ?? $this->sectores_info['Turismo'];
        
        // Detectar subsector automáticamente
        $subsector = $this->detectarSubsector($titulo . ' ' . $descripcion, $info['palabras_clave']);
        
        // Calcular potencial de éxito
        $potencial = $this->calcularPotencial($sector, $titulo);
        
        // Generar análisis de mercado
 $analisis_mercado = $this->generarAnalisisMercado($sector, $subsector);
        
        // Recomendaciones para inversionistas
        $recomendaciones = $this->generarRecomendaciones($sector, $potencial);
        
        return [
            'subsector' => $subsector,
            'tendencia' => $info['tendencia'],
            'incentivos' => $info['incentivos'],
            'riesgo' => $info['riesgo'],
            'potencial' => $potencial,
            'analisis_mercado' => $analisis_mercado,
            'recomendaciones' => $recomendaciones,
            'hashtags' => $this->generarHashtags($sector, $subsector)
        ];
    }
    
    private function detectarSubsector($texto, $palabras_clave) {
        $texto = strtolower($texto);
        foreach ($palabras_clave as $kw) {
            if (strpos($texto, $kw) !== false) {
                return ucfirst($kw);
            }
        }
        return 'General';
    }
    
    private function calcularPotencial($sector, $titulo) {
        $base = [
            'Turismo' => 85,
            'Infraestructura' => 90,
            'Tecnología' => 80,
            'Pesca' => 70,
            'Agroindustria' => 75
        ];
        
        $potencial = $base[$sector] ?? 70;
        
        // Ajustar por palabras clave en el título
        if (stripos($titulo, 'urgente') !== false) $potencial += 10;
        if (stripos($titulo, 'zona franca') !== false) $potencial += 15;
        if (stripos($titulo, 'exclusivo') !== false) $potencial += 5;
        
        return min($potencial, 100);
    }
    
    private function generarAnalisisMercado($sector, $subsector) {
        $analisis = [
            'Turismo' => "El sector turístico en Juan Griego tiene un crecimiento proyectado del 15% anual. La Zona de Desarrollo Turístico Especial (ZDTE) ofrece ventajas competitivas únicas en el Caribe.",
            'Pesca' => "La bahía de Juan Griego es uno de los caladeros más ricos del oriente venezolano. La demanda de productos del mar en mercados internacionales está en aumento.",
            'Infraestructura' => "El Plan 7T prioriza la inversión en infraestructura. Hay oportunidades en construcción de viviendas, mejoras viales y puertos deportivos.",
            'Agroindustria' => "El cacao margariteño y el coco son productos con alta demanda internacional. Existen nichos para procesamiento y exportación.",
            'Tecnología' => "La transformación digital del municipio abre oportunidades en software para turismo, gestión pública y comercio electrónico."
        ];
        
        $texto = $analisis[$sector] ?? "Sector en crecimiento con alto potencial de desarrollo en el municipio.";
        if ($subsector != 'General') {
            $texto .= " Específicamente en $subsector, hay nichos de mercado desatendidos.";
        }
        return $texto;
    }
    
    private function generarRecomendaciones($sector, $potencial) {
        if ($potencial >= 85) {
            return "🚀 Oportunidad de ALTO POTENCIAL. Se recomienda agilizar los trámites municipales y presentar el proyecto al Consejo de Inversiones Local.";
        } elseif ($potencial >= 70) {
            return "📈 Oportunidad de potencial MEDIO-ALTO. Realizar un estudio de mercado detallado y contactar a la Cámara de Comercio local.";
        } else {
            return "📊 Oportunidad en desarrollo. Se recomienda asociarse con inversionistas locales para mitigar riesgos iniciales.";
        }
    }
    
    private function generarHashtags($sector, $subsector) {
        $base = ['#InversionGasparMarcano', '#7Transformaciones', '#VenezuelaInvierte', '#PlanDeLaPatria'];
        $sector_tags = [
            'Turismo' => ['#InversionTuristica', '#ZDTE', '#CaribeInvierte'],
            'Pesca' => ['#PescaSostenible', '#ExportacionPesquera', '#MarVenezolano'],
            'Infraestructura' => ['#ObrasPublicas', '#APP', '#Construccion7T'],
            'Agroindustria' => ['#AgroVenezuela', '#CacaoMargariteño', '#DesarrolloRural'],
            'Tecnología' => ['#TechVenezuela', '#Innovacion7T', '#StartupMargarita']
        ];
        
        $tags = array_merge($base, $sector_tags[$sector] ?? ['#OportunidadDeInversion']);
        if ($subsector != 'General') {
            $tags[] = '#' . str_replace(' ', '', $subsector);
        }
        
        return implode(' ', array_slice(array_unique($tags), 0, 8));
    }
}
?>