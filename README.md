# Exec plugin for CakePHP

exec model method in console

    $ cake exec.exec Model Post delete 3

exec() model method in method

    <?php
      App::use('Exec', 'Exec.Lib');
      Exec.exec('Model', 'Post', 'delete', 3);
