<?php

namespace Tests;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Mockery;
use AlfredNutileInc\HPClient\HubPlannerProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return
            [
            HubPlannerProvider::class,
            ];
    }


    public function setUp()
    {
        parent::setUp();

        $this->app->setBasePath(__DIR__ . '/../');

        //Because I use dynamic facades
        \File::makeDirectory(base_path("storage/framework/cache"), 0755, true, true);

        //$this->withFactories(__DIR__ . '/../database/factories');

        $this->app['router']->get('example', function () {
            return view("testing");
        })->name('featured');

        \View::addLocation(__DIR__ . '/../views');

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--path' => realpath(__DIR__ . '/migrations')
        ]);

        $output = $this->artisan('migrate', ['--database' => 'testing']);
    }


    protected function getEnvironmentSetUp($app)
    {
        $app->configureMonologUsing(function ($monolog) {
            $path = __DIR__ . "/logs/laravel.log";

            $handler = $handler = new StreamHandler($path, 'debug');

            $handler->setFormatter(tap(new LineFormatter(null, null, true, true), function ($formatter) {
                /** @var LineFormatter $formatter */
                $formatter->includeStacktraces();
            }));

            /** @var \Monolog\Logger $monolog */
            $monolog->pushHandler($handler);
        });

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.debug', env('APP_DEBUG', true));

        $app['config']->set('laravel-feature-flag.logging', true);
    }
}
