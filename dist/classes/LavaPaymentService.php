<?php

namespace Classes;

use Lava\Api\Http\LavaFacade;
use Lava\Api\Dto\Request\Invoice\CreateInvoiceDto;
use Lava\Api\Dto\Request\Invoice\GetStatusInvoiceDto;

class LavaPaymentService
{
	private LavaFacade $lavaFacade;

	public function __construct()
	{
		$this->lavaFacade = new LavaFacade(
			'cd7582aec240384cfc27c5facc19761e49aa6c23',
			'8aab3ae1-2e5a-4cce-8eaa-26853bddf8b8',
			'e7532277d6a98cb8cc79522fe8253316eca5f558',
		);
	}

	public function createInvoice(
		int $starsQuantity,
		string $telegramAccountUsername,
	)
	{
		$data = microtime(true) . rand();
		$hash = sha1($data);

		$orderId = sprintf(
			'%08s-%04s-%04x-%04x-%12s',
			substr($hash, 0, 8),
			substr($hash, 8, 4),
			(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x4000,
			(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
			substr($hash, 20, 12)
		);

		$paymentSum = $starsQuantity * 1.8;

		$createInvoiceDto = new CreateInvoiceDto(
			$paymentSum,
			$orderId,
			'https://starsellpro.com/result.php',
			'https://starsellpro.com/success.html',
			'https://starsellpro.com/fail.html',
			300,
			json_encode([
				'stars_quantity' => $starsQuantity,
				'exchange_rate' => 1.8,
				'payment_sum' => $paymentSum,
				'telegram_account_nickname' => $telegramAccountUsername,
			]),
			"$telegramAccountUsername ($starsQuantity шт.)",
		);

		$createdInvoice = $this->lavaFacade->createInvoice($createInvoiceDto);

		return [
			'invoiceId' => $createdInvoice->getInvoiceId(),
			'amount' => $createdInvoice->getAmount(),
			'expired' => $createdInvoice->getExpired(),
			'status' => $createdInvoice->getStatus(),
			'shopId' => $createdInvoice->getShopId(),
			'url' => $createdInvoice->getUrl(),
		];
	}

	public function getStatusInvoice(
		string $invoiceId,
	)
	{
		$statusInvoice = new GetStatusInvoiceDto(
			null,
			$invoiceId,
		);

		$statusInvoice = $this->lavaFacade->checkStatusInvoice($statusInvoice);

		return [
			'status' => $statusInvoice->getStatus(),
			'errorMessage' => $statusInvoice->getErrorMessage(),
			'invoiceId' => $statusInvoice->getInvoiceId(),
			'shopId' => $statusInvoice->getShopId(),
			'amount' => $statusInvoice->getAmount(),
			'expire' => $statusInvoice->getExpire(),
			'orderId' => $statusInvoice->getOrderId(),
			'failUrl' => $statusInvoice->getFailUrl(),
			'successUrl' => $statusInvoice->getSuccessUrl(),
			'hookUrl' => $statusInvoice->getHookUrl(),
			'customFields' => $statusInvoice->getCustomFields(),
			'excludeService' => $statusInvoice->getExcludeService(),
			'includeService' => $statusInvoice->getIncludeService(),
		];
	}
}
