# Witrac - Prueba técnica nº1
###Implement a CRON replacement. The application must be able to take a configuration file (crontab style) and run commands on demand.

Para realizar esta prueba he decidido utilizar el lenguaje PHP combinado con PHPUnit para el testeo del código.

En la parte frontend he utilizado el framework bootstrap y javascript y ajax para validación y envío de los datos del formulario.

El motivo de utilizar esta pila tecnológica simplemente es porque es la que más domino y más experiencia tengo.

##1. Instalación
----------------
El código del proyecto se encuentra en github. Solamente hay que clonarlo y hacer una instalación muy sencilla que se explica a continuación:

- Clonar el proyecto
`git clone https://github.com/adagber/cron-php.git && cd cron-php`


- Instalar las dependencias con *composer*. Si no tienes composer instalado puedes ver la [guía de instalación de composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos).
`composer install`

- Crear el directorio *var* dónde se va a crear ficheros y hacer una copia del fichero de distribución de crontab
`mkdir var && cp crontab.txt.dist var/crontab.txt`

- (opcional) Para no tener problemas con los permisos puedes dar acceso de escritura a los ficheros de *var*
`chmod +w var/*`

- Si se quiere tener frontend para editar los crontabs hay que configurar tu servidor web para que apunte a la carpeta *public* del proyecto

¡Y eso es todo!

Una vez lo tengamos todo listo y configurado vamos a ver cómo funciona todo esto.

##2. Cómo funciona
-------------------

Para que las tareas del crontab se ejecuten hay que activar el cron para ello tenemos dos opciones:

**2.1 Poner en el crontab del sistema el comando que gestiona las tareas**

El proyecto dispone de un comando que cuando se ejecuta mira todas las tareas que están por salir y las ejecuta.

Este comando hay que ejecutarlo periódicamente cada minuto.

Para ello añade a tu crontab la siguente linea:
`* * * * * path/to/phpbin path/to/php-cron-project/bin/cron 1>> /dev/null 2>&1`

¡Y listo! Fácil, verdad.

¿Pero qué ocurre si en nuestro servidor **no** tenemos acceso al crontab del sistema?. 

Creo que la gracia de este desafío es montar uno sin la necesidad de utilizar el de Linux.

Pues estás de suerte. Sigue leyendo 😉.

**2.2 Ejecutar el propio demonio de php-cron**

Para activarlo solo tienes que ejecutar el siguiente comando:
`php bin/watch`

Esto ejecuta un servicio que carga todas las reglas (crontab) establecidas (que veremos después) y se queda pendiente a ejecutar cada una de las tareas que le toque cada momento.

Para detenerlo solo tienes que pulsar `Crtl + C`

##3. Estableciendo las tareas
-----------------------------

En linux mendiante el comando *crontab* accedemos a un fichero de texto para establecer la planificación de las tareas.

En php-cron este fichero se encuentra en *var/crontab.txt* ¿Te acuerdas que lo copiaste al principio en la instalación? Pues si lo abres verás que tienes una configuración demo de tareas como las del crontab de toda la vida. De echo el formato es el mismo.

Puedes editar este fichero manualmente y todas tus reglas serán cargadas automáticamente.

Pero... ¿y si utilizo el demonio de php-cron, entonces cuándo haga algún cambio debo cancelarlo y volverlo a lanzar?

La respuesta es **NO**. El demonio php-cron es lo suficientemente inteligente para que detecte los cambios en el fichero y vuelva a cargar las nuevas tareas.

Cuando hay cambios verás en la consola del demonio un mensaje como este:
`-- The crontab file has changed, the new rules are loaded --`

##4. Usando el frontend
-----------------------

Vale. Esto está muy bien. Pero, ¿qué ocurre si quiero cambiar el fichero crontab sin editar el fichero?

No hay problema. Para esto tienes el frontend. Un pequeño acceso vía web donde podrás modificar el crontab con un sencillo formulario.

Para utilizarlo deberás configurar algún servidor web y que apunte al directorio */public* tal y como se explicó en el proceso de instalación.

Después solo tienes que ir al navegador y usar la dirección URL. En mi caso es ésta:
`https://127.0.0.1:8000/`

##5. Viendo la salida (output)
------------------------------

Cualquier cosa que salga por la salida estándar de las tareas ejecutadas, se guarda en un log que se encuentra en *var/output.log*. Este log solo guarda la salida de la última tarea ejecutada, así que no tienes que preocuparte por si crece el fichero demasiado.

##6. Test
---------

La aplicación dispone de varios test para darle fiabilidad y escalabilidad al proyecto.

Se ha utilizado la librería de *PHPUnit 9.3*

Para ejecutar los test solo hay que escribir lo siguiente desde la raíz del proyecto:
`vendor/bin/phpunit`

##7. Conclusiones
-----------------

Y esto ha sido todo.

Creo que la prueba tiene un poco de todo y así os podéis hacer una idea de mi potencial. 

Evidentemente ésto no es una solución seria para llevarla a un sistema en producción. Creo que el objetivo no era ese, sino mostrar un poco las habilidades de programación tanto *backend*  como *frontend* y un poco de programación en terminal y acabando por los test unitarios.

Con todo esto creo que mi solución a este desafío es una solución muy completa y con un poco de todo.

¡Espero que os guste!



