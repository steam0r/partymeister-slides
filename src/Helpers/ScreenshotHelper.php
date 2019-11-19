<?php

namespace Partymeister\Slides\Helpers;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class ScreenshotHelper
{

    protected $driver;

    /**
     * ScreenshotHelper constructor.
     * Initialize a browser
     */
    public function __construct()
    {
        $host = 'http://localhost:9515';
        $options = new ChromeOptions();
        $options->addArguments(array(
            '--headless',
            '--window-size=1920x1080',
            '--disable-gpu',
            '--no-sandbox'
        ));

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $this->driver = RemoteWebDriver::create($host, $capabilities, 5000);
    }

    /**
     * @param $url
     * @param $file
     */
    public function screenshot($url, $file)
    {
        $this->driver->get($url);
        $this->driver->takeScreenshot($file);
    }

    /**
     * Throw away the browser
     */
    function __destruct()
    {
        if ($this->driver instanceOf RemoteWebDriver) {
            $this->driver->close();
        }
    }
}
