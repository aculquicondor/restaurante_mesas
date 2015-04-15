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

## Estándares de desarrollo

### IDE

Por cuestiones de uniformidad emplearemos [PHPStorm](https://www.jetbrains.com/phpstorm/)

### Identación

Indentación a 4 espacios. No tabs.

### ORM

Los documentos (entidades) serán mapeadas con el ODM de Doctrine usando YAML

