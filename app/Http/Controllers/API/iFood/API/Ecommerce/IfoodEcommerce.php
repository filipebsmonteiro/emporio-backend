<?php

namespace iFood\API\Ecommerce;

use Cielo\API30\Ecommerce\Request\TokenizeMerchantRequest;
use iFood\API\Ecommerce\Request\CleanEventsPollingRequest;
use iFood\API\Ecommerce\Request\EventsPollingRequest;
use iFood\API\Ecommerce\Request\ReadSaleRequest;
use iFood\API\Ecommerce\Request\UpdateSaleRequest;
use iFood\API\Merchant;

/**
 * The iFood Ecommerce SDK front-end;
 */
class IfoodEcommerce
{

    private $merchant;

    private $environment;

    /**
     * Create an instance of iFoodEcommerce
     *
     * @param Merchant $merchant
     *            The merchant credentials
     * @param Environment environment
     *            The environment: {@link Environment::production()}
     */
    public function __construct(Merchant $merchant, Environment $environment = null)
    {
        if ($environment == null) {
            $environment = Environment::production();
        }

        $this->merchant    = $merchant;
        $this->environment = $environment;
    }

    /**
     * Send the Sale to be created and return the Sale with tid and the status
     * returned by iFood.
     *
     * @param Sale $sale
     *            The preconfigured Sale
     *
     * @return Sale The Sale returned by iFood.
     *
     * @throws \iFood\API\Ecommerce\Request\ifoodRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://github.io/Webservice/english.html#error-codes">Error
     *      Codes</a>
     */
    public function tokenizeMerchant()
    {
        $listenEventRequest = new TokenizeMerchantRequest($this->merchant, $this->environment);

        return $listenEventRequest->execute();
    }

    /**
     * Send the Sale to be created and return the Sale with tid and the status
     * returned by iFood.
     *
     * @param Sale $sale
     *            The preconfigured Sale
     *
     * @return Sale The Sale returned by iFood.
     *
     * @throws \iFood\API\Ecommerce\Request\ifoodRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://github.io/Webservice/english.html#error-codes">Error
     *      Codes</a>
     */
    public function listenEvents()
    {
        $listenEventRequest = new EventsPollingRequest($this->merchant, $this->environment);

        return $listenEventRequest->execute();
    }

    /**
     * Query a Sale on iFood by correlationId
     *
     * @param string $correlationId
     *            The correlationId to be queried
     *
     * @return Sale The Sale returned by iFood.
     *
     * @throws \iFood\API\Ecommerce\Request\ifoodRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://github.io/Webservice/english.html#error-codes">Error
     *      Codes</a>
     */
    public function readSale($correlationId)
    {
        $readSaleRequest = new ReadSaleRequest($this->merchant, $this->environment);

        return $readSaleRequest->execute($correlationId);
    }

    /**
     * Cancel a Sale on iFood by paymentId and speficying the amount
     *
     * @param string  $paymentId
     *            The paymentId to be queried
     * @param integer $amount
     *            Order value in cents
     *
     * @return Sale The Sale returned by iFood.
     *
     * @throws \iFood\API\Ecommerce\Request\ifoodRequestException if anything gets wrong.
     *
     * @see <a href=
     *      "https://github.io/Webservice/english.html#error-codes">Error
     *      Codes</a>
     */
    public function cleanSales($arrayIds)
    {
        $cleanEventRequest = new CleanEventsPollingRequest($this->merchant, $this->environment);

        return $cleanEventRequest->execute($arrayIds);
    }

    public function updateSale($correlationId, $status)
    {
        $updateSaleRequest = new UpdateSaleRequest($this->merchant, $this->environment, $correlationId);

        return $updateSaleRequest->execute($status);
    }
}
