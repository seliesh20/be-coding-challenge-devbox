<?php 

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Console Command Test Case
 */
class LogSyncCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        //initialising setup
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $application->setAutoExit(false);

        //calling command
        $command = $application->find('app:log-sync');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'filename' => 'logs.txt'
        ]);

        //validation
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Inserted lines', $output);
    }
}