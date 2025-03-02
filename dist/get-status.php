<?php

require __DIR__ . '/vendor/autoload.php';

use Classes\LavaPaymentService;

header('Content-Type: application/json');

$lavaPaymentService = new LavaPaymentService();

$invoiceId = $_GET['invoice_id'] ?? null;

try {
	if(!$invoiceId)
	{
		throw new Exception('Произошла ошибка! Вы не передали обязательные параметры.');
	}

	$getStatusInvoice = $lavaPaymentService->getStatusInvoice($invoiceId);

	echo json_encode($getStatusInvoice);
} catch (Exception $error)
{
	http_response_code(400);

	echo json_encode([
		'success' => false,
	]);
}
