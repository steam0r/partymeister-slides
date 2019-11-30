<?php

namespace Partymeister\Slides\Console\Commands;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class PartymeisterChromiumProcess
 * @package Partymeister\Competitions\Console\Commands
 */
class PartymeisterChromiumProcess extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'partymeister:slides:webdriver {status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start or stop the chromium webdriver';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->argument('status') === 'start') {
            $process = new Process(config('partymeister-slides.chromedriver'));
            $process->start();
            sleep(5);
            $this->info('Started chromium webdriver');
        } elseif ($this->argument('status') === 'stop') {
            $process = new Process('killall '.config('partymeister-slides.chromedriver'));
            $process->run();
            $this->info('Stopped chromium webdriver');
        }

    }
}
