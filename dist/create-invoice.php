<?php

require __DIR__ . '/vendor/autoload.php';

use Classes\LavaPaymentService;

header('Content-Type: application/json');

$lavaPaymentService = new LavaPaymentService();

$starsQuantity = $_GET['stars_quantity'] ?? null;
$nickname = $_GET['nickname'] ?? null;

try {
	if(!$starsQuantity || !$nickname)
	{
		throw new Exception('Произошла ошибка! Вы не передали обязательные параметры.');
	}

	$createInvoice = $lavaPaymentService->createInvoice($starsQuantity, $nickname);

	echo json_encode($createInvoice);
} catch (Exception $error)
{
	http_response_code(400);

	echo json_encode([
		'success' => false,
	]);
}
