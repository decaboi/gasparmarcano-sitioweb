<?php
// src/Core/IAChatbot.php
// Agente de IA conversacional para el Municipio Gaspar Marcano
// Basado en el Código de Ética de IA de Venezuela

class IAChatbot {
    
    // Base de conocimientos de Juan Griego
    private $conocimiento = [
        // Historia
        'historia' => [
            'palabras' => ['historia', 'fundación', 'origen', 'quién fundó', 'corsario', 'griego'],
            'respuesta' => "🏛️ Juan Griego fue fundado en **1730** por el corsario de origen griego **Juan Griego**, quien llegó a la Isla de Margarita al servicio de la Corona española. Es uno de los pueblos más históricos del oriente venezolano."
        ],
        'iglesia' => [
            'palabras' => ['iglesia', 'san nicolás', 'templo', 'religioso', 'colonial'],
            'respuesta' => "⛪ La **Iglesia San Nicolás de Bari** fue construida en el **siglo XVIII** y es uno de los templos más antiguos de Margarita. Su arquitectura colonial y su torre campanario la convierten en un ícono de Juan Griego."
        ],
        
        // Turismo
        'playas' => [
            'palabras' => ['playa', 'bahía', 'mar', 'arena', 'baño', 'snorkel'],
            'respuesta' => "🏖️ Juan Griego tiene hermosas playas: **Playa El Agua**, **Playa Caribe**, **Playa La Galera** y **Playa El Manglillo**. La Bahía de Juan Griego es una de las más protegidas del Caribe, ideal para nadar y hacer kayak."
        ],
        'cerro' => [
            'palabras' => ['cerro', 'montaña', 'copey', 'senderismo', 'mirador'],
            'respuesta' => "⛰️ El **Cerro El Copey** es el punto más alto de Margarita. Ofrece senderismo, avistamiento de aves y vistas panorámicas espectaculares de la bahía y el Caribe."
        ],
        'gastronomia' => [
            'palabras' => ['comida', 'gastronomía', 'pescado', 'marisco', 'arepa', 'coco'],
            'respuesta' => "🍽️ La gastronomía local incluye: pescado fresco (corvina, pargo), mariscos, **arepas de jojoto**, dulces de lechosa y ajonjolí. No te vayas sin probar el pescado a la margariteña."
        ],
        
        // Eventos y festividades
        'fiestas' => [
            'palabras' => ['fiesta', 'festividad', 'san nicolás', 'diciembre', 'tradición'],
            'respuesta' => "🎉 La **Fiesta de San Nicolás de Bari** se celebra el **6 de diciembre** con procesión marítima, música y gastronomía. También es tradicional la Semana Santa con celebraciones religiosas."
        ],
        
        // Inversiones
        'invertir' => [
            'palabras' => ['invertir', 'inversión', 'negocio', 'empresa', 'turismo', 'hotel'],
            'respuesta' => "💰 El Municipio Gaspar Marcano ofrece **Zonas de Desarrollo Turístico Especial (ZDTE)** con beneficios fiscales. Hay oportunidades en hotelería, gastronomía, tours y servicios turísticos. Contáctanos en inversiones@municipiogaspar.gob.ve"
        ],
        
        // Trámites
        'tramites' => [
            'palabras' => ['trámite', 'permiso', 'licencia', 'registro', 'serimun'],
            'respuesta' => "📋 Los trámites municipales se realizan a través del **Portal SERIMUN**. Puedes acceder desde el botón 'Portal del Contribuyente' en nuestro sitio web."
        ],
        
        // Contacto
        'contacto' => [
            'palabras' => ['contacto', 'teléfono', 'correo', 'dirección', 'oficina'],
            'respuesta' => "📞 **Oficina Principal:** Calle Bolívar, Casa Municipal, Juan Griego<br>**Teléfono:** +58 (295) 123-4567<br>**Correo:** contacto@municipiogaspar.gob.ve<br>**Horario:** Lunes a Viernes, 8:00 AM - 4:00 PM"
        ],
        
        // Alcalde
        'alcalde' => [
            'palabras' => ['alcalde', 'yul armas', 'autoridad', 'gestión'],
            'respuesta' => "👨‍💼 El Alcalde Bolivariano del Municipio Gaspar Marcano es el **Licenciado Yul Armas**, comprometido con las **7 Transformaciones (7T)** y el desarrollo turístico y social de Juan Griego."
        ],
        
        // Plan 7T
        '7t' => [
            'palabras' => ['7t', '7 transformaciones', 'plan de la patria'],
            'respuesta' => "🇻🇪 Las **7 Transformaciones (7T)** son: Economía, Seguridad, Social, Política, Internacional, Ecológica y Comunicacional. Este plan guía todas las políticas del municipio para el desarrollo del país."
        ],
        
        // Predeterminado
        'default' => [
            'respuesta' => "🤖 ¡Hola! Soy el asistente virtual del Municipio Gaspar Marcano. Puedo ayudarte con información sobre: 📍 Historia de Juan Griego, 🏖️ Playas y turismo, 🎉 Festividades, 💰 Inversiones, 📋 Trámites municipales, 📞 Contacto de la Alcaldía. ¿Qué te gustaría saber?"
        ]
    ];
    
    public function responder($pregunta) {
        $pregunta = strtolower(trim($pregunta));
        $pregunta = preg_replace('/[áä]/i', 'a', $pregunta);
        $pregunta = preg_replace('/[éë]/i', 'e', $pregunta);
        $pregunta = preg_replace('/[íï]/i', 'i', $pregunta);
        $pregunta = preg_replace('/[óö]/i', 'o', $pregunta);
        $pregunta = preg_replace('/[úü]/i', 'u', $pregunta);
        
        // Buscar coincidencias
        $mejor_coincidencia = null;
        $max_palabras = 0;
        
        foreach ($this->conocimiento as $key => $item) {
            if ($key === 'default') continue;
            
            $coincidencias = 0;
            foreach ($item['palabras'] as $palabra) {
                if (strpos($pregunta, $palabra) !== false) {
                    $coincidencias++;
                }
            }
            
            if ($coincidencias > $max_palabras) {
                $max_palabras = $coincidencias;
                $mejor_coincidencia = $key;
            }
        }
        
        if ($mejor_coincidencia && $max_palabras > 0) {
            return $this->conocimiento[$mejor_coincidencia]['respuesta'];
        }
        
        return $this->conocimiento['default']['respuesta'];
    }
    
    public function sugerirPreguntas() {
        return [
            "¿Cuál es la historia de Juan Griego?",
            "¿Qué playas hay en Juan Griego?",
            "¿Cómo puedo invertir en el municipio?",
            "¿Cuándo es la fiesta de San Nicolás?",
            "¿Cómo contacto con la Alcaldía?",
            "¿Qué es el Plan 7T?"
        ];
    }
}
?>