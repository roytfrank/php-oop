<?php
namespace Tests\Units;

use PHPUnit\Framework\TestCase;
use App\Exception\InvalidLogLevelArgument;
use App\Logger\LogLevel;
use App\Contracts\LoggerInterface;
use App\Helpers\App;
use App\Logger\Logger;

class TestApplication extends TestCase{

    private $app;
    private $logger;

    protected function setUp(){
        parent::setUp();
        $this->app = new App;
        $this->logger = new Logger;
    }

    public function testItIsInstanceOfApp(){
        $this->assertInstanceOf(App::class, new App);
    }

    public function testItCanGetAppDatasetFromClass(){

        self::assertTrue($this->app->isRunningFromconsole());
        self::assertInstanceOf(\DateTime::class, $this->app->getServerDateTime());
        self::assertSame("test", $this->app->getEnvironment());
        $this->assertNotNull($this->app->getLogPath());
    }

    public function testItLoggerImplementsLoggerInterface(){    
        self::assertInstanceOf(LoggerInterface::class, $this->logger);
    }

    public function testItCanCreateLogFile(){

        $this->logger->info("Test Info Logger");
        $this->logger->error("Test error Logger");
        $this->logger->emergency("Test Emergency Logger");
        $this->logger->log(LogLevel::ALERT, "Test Info Logger");

        $filename = sprintf("%s/%s-%s.log", $this->app->getLogPath(), 'test', date('j.n.Y'));
        self::assertFileExists($filename);

        $fileContent = file_get_contents($filename);
        self::assertStringContainsString("Info Logger", $fileContent);
        self::assertStringContainsString("Test error Logger", $fileContent);
        self::assertStringContainsString("Test Emergency Logger", $fileContent);
        self::assertStringContainsString("Test Info Logger", $fileContent);
    
        //unlink file so that files doesn't keep growing
        unlink($filename);
        self::assertFileNotExists($filename);
    }

    public function testItThrowsInvalidLogLevelArgumentExceptionWhenWrongLogLevelGiven(){
          self::expectException(InvalidLogLevelArgument::class);
          $this->logger->log("invalid", "This is a test for invalid log level");
    }

    protected function tearDown(): void{
        parent::tearDown();
    }

}

?>