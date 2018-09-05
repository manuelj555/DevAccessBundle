DevAccessBundle
=====

Este bundle permite proteger el accesso a **app_dev.php** en los proyectos Symfony, con la finalidad de que solo usuarios autorizados puedan acceder por este medio.

**NOTA** El proyecto se ha configurado para funcionar en Symfony 2.8 y Symfony 3.

Instalación
---

Ejecutar

    composer require manuelj555/dev-access-bundle ~0.1@dev
  
Registrar el Bundle en el AppKernel:

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Manuel\Bundle\DevAccessBundle\DevAccessBundle(),
    );
    
    ...
}
```

En el **app/config/routing.yml** agregar:

```yaml

dev_access:
    resource: "@DevAccessBundle/Controller/"
    type:     annotation
    prefix:   /admin
``` 

Y por último agregar ajustar el archivo app_dev.php de la siguiente manera:

```php
...
// Comentar las lineas que verifican el acceso local
//if (isset($_SERVER['HTTP_CLIENT_IP'])
//    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
//    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true) || PHP_SAPI === 'cli-server')
//) {
//    header('HTTP/1.0 403 Forbidden');
//    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
//}

// y ahgregar debajo de la carga del autoload lo siguiente:
require __DIR__.'/../vendor/autoload.php';
Manuel\Bundle\DevAccessBundle\Security\Access::check(__DIR__.'/var/cache'); // Agregar esta linea

Debug::enable();
```

y con eso ya el bundle ha quedado configurado:


Ruta de configuración de acceso
----

Para administrar los accesos se debe acceder a:

    /admin/dev-access/config
  
Configuración adicional
----


```yaml
dev_access:
    roles:       # Roles con acceso al modulo de administracion
        - ROLE_ADMIN
    users:   # Usuarios especificos con acceso al modulo de administracion
        - admin
    sessions_path: '%kernel.project_dir%/var/'   # Ruta donde se van a generar los archivos de sesion
    environment: dev         # entorno en el que se está trabajando con acceso restringido
``` 

Importante
===

En el app_dev.php al añadir la linea `Manuel\Bundle\DevAccessBundle\Security\Access::check(__DIR__.'/var/cache');` la ruta pasada como argumento debe ser la misma que la colocada en la configuracion del bundle.
    
