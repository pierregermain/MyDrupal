# WSDL en los tiempos de REST
(Alejandro Arnau)

Proyecto Prisma

B2C
Business 2 Client (negocia hacia el cliente final)

# Dificultades

 - Integrar varias API's
 - Consultar API en tiempo real vs. cuando sólo sea necesario

# Pilares usados

 - Usar módulo Migrate
   - Despublicar automáticamente contenido.
   - Actualizar parcialmente cuando sólo necesitamos cierto contenido.
 - Integrar Api SOAP con Drupal 
 - Field API para añadir Fields estáticos vs. dinámicos

# Tipado Fuerte en D8

En D8 prácticamente todo es tipado fuerte.

Para pasar wsdl a drupal tenemos `wsdl2phpgenerator` que nos genera el código necesario en PHP para poder consumir wsdl's.

Esta gente va a sacar un modulo llamado WS Cliente




