<?php

namespace Vitrac\PhpCron\Tests;

use Vitrac\PhpCron\Scheduler;
use PHPUnit\Framework\TestCase;

class SchedulerTest extends TestCase
{

  protected $scheduler;

  protected $cronFilename;

  protected $logFilename;


  protected function setUp(): void
  {

    $this->logFilename = dirname(__DIR__).'/var/output.test.log';
    $this->cronFilename = dirname(__DIR__).'/var/crontab.test.txt';

    $rules = <<<EOF
*	*	*	*	*	pwd
*/5	*	*	*	*	ls -la
EOF;
    file_put_contents($this->cronFilename, $rules, FILE_APPEND | LOCK_EX);

    $this->scheduler = new Scheduler($this->cronFilename, $this->logFilename);
  }
  
  public function testLoadConfig(): void
  { 
    
    $this->assertInstanceOf(Scheduler::class, $this->scheduler->loadConfig());
  }

  
  public function testHasCrontabChanged(): void
  {

    $this->scheduler->loadConfig();

    //Aseveramos que el contenido no ha cambiado
    $this->assertNotTrue($this->scheduler->hasCrontabChanged());

    //Modificamos el contenido externamente
    $rules = <<<EOF
10	*	*	*	*	whoami
EOF;
    file_put_contents($this->cronFilename, $rules, FILE_APPEND | LOCK_EX);

    //Aseveramos que el contenido ha cambiado
    $this->assertTrue($this->scheduler->hasCrontabChanged());
  }
}