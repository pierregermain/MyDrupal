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

# Theme Hook suggestions

Pattern:

```

base_theme_hook__some_context

```

We append the context to the theme hook!

The theme system checks:
 - 1. If there is a template file matching the suggestion inside a theme
 - 2. If there is a theme hook registered
 - 3. If there is a base theme hook (Fallback)
 
 ## Proposing suggestions
 
 It is the caller that proposes different kind of suggestions
 
 ```
return [
 '#theme' => 'item_list__my_list',
 '#items' => $items
];
 ```
 
 So in our case the base theme is `item_list` which renders `item-list.html.twig`.
 It there is *no* `item-list--my-list.html.twig` and no `item_list__my_list` theme hook is registered
 than the default `item_list` theme hook is used.
 
## Provide suggestions from a module

A module that registers a theme hook can also provide a list of suggestions by implementing:

```
hook_theme_suggestions_HOOK() 
```

where HOOK is the theme hook name.

We can provide a list of theme hook suggestions by implementing:

```
hook_theme_suggestions_HOOK_alter() 
```

# Render Arrays

Definition:
```
A render array is a structured array that provides data (probably nested) 
along with hints as to how it should be rendered (properties, like #type).
```
 - they allow to delay the rendering
 - we no longer have to/should render anything manually
 - Drupal will know how to turn them into markup
 
 
## The structure of a render array

Render arrays are rendered by the `renderer` service which recursively renders each level. 
The properties have a `#` sign whereas children not.
Each level needs to have al least one property.
Mandatory properties are the following:

```
#type
#theme
#markup
```

### #type

This property specifies the type of *render element* to be used. There are 2 types that we can use:
 - generic
 - form input: This is more complex, it is used for forms. You will need to deal with validation and other aspects.

### #theme

It specifies that the render array needs to render some kind of data using one of the theme hooks defined.

### #markup

You can use this property to directly output the markup.

Example: The #markup property takes the simplest render array you will ever see.

```
return [
  '#markup' => $this->t('Hello World')
];
```
### Other Properties

The `#plain_text` property is similar to the `#markup# porperty: You can output simple text with it.
 
## The render pipeline





