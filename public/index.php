<?php
//Obtenemos el crontab
require dirname(__DIR__).'/app/files.php';
$content = @file_get_contents($cronFilename);
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Witrac/php-cron</title>
  </head>
  <body>
  <div class="container">
  <div class="py-5 text-center">
    <img class="d-block mx-auto mb-4" src="https://z2d7q2g2.rocketcdn.me/wp-content/uploads/2017/10/witrac.png" alt="" width="72" height="72">
    <h2>Witrac/php-cron</h2>
    <p class="lead">Implement a CRON replacement. The application must be able to take a configuration file (crontab style) and run commands on demand.</p>
  </div>

  <div class="row">
    <div class="col-md-8 offset-2">
      <h4 class="mb-3">Crontab</h4>
      <form id="form">
        <div class="form-group">
          <label for="rules">Escribe tus tareas de cron en el siguiente campo:</label>
          <textarea class="form-control" id="rules" rows="15" require="require" name="rules">
<?php echo trim($content) ?>
          </textarea>
        </div>
        <hr class="mb-4">
        <button id="submit" class="btn btn-primary btn-lg btn-block" type="button">Guardar</button>
      </form>
    </div>
  </div>

  <footer class="my-5 pt-5 text-muted text-center text-small">
    <p class="mb-1">&copy; 2017-2020 Witrac</p>
  </footer>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
      window.addEventListener("load", () => {
        
        const button = document.getElementById('submit');
        const form   = document.getElementById('form');

        button.addEventListener('click', () => {
          
          const url = '/save.php';
          const initialValue = button.innerHTML;

          //Hacemos la peticion
          button.classList.add('loading');
          button.innerHTML =' <small>Saving...</small>';
          fetch(url, {

            method: 'POST',
            body: new FormData(form)
          }).then(response => {
            
            if(!response.ok){
              
              throw Error('Invalid data');
            }
            return response;
          })
          .then(data => {
            Swal.fire({
              title: 'Perfeto!',
              text: 'Su crontab se ha guardado correctamente',
              icon: 'success',
              confirmButtonText: 'Cool'
            })
          }).catch((err) => {

            Swal.fire({
              title: 'Error!',
              text: 'Las reglas del crontab no están bien definidas. Por favor revísalas!',
              icon: 'error',
              confirmButtonText: 'Cool'
            })
          }).finally(() => {
            
            button.innerHTML = initialValue;
            button.classList.remove('loading');
          });
        })
      });
    </script>
  </body>
</html>