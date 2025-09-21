<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['prescriptionId']) && isset($data['action'])) {
        $prescriptionId = $data['prescriptionId'];
        $action = $data['action']; // 'approve' or 'reject'
        $notes = $data['notes'] ?? '';

        // In a real application, you would update a database here.
        // Example: update_prescription_status($prescriptionId, $action, $notes);

        // For now, we'll just return a success message
        echo json_encode([
            'status' => 'success',
            'message' => "Prescription #{$prescriptionId} has been {$action}d.",
            'details' => [
                'action' => $action,
                'notes' => $notes
            ]
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}