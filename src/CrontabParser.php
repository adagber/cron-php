<?php

namespace Vitrac\PhpCron;

use Cron\CronExpression;

/**
 * Esta clase me permite parsear las distintas lineas de crontab
 */
class CrontabParser
{

  /**
   * Me valida un texto con varias lineas
   * cada linea es una regla crontab a validar
   * 
   * @param string $content
   * 
   */
  public static function checkMultiLines(string $content): bool
  {

    //Convertimaos el texto en array un elemento por fila
    $list = explode(PHP_EOL, $content); 
    $result = self::check($list);

    return $result;
  }
  
  /**
   * Me comprueba las reglas
   * el argumento puede ser una string con la fila del crontab
   * o un array un conjunto de reglas a validar
   * 
   * @param string | array $content Reglas a validar
   */
  public static function check($content): bool
  {

    if(is_string($content)){

      return self::checkLine($content);
    } elseif(is_array($content)){

      
      foreach($content as $line){

        $line = trim($line);

        if(empty($line)){

          continue;
        }

        if(!is_string($line)){

          throw new \RuntimeException('The argument must be an array of strings');
        }

        if(!self::checkLine($line)){

          return false;
        }
      }

      return true;
    } else {

      throw new \RuntimeException('The argument must be a string or array');
    }
  }

  /**
   * Me parsea una linea de crontab y me devuelve un array con dos campos
   * el primero es la expresion (las 5 primeras columnas)
   * el segundo es el comando
   * 
   * Si no se puede parsear lanza exceptión
   * 
   * @param string $line
   * @throws \RuntimeException
   */
  public static function parse(string $line): array
  {
    try {
      return self::doParse($line);
    } catch (\Exception $exc) {

      throw new \RuntimeException(sprintf('Invalid line %s', $line));
    }
  }

  protected static function checkLine(string $line): bool
  {

    try {
      self::parse($line);
    } catch (\Exception $th) {
      return false;
    }

    return true;
  } 

  protected static function doParse(string $line): array
  {

    $line = trim($line);

    //Comprobamos si la linea contiene una expresión
    if(preg_match('/^@/', $line)){

      //Separamos las lineas en columnas
      $columns = preg_split("/[\s]+/", $line);

      $expression = implode(' ', array_slice($columns, 0, 1));
      $cmd = implode(' ', array_slice($columns, 1));

      return [$expression, $cmd];
    }

    //Separamos las lineas en columnas
    $columns = preg_split("/[\s]+/", $line);

    //Separamos la expresion del comando
    $expression = implode(' ', array_slice($columns, 0, 5));

    //Validamos la expresion
    if(!CronExpression::isValidExpression($expression)){

      throw new \RuntimeException(sprintf('Invalid (%s) expression', $expression));
    }

    //Obtenemos el comando
    $cmd = implode(' ', array_slice($columns, 5));

    return [$expression, $cmd];
    
  }
}