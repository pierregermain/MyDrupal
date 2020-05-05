# Patternlab

 - Atomic Design
 - Es muy estable

 - Permite trabajar con themes realizados por patternlab sin realizar adaptaciones para drupal.

```
Diseño <----> Patternlab <-----> Drupal
```

 - Es posible usarlo con Drupal Desacoplado y Acoplado


# Construcción

Todo lo metemos a la carpeta source/patterns

Ahí tenemos los atomos, moleculas, etc.

Cada elemento le aplicamos un css

# Reutilizar

De esta forma SI podemos reutilizar!

Lo interesante es que en cada componente metemos: Twig, json, css, js, sass ... todo lo que necesitamos para ese componente. En el json va el test mock para que FE tenga datos.

# Trabajo del FE

En un lado ve todos los componentes, en toro el markup, y el resultado, todo a la vez!!!

Todo eso dentro de una aplicación Node.

La idea es currar en dos pantallas: En una pantalla modificas los fuentes, y en otra tu app de node js va mostrando los cambios de todos los componentes

# Y Drupal ?

En Drupal debemos sobreescribir los templates haciendo includes a los componentes de patternlab.

El modulo components hace que la importanción sea mas automatizada o facil.

La idea es que el nombre de patternlab sea el mismo que el de drupal para las componentes.

Si no coinciden podemos cambiar el nombre con el preprocess.

# Cuando no usarse

 - si los FE son muy drupaleros
 - proyectos tipo intranet
 - SPA

# Cuando SI usarse
 - Cuando hay mucho curro de html y css
 - Si FE no es drupalero
 - Si hay experiencia con diseño atómico
 - Si nos parece que los templates de drupal no nos sirven o tienen demasiada información.


# Pregunta: Que tal si se usa sólo para algunos componentes ?

No recomendable aplicar a proyectos ya existentes: Mejor usar en proyectos nuevos.




