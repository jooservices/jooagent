<?php

namespace JOOservices\Client\Data;

use JOOservices\Client\Contracts\UserAgentDefinitionProviderInterface;
use JOOservices\Client\Dto\UserAgentDefinition;
use JOOservices\Client\ValueObjects\Browser;
use JOOservices\Client\ValueObjects\BrowserVersionCatalog;
use JOOservices\Client\ValueObjects\Device;
use JOOservices\Client\ValueObjects\OperatingSystem;
use JOOservices\Client\ValueObjects\UserAgentPattern;
use JOOservices\Client\ValueObjects\VersionPool;

class UserAgentDefinitionProvider implements UserAgentDefinitionProviderInterface
{
    public function __construct(private ?BrowserVersionCatalog $browserVersions = null)
    {
    }

    /**
     * @return UserAgentDefinition[]
     */
    public function definitions(): array
    {
        return [
            $this->windowsChrome(),
            $this->macSafari(),
            $this->androidChrome(),
            $this->linuxFirefox(),
            $this->iosSafari(),
            $this->ipadSafari(),
            $this->playStationBrowser(),
            $this->googlebotMobile(),
            $this->samsungSmartTv(),
        ];
    }

    private function windowsChrome(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('windows', 'Windows', 'Windows NT', new VersionPool(['10.0', '11.0'])),
            new Device('desktop', 'Desktop', 'Win64; x64', 'desktop'),
            new Browser('chrome', 'Google Chrome', 'Chrome', new VersionPool($this->versions('chrome', [
                '120.0.6099.129',
                '121.0.6115.86',
                '122.0.6261.111',
            ]))),
            new UserAgentPattern('Mozilla/5.0 ({os_token} {os_version}; {device_descriptor}) AppleWebKit/537.36 (KHTML, like Gecko) {browser_token}/{browser_version} Safari/537.36'),
            ['desktop', 'windows', 'chrome']
        );
    }

    private function macSafari(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('macos', 'macOS', 'Intel Mac OS X', new VersionPool(['10_15_7', '12_6', '13_5'])),
            new Device('desktop', 'Mac Desktop', 'Macintosh; Intel Mac OS X', 'desktop'),
            new Browser('safari', 'Safari', 'Version', new VersionPool($this->versions('safari', ['16.5', '17.0', '17.5']))),
            new UserAgentPattern('Mozilla/5.0 ({device_descriptor} {os_version}) AppleWebKit/605.1.15 (KHTML, like Gecko) {browser_token}/{browser_version} Safari/605.1.15'),
            ['desktop', 'macos', 'safari']
        );
    }

    private function androidChrome(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('android', 'Android', 'Android', new VersionPool(['13', '14'])),
            new Device('mobile', 'Android Mobile', 'Pixel 7', 'mobile'),
            new Browser('chrome', 'Google Chrome Mobile', 'Chrome', new VersionPool($this->versions('chrome', [
                '120.0.6099.231',
                '121.0.6115.101',
                '122.0.6261.94',
            ]))),
            new UserAgentPattern('Mozilla/5.0 (Linux; {os_token} {os_version}; {device_descriptor}) AppleWebKit/537.36 (KHTML, like Gecko) {browser_token}/{browser_version} Mobile Safari/537.36'),
            ['mobile', 'android', 'chrome']
        );
    }

    private function linuxFirefox(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('linux', 'Linux', 'X11; Ubuntu; Linux', new VersionPool(['22.04', '24.04'])),
            new Device('desktop-linux', 'Linux Desktop', 'X11; Ubuntu; Linux x86_64', 'desktop'),
            new Browser('firefox', 'Mozilla Firefox', 'Firefox', new VersionPool($this->versions('firefox', ['120.0', '121.0', '122.0']))),
            new UserAgentPattern('Mozilla/5.0 ({device_descriptor}) Gecko/20100101 {browser_token}/{browser_version}'),
            ['desktop', 'linux', 'firefox']
        );
    }

    private function iosSafari(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('ios', 'iOS', 'CPU iPhone OS', new VersionPool(['16_6', '17_0', '17_5'])),
            new Device('iphone', 'iPhone', 'iPhone', 'mobile'),
            new Browser('safari-mobile', 'Mobile Safari', 'Version', new VersionPool($this->versions('safari', ['16.6', '17.0', '17.5']))),
            new UserAgentPattern('Mozilla/5.0 ({device_descriptor}; {os_token} {os_version} like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) {browser_token}/{browser_version} Mobile/15E148 Safari/604.1'),
            ['mobile', 'ios', 'safari']
        );
    }

    private function ipadSafari(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('ipados', 'iPadOS', 'CPU OS', new VersionPool(['16_6', '17_0', '17_5'])),
            new Device('ipad', 'iPad', 'iPad', 'tablet'),
            new Browser('safari-tablet', 'Safari iPad', 'Version', new VersionPool($this->versions('safari', ['16.6', '17.0', '17.5']))),
            new UserAgentPattern('Mozilla/5.0 ({device_descriptor}; {os_token} {os_version} like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) {browser_token}/{browser_version} Mobile/15E148 Safari/604.1'),
            ['tablet', 'ios', 'safari']
        );
    }

    private function playStationBrowser(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('playstation', 'PlayStation', 'PlayStation 5', new VersionPool(['3.11', '4.00'])),
            new Device('playstation5', 'PlayStation 5', 'PlayStation 5', 'console'),
            new Browser('playstation-browser', 'PlayStation Browser', 'PlayStationBrowser', new VersionPool(['5.0', '6.0'])),
            new UserAgentPattern('Mozilla/5.0 ({device_descriptor} {os_version}) AppleWebKit/605.1.15 (KHTML, like Gecko) {browser_token}/{browser_version}'),
            ['console', 'sony', 'playstation']
        );
    }

    private function googlebotMobile(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('linux-bot', 'Linux (Bot)', 'Android', new VersionPool(['13', '14'])),
            new Device('googlebot-mobile', 'Googlebot Mobile', 'Pixel 7 Pro', 'bot'),
            new Browser('googlebot', 'Googlebot', 'Chrome', new VersionPool($this->versions('chrome', ['120.0.6099.231', '122.0.6261.111']))),
            new UserAgentPattern('Mozilla/5.0 (Linux; {os_token} {os_version}; {device_descriptor}) AppleWebKit/537.36 (KHTML, like Gecko) {browser_token}/{browser_version} Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'),
            ['bot', 'crawler', 'google', 'mobile']
        );
    }

    private function samsungSmartTv(): UserAgentDefinition
    {
        return new UserAgentDefinition(
            new OperatingSystem('tizen', 'Tizen', 'Tizen', new VersionPool(['6.5', '7.0'])),
            new Device('samsung-tv', 'Samsung Smart TV', 'Samsung; Tizen', 'tv'),
            new Browser('samsung-browser', 'Samsung TV Browser', 'SamsungBrowser', new VersionPool(['3.1', '3.2'])),
            new UserAgentPattern('Mozilla/5.0 ({device_descriptor} {os_version}) AppleWebKit/537.36 (KHTML, like Gecko) {browser_token}/{browser_version} TV Safari/537.36'),
            ['tv', 'samsung', 'smart-tv']
        );
    }

    /**
     * @param string[] $defaults
     * @return string[]
     */
    private function versions(string $browser, array $defaults): array
    {
        if ($this->browserVersions === null) {
            return $defaults;
        }

        $latest = $this->browserVersions->latest($browser);

        if ($latest === null) {
            return $defaults;
        }

        return array_values(array_unique([
            $latest,
            ...$defaults,
        ]));
    }
}
