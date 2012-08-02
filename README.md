dreamedframework
================

Dreamed Framework

Con PHP

Ideas
-----
- Los módulos se colocan en modules/

- Cada módulo es un directorio que contiene un archivo module.php

- El nombre del directorio de un módulo es de la forma dd-mmm 
  dd es un número
  mmm es el nombre del módulo

- root es el módulo base.

- hook('hookname', $data) permite que todos los módulos puedan modificar $data.
  Los módulos son consultados en el orden alfabético de sus directorios (dd permite manipular el orden de los módulos).

- Si un módulo mmm quiere participar del hookname, implementa mmm_hookname(), que devuelve un array de pares key => action.
  key sirve para seleccionar un action.
  action es un callback al que se le pasa $data.

- $data['qp'] = getqp(base_dir() . '/index.html');
  Permite establecer index.html como template.
  Cualquier módulo puede alterar esto.