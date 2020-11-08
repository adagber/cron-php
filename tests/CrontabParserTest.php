<?php

namespace Vitrac\PhpCron\Tests;

use PHPUnit\Framework\TestCase;
use Vitrac\PhpCron\CrontabParser;

class CrontabParserTest extends TestCase
{
  
  /**
   * @dataProvider linesProvider
   */
  public function testParse($line, $expectedExpression, $expectedCmd): void
  { 
    
    list($expression, $cmd) = CrontabParser::parse($line);

    $this->assertEquals($expectedExpression, $expression);
    $this->assertEquals($expectedCmd, $cmd);
  }

  
  public function linesProvider(): array
  {

    return [
      ['*   *   *   *   * /bin/bash /path/to/cmd.sh', '* * * * *', '/bin/bash /path/to/cmd.sh'],
      ['* * * * * php /var/www/domain.com/project emails:send >> /dev/null 2>&1', '* * * * *', 'php /var/www/domain.com/project emails:send >> /dev/null 2>&1'],
      ['@yearly /path/to/script.sh', '@yearly', '/path/to/script.sh']
    ];
  }
}