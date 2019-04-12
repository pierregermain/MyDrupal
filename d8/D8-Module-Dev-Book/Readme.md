# Drupal 8 Module Development Notes

#T4 Theming

[TOC GENERATE](https://magnetikonline.github.io/markdown-toc-generate/)

(...)

# Introduction

`/admin/appearance`

Every output we make has to be marked up with HTMl and CSS (and that is theming).

# Business logic versus presentation logic

A module has its responsibility to provide its default theme implementation (using a *theme hook*). 
That's the initial look and feel that is independent of design but that should be displayed correctly, 
regardless of the theme. 
However, as long as the module uses the theme system properly, 
a theme will be able to override (*overriding*) any HTML and/or CSS by swapping the modules implementation with its own.

# Twig

D7: PHPTemplate: `*.tpl.php`  files
D8: Twig: `*.html.twig` files

# Theme hooks


