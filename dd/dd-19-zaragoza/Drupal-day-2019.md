# DD19 - Composer y Drupal
(Alberto Perez - Everis - Zaragoza)

# Dia a Dia

Hacer git pull y luego un composer update. Esto debería ser automático.

# Habilitar mas Timeout

```
./composer.phar config --global process-timeout 1800
```

# Versión oficial

Por ahora haciendo `composer create-project drupal-composer` no crea la versión oficial de drupal, pero se está trabajando en ello para que en el futuro si sea la oficial.

# Instalación de drupal

Con el comando anterior se crea una instancia de drupal. Esto con configura las siguientes carpetas:
 - config
 - drush 
 - vendor
 - web
 - Scripts/composer/ScriptHandler.php 
     - Sirve para por ejemplo cambiar permisos del sites/default/files
     - Sirve para CI (Integración Continua)

# Analizando composer.json

Al final del composer.json hay una llamada al Script Handler.









