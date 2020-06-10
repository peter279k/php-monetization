<?php

namespace Lee\WebMonetization\Tests;

use Lee\WebMonetization\InvalidPaymentPointerUrlException;
use Lee\WebMonetization\PaymentPointerPrefixes;
use Lee\WebMonetization\WebMonetization;
use PHPUnit\Framework\TestCase;

class WebMonetizationTest extends TestCase
{
    public function validPaymentPointerUrlDataProvider(): array
    {
        return [
            ['$ilp.uphold.com/alice'],
            ['$ilp.gatehub.net/123456'],
            ['$pay.stronghold.co/dkjjofeofjeojfoejfoejfoeo'],
            ['$pay.stronghold.co/dkjjofeofjeojfoejfoejfoeoddjw1344jooos'],
        ];
    }

    public function invalidPaymentPointerUrlDataProvider(): array
    {
        return [
            ['$ilp.upholdcom/alice'],
            ['$ilpuphold.com/alice'],
            ['$ilpgatehub.net/123456'],
            ['$ilp.gatehubnet/123456'],
            ['$paystronghold.co/dkjjofeofjeojfoejfoejfoeo'],
            ['$pay.strongholdco/dkjjofeofjeojfoejfoejfoeoddjw1344jooos'],
        ];
    }

    public function dataProviderForGeneratePaymentPointer(): array
    {
        return [
            ['ilp', 'uphold', 'com', 'token', '$ilp.uphold.com/token'],
            ['ilp', 'uphold', 'com', 'alice', '$ilp.uphold.com/alice'],
            ['ilp', 'gatehub', 'net', 'token', '$ilp.gatehub.net/token'],
            ['ilp', 'gatehub', 'net', 'alice', '$ilp.gatehub.net/alice'],
            ['pay', 'stronghold', 'co', 'token', '$pay.stronghold.co/token'],
            ['pay', 'stronghold', 'co', 'alice', '$pay.stronghold.co/alice'],
        ];
    }

    public function invalidDataProviderForGeneratePaymentPointer(): array
    {
        return [
            ['$$$ilp', '$$uphold', 'com', 'token'],
            ['ilp', '$$uphold', 'com$$', 'token'],
            ['ilp', 'uphold', 'com$$', 'token'],
            ['ilp', 'uphold', 'com', '$$token'],
            ['ilp', 'uphold', 'com', 'alice#$$%%^&*', '$ilp.uphold.com/alice#$$%%^&*'],
            ['ilp', 'gatehub', 'net', 'alice#$$%%^&*', '$ilp.gatehub.net/alice#$$%%^&*'],
            ['pay', 'stronghold', 'co', 'alice#$$%%^&*', '$pay.stronghold.co/alice#$$%%^&*'],
        ];
    }

    public function metaTagDataProviderForGenerateMetaTag(): array
    {
        return [
            ['$ilp.uphold.com/alice', '<meta name="monetization" content="$ilp.uphold.com/alice">'],
            ['$ilp.gatehub.net/alice', '<meta name="monetization" content="$ilp.gatehub.net/alice">'],
            ['$pay.stronghold.co/alice', '<meta name="monetization" content="$pay.stronghold.co/alice">'],
        ];
    }

    /**
     * @dataProvider metaTagDataProviderForGenerateMetaTag
     */
    public function testGenerateMetaTag(string $paymentPointerUrl, string $expected): void
    {
        $metaTag = WebMonetization::generateMetaTag($paymentPointerUrl);

        $this->assertSame($expected, $metaTag);
    }

    /**
     * @dataProvider invalidPaymentPointerUrlDataProvider
     */
    public function testGenerateMetaTagShouldThrowInvalidPaymentPointerUrlException(string $paymentPointerUrl): void
    {
        $this->expectException(InvalidPaymentPointerUrlException::class);

        WebMonetization::generateMetaTag($paymentPointerUrl);
    }

    /**
     * @dataProvider validPaymentPointerUrlDataProvider
     */
    public function testValidatePaymentPointerUrlOnValidPaymentPointerUrl(string $paymentPointerUrl): void
    {
        $validateResult = WebMonetization::validatePaymentPointer($paymentPointerUrl);

        $this->assertTrue($validateResult);
    }

    /**
     * @dataProvider invalidPaymentPointerUrlDataProvider
     */
    public function testValidatePaymentPointerUrlOnInvalidPaymentPointerUrl(string $paymentPointerUrl): void
    {
        $validateResult = WebMonetization::validatePaymentPointer($paymentPointerUrl);

        $this->assertFalse($validateResult);
    }

    public function testGetDefaultPaymentPointerUrls(): void
    {
        $webMonetization = new WebMonetization();

        $this->assertSame(PaymentPointerPrefixes::$paymentPointerPrefixes, $webMonetization->getDefaultPaymentPointerUrls());
    }

    /**
     * @dataProvider dataProviderForGeneratePaymentPointer
     */
    public function testGeneratePaymentPointer(string $interLedger, string $domainName, string $tldName, string $userTokenName, string $expected): void
    {
        $paymentPointerUrl = WebMonetization::generatePaymentPointer($interLedger, $domainName, $tldName, $userTokenName);

        $this->assertSame($expected, $paymentPointerUrl);
    }

    /**
     * @dataProvider invalidDataProviderForGeneratePaymentPointer
     */
    public function testGeneratePaymentPointerShouldThrowInvalidPaymentPointerUrlException(string $interLedger, string $domainName, string $tldName, string $userTokenName): void
    {
        $this->expectException(InvalidPaymentPointerUrlException::class);

        WebMonetization::generatePaymentPointer($interLedger, $domainName, $tldName, $userTokenName);
    }
}
