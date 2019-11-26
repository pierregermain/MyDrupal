# CI/CD
(Juampynr - Lullabot)

Continuous Delivery y Deployment hay que salir fuera de Drupal para verlo!

Los libros al respecto son cómo novelas.

- Libro: The Goal (años 80). Tienes 3 meses para mejorar la planta, sino cerramos. Da igual si tienes un proceso muy lento ese proceso va hacer que todo el proceso vaya lento.

Muchas libros de Devops hacen referencia a ese libro llamado `The Goal`.

Todo es in INPUT -> Proceso -> OUTPUT

# Proceso

Integration > Delivery > Deployment

 - Los Commits son Integration
 - Los Release es Delivery
 - Deployment es pasar a Producción.

CI Es hacerlo automáticamente

Fb, Netflix, Google hacen eso porque su negocio lo obliga a saco!

Muchos negocios no necesitan eso. Pero que cosa mala haya que no se pase cada día a Prouducción ? Esto es algo mas de filosofía que otra cosa.

Trabajar de esta forma practicamente no necesitas entorno de desarrollo.

# Comparación CI Tools


Circle CI (mola)(no se si es libre)
Travis CI
Gitlab CI (permite todo, excepto conectarte por ssh)(es libre)
Github Actions
Jenkins (muy complicado de configurar)

Me gusta Circle CI y Gitlab CI. 
Mola Gitlab porque permite todo todo todo y es super libre.

# Continuos Delivery - Tools

- DDEV - muy bueno para debuggear

# CD : Infraestructura as Code

 - Vagrant
 - Ansible
 - Terraform
 - Docker

Facil experimentar, nada mas cambias ficherito, y al hacer deploy lo pruebas, y listo!!!!

Tipos de estructuras:
 - VM
 - Containers
 - Serverless (lambda)

# CD - Evolutionary Architecture

Lo mas chungo es cambiar la mentalidad, lo mas jodido es subir las cosas a prod, aunque no se haga nada. Hay que subir poquito a poco, no pasa nada, no hay que tener miedo.

Subes el codigo, y puedes ir probando el codigo poco a poco con pocos usuarios, eso es lo que hacen los de facebook por ejemplo.

# Estrangular una aplicación

Ir quitandole cosas a la aplicación hasta que se quede sin funcionalidad. Las funcionalidades las vamos pasando a la nueva arquitectura. Hacemos todo poco a poco.

# Zero Downtime: Es posible en Drupal 8?

# Trunk Base Development

- Ir siempre contra master
- Una rama no debe perdurar mas de uno o dos dias








