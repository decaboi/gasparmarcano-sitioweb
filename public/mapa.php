<?php
require_once __DIR__ . '/../src/Core/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Verificar conexión
if (!$conn) {
    die("Error de conexión a la base de datos");
}

// Coordenadas reales de Juan Griego (centro)
$centro_lat = 11.0667;
$centro_lng = -63.9667;

// Definir coordenadas reales de atractivos turísticos (Base de datos local)
$atractivos_con_coords = [
    'Iglesia San Nicolás de Bari' => ['lat' => 11.0689, 'lng' => -63.9658, 'tipo' => 'historia'],
    'Bahía de Juan Griego' => ['lat' => 11.0700, 'lng' => -63.9700, 'tipo' => 'playa'],
    'Cerro El Copey' => ['lat' => 11.0500, 'lng' => -63.9400, 'tipo' => 'naturaleza'],
    'Playa El Agua' => ['lat' => 11.0833, 'lng' => -63.9500, 'tipo' => 'playa'],
    'Playa Caribe' => ['lat' => 11.0750, 'lng' => -63.9600, 'tipo' => 'playa'],
    'Playa La Galera' => ['lat' => 11.0800, 'lng' => -63.9450, 'tipo' => 'playa'],
    'Malecón de Juan Griego' => ['lat' => 11.0695, 'lng' => -63.9670, 'tipo' => 'cultura'],
    'Casa de la Cultura' => ['lat' => 11.0680, 'lng' => -63.9665, 'tipo' => 'cultura'],
    'Fuerteín de la Galera' => ['lat' => 11.0820, 'lng' => -63.9420, 'tipo' => 'historia'],
    'Museo Marino de Margarita' => ['lat' => 11.0675, 'lng' => -63.9660, 'tipo' => 'cultura']
];

// Intentar obtener atractivos de la base de datos, si falla usar los manuales
$atractivos = [];
$error_sql = false;

try {
    // Consulta más simple sin JOIN para evitar errores
    $sql = "SELECT id, titulo, resumen, imagen_destacada FROM contenido WHERE status = 1";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $coords = $atractivos_con_coords[$row['titulo']] ?? ['lat' => $centro_lat, 'lng' => $centro_lng, 'tipo' => 'general'];
            $atractivos[] = [
                'id' => $row['id'],
                'titulo' => $row['titulo'],
                'resumen' => $row['resumen'] ?: 'Atractivo turístico de Juan Griego',
                'imagen_destacada' => $row['imagen_destacada'],
                'latitud' => $coords['lat'],
                'longitud' => $coords['lng'],
                'tipo' => $coords['tipo']
            ];
        }
    } else {
        // Si no hay datos en la BD, usar los manuales
        $error_sql = true;
        foreach ($atractivos_con_coords as $nombre => $coords) {
            $atractivos[] = [
                'id' => 0,
                'titulo' => $nombre,
                'resumen' => 'Atractivo turístico de Juan Griego, Municipio Gaspar Marcano.',
                'imagen_destacada' => '',
                'latitud' => $coords['lat'],
                'longitud' => $coords['lng'],
                'tipo' => $coords['tipo']
            ];
        }
    }
} catch (Exception $e) {
    // Si hay error en la consulta, usar los manuales
    foreach ($atractivos_con_coords as $nombre => $coords) {
        $atractivos[] = [
            'id' => 0,
            'titulo' => $nombre,
            'resumen' => 'Atractivo turístico de Juan Griego, Municipio Gaspar Marcano.',
            'imagen_destacada' => '',
            'latitud' => $coords['lat'],
            'longitud' => $coords['lng'],
            'tipo' => $coords['tipo']
        ];
    }
}

include '../src/Views/partials/header.php';
?>

<main>
    <div class="container-fluid py-4">
        <div class="text-center mb-4">
            <h1 class="display-5" style="color: #0A2472;">Mapa Turístico de Juan Griego</h1>
            <div class="mx-auto" style="width: 80px; height: 3px; background-color: #FFCD00; margin: 15px auto;"></div>
            <p class="lead">Descubre los atractivos naturales, históricos y culturales del Municipio Gaspar Marcano</p>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div id="mapa" style="height: 550px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-geo-alt"></i> Puntos de Interés
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <div id="lista-puntos">
                            <?php foreach($atractivos as $index => $item): ?>
                            <div class="punto-item mb-3 p-2 border-bottom rounded" data-index="<?php echo $index; ?>" data-lat="<?php echo $item['latitud']; ?>" data-lng="<?php echo $item['longitud']; ?>" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill fs-2 text-primary me-2"></i>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($item['resumen'], 0, 80)); ?></small>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Leaflet CSS y JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Coordenadas de Juan Griego
const centro = [11.0667, -63.9667];

// Inicializar mapa
const mapa = L.map('mapa').setView(centro, 13);

// Cargar tiles
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    subdomains: 'abcd',
    maxZoom: 19
}).addTo(mapa);

// Marcador principal
L.marker(centro).addTo(mapa)
    .bindPopup('<b>Juan Griego</b><br>Municipio Gaspar Marcano<br>Isla de Margarita')
    .openPopup();

// Datos de atractivos
const atractivos = <?php echo json_encode($atractivos); ?>;

// Colores por tipo
function getColor(tipo) {
    const colores = {
        'historia': '#0A2472',
        'playa': '#FFCD00',
        'naturaleza': '#28a745',
        'cultura': '#dc3545',
        'general': '#17a2b8'
    };
    return colores[tipo] || '#17a2b8';
}

// Icono personalizado
function crearIcono(color) {
    return L.divIcon({
        html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
        iconSize: [14, 14],
        className: 'custom-marker'
    });
}

// Agregar marcadores
const marcadores = [];
atractivos.forEach((atractivo, index) => {
    const lat = parseFloat(atractivo.latitud);
    const lng = parseFloat(atractivo.longitud);
    const color = getColor(atractivo.tipo);
    
    const marker = L.marker([lat, lng], { icon: crearIcono(color) }).addTo(mapa);
    
    let contenido = `<b>${atractivo.titulo}</b><br>`;
    contenido += `<small>${atractivo.resumen.substring(0, 100)}...</small><br>`;
    contenido += `<a href="/gasparmarcano-sitioweb/public/turismo.php" class="btn btn-sm btn-primary mt-2">Ver más</a>`;
    
    marker.bindPopup(contenido);
    marcadores.push({ marker, lat, lng, index });
});

// Evento click en lista lateral
document.querySelectorAll('.punto-item').forEach(el => {
    el.addEventListener('click', () => {
        const lat = parseFloat(el.dataset.lat);
        const lng = parseFloat(el.dataset.lng);
        mapa.setView([lat, lng], 15);
        
        // Buscar y abrir el marcador
        marcadores.forEach(m => {
            if (Math.abs(m.lat - lat) < 0.001 && Math.abs(m.lng - lng) < 0.001) {
                m.marker.openPopup();
            }
        });
        
        // Resaltar elemento
        el.style.backgroundColor = '#f0f8ff';
        setTimeout(() => el.style.backgroundColor = '', 1000);
    });
});

// Control de escala
L.control.scale().addTo(mapa);
</script>

<style>
    .custom-marker {
        background: transparent;
        border: none;
    }
    .punto-item {
        transition: all 0.3s;
    }
    .punto-item:hover {
        background-color: #f0f8ff;
        transform: translateX(5px);
    }
</style>

<?php include '../src/Views/partials/footer.php'; ?>