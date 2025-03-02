<?php
// Include Composer's autoloader
require_once '../vendor/autoload.php'; // Correct path to autoload.php
// Make sure the path is correct

// Set your Stripe Secret Key
\Stripe\Stripe::setApiKey('sk_test_51QRZXgFxCsIfG670Nz9mQjLP8YpUTfk3D4uSHa5qi7TxyjkG5nVL4kNn77yTQaDznkkYbA3YlU3MVkoeIs8DGph900zhNc6tmM'); // Replace with your actual test secret key

header('Content-Type: application/json');

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Test Product',
                ],
                'unit_amount' => 2000, // Amount in cents (e.g., $20.00)
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/success.html',
        'cancel_url' => 'http://localhost/cancel.html',
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
