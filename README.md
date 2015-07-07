Módulo de Mesas de Restaurante
===============================

## Colaboradores

- Aldo Culquicondor
- Alejandro Delgado
- Rudy Godoy
- Paulo Ramirez

## Instalación

1. Instalar el driver de mongodb y añadirlo a `php.ini`

        extension=mongo.so
    
2. Instalar composer

        curl -sS https://getcomposer.org/installer | php
    
3. Actualizar dependencias

        php composer.phar update

4. Configurar proyecto

        cp app/config/parameters.yml.dist app/config/parameters.yml
        vi app/config/parameters.yml
        
5. Configurar frontend

        php app/console assets:install --symlink
        npm install
        bower install
        grunt
        
    Si se quiere desplegar los archivos `js`:
    
        grunt debug
        
        
## Ejecución de Pruebas

1. Copiar configuración de PHPUnit

        cp app/phpunit.xml.dist app/phpunit.xml
        
2. (Opcional) Para obtener cobertura:

    a. Instalar xdebug (varía según el sistema operativo)
    
    b. Activar la extensión de xdebug en `php.ini`
    
3. Obtener ejecutable de PHPUnit

        wget https://phar.phpunit.de/phpunit.phar

4. Ejecutar pruebas

        php phpunit.phar -c app
 
Opcionalmente, es posible
[configurar PHPUnit en PhpStorm](https://confluence.jetbrains.com/display/PhpStorm/Debugging,+Profiling+and+Testing+Symfony2+-+Symfony+Development+using+PhpStorm#Debugging%2CProfilingandTestingSymfony2-SymfonyDevelopmentusingPhpStorm-UnitTestingSymfony2).
PhpStorm trae el ejecutable de phpunit embebido.

## Estándares de desarrollo

### IDE

Por cuestiones de uniformidad emplearemos [PhpStorm](https://www.jetbrains.com/phpstorm/)

### Identación

Indentación a 4 espacios. No tabs.

### ORM

Los documentos (entidades) serán mapeadas con el ODM de Doctrine usando YAML

