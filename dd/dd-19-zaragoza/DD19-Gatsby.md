Drupal Day 2019

---
Charla 1 : Gatsby
--- 


# Gatsby by Antonio

 - Hacer sites indestructibles
 - Generador de sites **estáticos** usando React
 - Patrón PRPL
 - Funciona con todo tipo de motores de datos usando GraphQL
 - Tiene 1500 plugins en NPM
 - Conseguimos sites super rápido: 100 Lighthouse. Nos da un SPA instantaneo

# Que conseguis
 - Era de los componentes (React)
 - DX: Developer eXperience
 - HMR: Gatsby se da cuenta cuando hacer un cambio y automaticamente compila todo y puedes ver el resultado en la web
 - 3 a 5 veces trabajar mas rapido que con twig
 - Al usar Json, FE no necesita que BE esté terminado

# JAM STACK

- JAM es el stack con JS + APIS + Markup
- Markup puede ser JSON, Markdownn
- Esto es la Web que necesitamos, al FE solo le interesa que se devuelvan los da

# Desacoplar Drupal: La era de los componentes

- LAMP significa que hay que controlar un montón de movidas. Hay que lidiar con logs, etc.
- Drupal es un sistema ACOPLADO. Depende de muchos sistemas de los años 90.
- En React sólo usamos una función: Use-state. Lo demás es JS
- Es imposible hacer FE sin componentes.
- El FE de Drupal es anticuado
- La solución es desacoplar

Objetivo: Que Drupal vuelva a ser un CMS

# Ejemplo

- Antes de desacoplar:
 - 20 mil euros en servidores al mes AWS y no iba bien

- Depués de desacoplar:
 - Exportamos todo a JSON: Contenido, Taxonomías, Configuraciones, Locales
 - Reducción del coste a 90%
 - Sistema no destructible
 - S3 no tiene carpetas
 - Sin cortes en el servicio

# GraphQL

 - Acceso a datos sin necesidad de red.
 - de GraphQL se convierte automáticamente a JSON. 
 - Es super facil de modificar la estructura del JSON usando GraphQL.








