<?php
// только для демо: отдаем обратно то, что пришло, index.html это показывает

$coords = json_decode($_POST['coords'] ?? '', true) ?: [];

header('Content-Type: application/json');
echo json_encode(['count' => count($coords), 'coords' => $coords]);
