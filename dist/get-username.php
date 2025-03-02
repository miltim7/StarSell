<?php

require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

$username = $_GET['username'] ?? null;

try {
	if(!$username)
	{
		throw new Exception('Произошла ошибка! Вы не передали обязательные параметры.');
	}

	$response = file_get_contents('https://t.me/' . urlencode($username));

	libxml_use_internal_errors(true);
	$dom = new DOMDocument();
	$dom->loadHTML($response);

	$xpath = new DOMXPath($dom);
	$titleNode = $xpath->query('//meta[@property="og:title"]')->item(0);
	$imageNode = $xpath->query('//meta[@property="og:image"]')->item(0);

	$title = $titleNode ? $titleNode->getAttribute('content') : null;
	$image = $imageNode ? $imageNode->getAttribute('content') : null;

	if(!$title || !$image)
	{
		throw new Exception('Данные не найдены');
	}

	echo json_encode([
		'name' => $title,
		'photo' => $image,
	]);
} catch (Exception $error) {
	http_response_code(400);

	echo json_encode([
		'success' => false,
	]);
}
