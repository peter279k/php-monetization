<?php

namespace Lee\WebMonetization;

class WebMonetization
{
    /**
     * @var string
     */
    private static $webMonetizationMetaFormat = '<meta name="monetization" content="%s">';

    /**
     * @var string
     */
    private static $paymentPointerFormat = '$%s.%s.%s/%s';

    public function __construct()
    {
    }

    /**
     * @throws InvalidPaymentPointerUrlException
     *
     * @return string
     */
    public static function generateMetaTag(string $paymentPointerUrl)
    {
        if (static::validatePaymentPointer($paymentPointerUrl) === false) {
            $errorExceptionMessage = sprintf('Given %s meta content attribute for payment pointer url is invalid', $paymentPointerUrl);

            throw new InvalidPaymentPointerUrlException($errorExceptionMessage);
        }

        return sprintf(static::$webMonetizationMetaFormat, $paymentPointerUrl);
    }

    /**
     * @throws InvalidPaymentPointerUrlException
     *
     * @return string
     */
    public static function generatePaymentPointer(string $interLedger, string $domainName, string $tldName, string $userTokenName)
    {
        $paymentPointer = sprintf(static::$paymentPointerFormat, $interLedger, $domainName, $tldName, $userTokenName);

        if (static::validatePaymentPointer($paymentPointer) === false) {
            $errorExceptionMessage = sprintf('Given %s payment pointer url is invalid', $paymentPointer);

            throw new InvalidPaymentPointerUrlException($errorExceptionMessage);
        }

        return $paymentPointer;
    }

    public static function validatePaymentPointer(string $paymentPointerPrefix): bool
    {
        $matchedCount = preg_match('/\$(\w+)\.(\w+)\.(\w+)\/(\w+)/', $paymentPointerPrefix, $matched);

        return $matchedCount === 1 && $matched[0] === $paymentPointerPrefix;
    }

    /**
     * @return array<string>
     */
    public function getDefaultPaymentPointerUrls(): array
    {
        return PaymentPointerPrefixes::$paymentPointerPrefixes;
    }
}
