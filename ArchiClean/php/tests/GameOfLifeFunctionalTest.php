<?php
declare(strict_types=1);

namespace GameOfLife\Tests;

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

include 'game.php';

final class GameOfLifeTFunctionalTest extends TestCase
{
    protected $parameters = [
        'glider',
        'lwss',
        'penta',
        'pulsar' 
    ];

    /** @test */
    public function get_endpoint()
    {
        foreach ($this->parameters as $patternName) {
            initApp();
            $app = $GLOBALS['app'];
            
            $environment = Environment::mock([
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/grid?template='.$patternName,
            ]);
            $request = Request::createFromEnvironment($environment);
            $app->getContainer()['request'] = $request;
            
            $response = $app->run(true);

            $responseAsArray = \json_decode((string)$response->getBody(), true);
            $expectedPatternAsArray = \json_decode(\GameOfLife\Tests\BaseFixture::$$patternName, true);

            $id = 0;
            foreach ($expectedPatternAsArray as $row) {
                $this->assertEquals($row['x'], $responseAsArray[$id]['x'], '[GET] '.$patternName." / element $id / x");
                $this->assertEquals($row['y'], $responseAsArray[$id]['y'], '[GET] '.$patternName." / element $id / y");
                $this->assertEquals($row['alive'], $responseAsArray[$id]['alive'], '[GET] '.$patternName." / element $id / alive");
                $id++;
            }
        }
    }

    /** @test */
    public function post_endpoint()
    {
        foreach ($this->parameters as $patternName) {
            initApp();
            $app = $GLOBALS['app'];

            $environment = Environment::mock([
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI'    => '/grid',
            ]);
            $request = Request::createFromEnvironment($environment);
            $request = $request->withParsedBody(['data' => \GameOfLife\Tests\BaseFixture::$$patternName]);
            $app->getContainer()['request'] = $request;
            
            $response = $app->run(true);

            $responseAsArray = \json_decode((string)$response->getBody(), true);
            $expectedPatternAsArray = \json_decode(\GameOfLife\Tests\EvolvedFixture::$$patternName, true);

            $id = 0;
            foreach ($expectedPatternAsArray as $row) {
                $this->assertEquals($row['x'], $responseAsArray[$id]['x'], '[POST] '.$patternName." / element $id / x");
                $this->assertEquals($row['y'], $responseAsArray[$id]['y'], '[POST] '.$patternName." / element $id / y");
                $this->assertEquals($row['alive'], $responseAsArray[$id]['alive'], '[POST] '.$patternName." / element $id / alive");
                $id++;
            }
        }
    }
}
