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

Themes are registered with the theme system by modules and themes using the `hook_theme()`.
In D8 almost everything is outputted via a Twig template file.

## Drupal Core example:

```
function hook_theme($existing, $type, $theme, $path) {
  return [
    'item_list' => [
      'variables' => ['forums' => NULL, 'topics' => NULL, 'parents' => NULL, 'tid' => NULL, 'sortby' => NULL, 'forum_per_page' => NULL],
    ],
    'status_report' => [
      'render element' => 'requirements',
      'file' => 'system.admin.inc',
    ],
  ];
}
```

 - `item_list` will map using `variables` to the `item-list.html.twig` file
 - `status_report` will map using `render element` to the `status_report.html.twig` file

## Template Preprocessors

Modules and themes can propide template preprocessors

```
function template_preprocess_component_box(&$variables) {
 // Prepare variables.
}
```

in this case `component_box` will be the theme hook, and $variables is the data defined in the theme hook.

### Order of Template Preprocessors

1. Default one
2. Modules
3. Templates

### Overriding theme hooks

Modules and themes can override theme hooks using `hook_theme_registry_alter()`.



