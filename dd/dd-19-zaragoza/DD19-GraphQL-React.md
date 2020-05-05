# Drupal y GraphQL y React. Y si GraphQL manda sobre Drupal ?
()

# Requisito del cliente:
 - CMS
 - UX
 - APIs

# Drupal + REACT

 - Drupal desacoplado

Drupal ---> Repo ---> Gatsby 
  |
  |
  ----> CMS


 - Drupal desacoplado

Drupal <---> Json / Api GraphQL <---> React
  |
  |
 ---> CMS



# 2 mundos

 - dri.es: json/api
 - Philipp amazeelabs: GraphQL

# GraphQL

 - Gracias a GraphQL podemos trabajar de forma facil con Drupal.
 - Esta creado con fb.
 - Usado por grandes corporaciones.
 - Está cómo módulo contrib.

# Voyager

Hace toda la relación E-R de la DB.

# GraphiQL

Para hacer las queries contra la DB

Con "Ctr+Enter" puedo autocompletar las queries.

Esta super increible la manera tan facil de hacer queries contra la DB de esta forma!!!!

Esta herramienta se la pasas al FE, y el tío te hace maravillas!

# Custom Code

 - POdemos hacer nuevas funcionalidad, por ejemplo exponer una api para que desde fuera se puede dar de alta un nuevo nodo
  - Hay que saber las anotaciones de GraphQL

```
@GraphQLField(
id = getUsers
name = Array
``` 

 - CRUD se puede usar, pero te lo tienes que programar.


# Twig

Puedpo ejecutar queries desde Twig en GraphQL

Ficheros: gql


# Webpack

El FE no necesita levantar drupal para hacer pruebas. Nada mas habilita su webpack y storyboard y listo

