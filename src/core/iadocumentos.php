<?php
// src/Core/IADocumentos.php
// Agente de IA para clasificación automática de documentos oficiales

class IADocumentos {
    
    public function clasificarDocumento($titulo, $contenido_texto = '') {
        $texto = strtolower($titulo . ' ' . $contenido_texto);
        
        // Detectar tipo de documento
        $tipo = $this->detectarTipo($texto);
        
        // Extraer número si existe
        $numero = $this->extraerNumero($texto);
        
        // Extraer fecha si existe
        $fecha = $this->extraerFecha($texto);
        
        // Generar resumen automático
        $resumen = $this->generarResumen($titulo, $contenido_texto);
        
        return [
            'tipo_detectado' => $tipo,
            'numero_sugerido' => $numero,
            'fecha_sugerida' => $fecha,
            'resumen_generado' => $resumen,
            'icono' => $this->getIcono($tipo),
            'color' => $this->getColor($tipo)
        ];
    }
    
    private function detectarTipo($texto) {
        if (strpos($texto, 'gaceta') !== false) return 'gaceta';
        if (strpos($texto, 'ordenanza') !== false) return 'ordenanza';
        if (strpos($texto, 'decreto') !== false) return 'decreto';
        if (strpos($texto, 'mapa') !== false || strpos($texto, 'plano') !== false) return 'mapa';
        return 'informativo';
    }
    
    private function extraerNumero($texto) {
        if (preg_match('/(?:N[°º\.]|No\.?|Número)\s*(\d+)/i', $texto, $matches)) {
            return $matches[1];
        }
        if (preg_match('/(\d{3,4})/', $texto, $matches)) {
            return $matches[1];
        }
        return '';
    }
    
    private function extraerFecha($texto) {
        if (preg_match('/(\d{2})[\/\-](\d{2})[\/\-](\d{4})/', $texto, $matches)) {
            return $matches[3] . '-' . $matches[2] . '-' . $matches[1];
        }
        return date('Y-m-d');
    }
    
    private function generarResumen($titulo, $contenido) {
        $resumen = "Documento oficial: " . $titulo;
        if (strlen($resumen) > 150) {
            $resumen = substr($resumen, 0, 147) . '...';
        }
        return $resumen;
    }
    
    private function getIcono($tipo) {
        $mapa = [
            'gaceta' => 'bi-newspaper',
            'ordenanza' => 'bi-file-text',
            'decreto' => 'bi-file-earmark',
            'mapa' => 'bi-map',
            'informativo' => 'bi-info-circle'
        ];
        return $mapa[$tipo] ?? 'bi-file-pdf';
    }
    
    private function getColor($tipo) {
        $mapa = [
            'gaceta' => 'danger',
            'ordenanza' => 'primary',
            'decreto' => 'success',
            'mapa' => 'info',
            'informativo' => 'secondary'
        ];
        return $mapa[$tipo] ?? 'dark';
    }
}
?>