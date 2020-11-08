<?php
namespace Vitrac\PhpCron;

use GO\Scheduler as GoScheduler;

class Scheduler extends GoScheduler
{

  /**
   * Fichero donde esta la configuración del crontab
   * @var \SplFileInfo
   */
  private $crontabFile;

  /**
   * Checksum del fichero de configuración para detectar cuando se modifica
   * @var string
   */
  private $checksumFile;

  /**
   * Ruta del fichero donde se guardan los outputs
   * @var string
   */
  private $logFilename;

  /**
   * Array del configuraciones globales del Scheduler
   * @var array
   */
  private $config;


  /**
     * Create new instance.
     *
     * @param  array  $config
     */
    public function __construct(string $crontabFilename, string $logFilename, array $config = [])
    {
      parent::__construct($config);
      $this->crontabFile = new \SplFileInfo($crontabFilename);
      $this->logFilename = $logFilename;
      $this->config = $config;
    }

    /**
     * Cargamos la configuración del fichero de texto especificado
     */
    public function loadConfig(): self
    {
      
      $logFilename = $this->logFilename;

      //Guardamos el checksum
      $this->checksumFile = $this->generateChecksum();

      //Abrimos el fichero
      $file = $this->crontabFile->openFile('r');

      foreach($file as $line){

        if(empty($line)){
          continue;
        }

        list($expression, $cmd) = CrontabParser::parse($line);
        $this
          ->raw($cmd)
          ->at($expression)
          ->output($logFilename)
        ;
      }

      return $this;
    }

    /**
     * Creamos un deamon que se ejecuta infinitamente para ejecutar 
     * los comandos configurados cuando toquen
     * 
     * Si cambia el fichero de configuración el demonio lo detecta 
     * y carga la nueva configuracion
     * 
     * @param $tick int Indica cuantos segundos debe esperara para hacer la nueva comprobación
     */
    public function watch($tick = 60)
    {

      $scheduler = $this->schedulerFactory();

      while(1){

        //Comprobamos si el fichero ha cambiado
        if($scheduler->hasCrontabChanged()){

          printf('-- The crontab file has changed, the new rules are loaded --'.PHP_EOL);
          unset($scheduler);

          $scheduler = $this->schedulerFactory();
          $scheduler->run();
        }

        $scheduler->run();
        sleep($tick);
      }
    }

    /**
     * Me indica si el fichero ha cambiado
     */
    public function hasCrontabChanged(): bool
    {

      return $this->checksumFile !== $this->generateChecksum();
    }

    protected function generateChecksum()
    {
      return md5(file_get_contents($this->crontabFile->getRealPath()));
    }

    protected function schedulerFactory()
    {
      $scheduler = new self($this->crontabFile, $this->logFilename, $this->config);
      $scheduler->loadConfig();
      return $scheduler;
    }
}