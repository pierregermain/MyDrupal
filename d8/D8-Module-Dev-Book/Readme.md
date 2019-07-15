# Drupal 8 Module Development Notes

# T4 Theming

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

We have 2 render pipelines: 
 1. Symfony render pipeline
 2. Drupal render pipeline
 
Drupal 8 uses many Symfony components:
 - HTTPKernel component to turn a user request into a response object
 - Even Dispatcher to dispatch events

Controllers in Drupal 8 can return 2 things:
 1. Response objects
 2. Render arrays
 
In the case of response objects, nothing has to be done, but in the case of render arrays it will be transformed into a response object traversing a lot of drupal layers.
For that purpose it will use information of the render array, and each layer sometimes adds new information to that array until we get an actual response object.

Once the process is finished an actual render array that can be transformed to HTML will be created.

## Assets and libraries

Working with CSS and JS files is done via the concepts of Libraries. 

3 steps to make this happen:
 1. create your CSS/JS file
 2. create a library that includes them
 3. attach that library to a render array
 
### Libraries
 
#### Example 1
 
To define a library we can create the following file: `module_name.libraries.yml`
```
my-library:
  version: 1.x
  css:
    theme:
      css/my_library.css: {}
  js:
    js/my_library.js: {}
``` 

where `{}` is for the advances options, and `theme` is defined by SMACSS:
  - base
  - layout
  - component
  - state
  - theme

where the latter will be included last.


#### Example 2: CDN

```
angular.angularjs:
 remote: https://github.com/angular/angular.js
 (...)
 js:
   https://(...)/angular.js: {type: external, minified: true}
```

For external libraries more metadata is needed.

#### Example 3: Add a dependency

For example to add the jquery dependency:

```
dependencies:
  - core/jquery
```

### Attaching libraries

Common methods:

1. Attaching them to your render array

```
return [
  '#theme' => 'some_theme_hook',
  '#some_variable' => '$some_variable',
  '#attached' => [
    'library' => [
      'my_module/my-library',
    ],
  ],
];
```

2. Attaching the library to the entire page using `hook_page_attachments();`

```
function hook_page_attachments(array $attachments) {
  $attachments['#attached']['library'][] = 'my_module/my-library';
}
```

3. Using a preprocess function

```
function my_module_preprocess_theme_hook(&$variables) {
  $variables['#attached']['library'][] = 'my_module/my-library';
}
```

# Common Theme Hooks

## Lists

- One of the most common HTML constructs are lists (ordered or unordered).
- Drupal has always had the `item_list` theme hook:
  - Defined in the `drupal_common_theme()`
  - Preprocessed in [`template_preprocess_item_list()`](https://api.drupal.org/api/drupal/core%21includes%21theme.inc/function/template_preprocess_item_list/8.2.x)
  - Uses `item-list.html.twig` template
  - Has no default theme suggestions.


Imagine you want to show in an `<ul>` the following items:

```
$items = [
  'item 1',
  'item 2',
];
```

So you would use the following render array:

```
return [
  '#theme' => 'item_list',
  '#items' => $items,
];
```

Keep in mind that:
 - If you want to use a `ol` then use the `'#list_type' => 'ol'` variable.
 - If you want to have a title then use `'#title' => 'My title'`

## Links

Important Information:
  - Preprocessed in [`template_preprocess_links()`](https://api.drupal.org/api/drupal/core%21includes%21theme.inc/function/template_preprocess_links/8.8.x)
  - Uses `links.html.twig` template.
  
Example:

```
$links = [
  [
    'title' => 'Link 1'
    'url' => Url::fromRoute('<front>'),
  ],
  [
    'title' => 'Link 2'
    'url' => Url::fromRoute('hello_world.hello'),
  ],
];

return [
  '#theme' => 'links',
  '#links' => $links,
  '#set_active_class' => true,
];
```

where:
 - `set_active_class` is to make present `is-active` class on the home page when rendered on the home page

## Tables

Important Information:
  - Preprocessed in [`template_preprocess_table()`](https://api.drupal.org/api/drupal/core%21includes%21theme.inc/function/template_preprocess_table/8.8.x)
  - The most important variables are
    - `header`
    - `rows`
    
Example:
```
$header = ['Column 1','Column 2'];
$rows = [
  ['Row 1'],['Row 1'],
  ['Row 2'],['Row 2'],
];

return [
  '#theme' => 'table',
  '#header' => $header,
  '#rows' => $rows,
];
```

# Attributes

Most theme hooks have attributes, usually the variable is called `$attributes` or  `$wrapper_attributes`.

Example:

```
$attributes = [
  'id' => 'my-id',
  'class' => ['class-one','class-two'],
  'data-custom' => 'my custom data value',
\;
```

# Theming our Hello World module (and using suggestions)

Example: `30-hello_world-theming`
Route: `/hello-world-component`

We want to wrap our own markup to the `HelloWorldController` output.

We need this output
```
<div class="salutation> Good Morning <span class="salutation--target"> world</span></div>
```

So we implement hook_theme()

```
function hello_world_theme($existing, $type, $theme, $path) {
  return [
    'hello_world_salutation' => [
      'variables' => ['salutation' => NULL, 'target' => NULL, 'overridden' => FALSE],
    ],
  ];
}
```

By default this theme hook will look for Twig file with the name `hello-world-salutation.html.twig` inside the `/templates` folder.
So we create that file

```
<div {{ attributes }}>
  {{ salutation }}
  {% if target %}
    <span class="salutation--taget">{{ target }}</span>
  {% endif %}
</div>
```

Keep in mind that the `attributes` array is already predefined in each theme hook and will be converted to the `Attributes` object.

If we want to pass information to the `Attribute` object we can do so from the preprocessor:

```
function template_preprocess_hello_world_salutation(&$variables) {
  $variables['attributes'] = [
    'class' => ['salutation'],
  ];
}
```

We want also add a **suggestion** to our theme hook, so that we use an other twig file when the boolean value 'overridden' is TRUE

```
/**
 * Implements hook_theme_suggestions_HOOK().
 */
function hello_world_theme_suggestions_hello_world_salutation(array $variables) {
  $suggestions = [];
  
  if ($variables['overridden'] == TRUE){
    $suggestions[] = 'hello_world_salutation__overridden';
  }
  
  return $suggestions;
}
```

Don't be confused by the use of the word "hook" two times in naming this function. The first "hook" should be replaced with the name of your theme or module. The latter "HOOK" should be replaced with the base name of the template file for which you're suggesting alternatives.

Now we can have different templates that render our message:
```
hello-world-salutation.html.twig
hello-world-salutation--overridden.html.twig
```

## Using our hook


We create a new service called `getSalutationComponent()` on our service class `/src/HelloWorldSalutation`.
We also modify our controller `HelloWorldController` to return the new render array created with a new method called
`public function helloWorldComponent()`
We add a new route to test this method: `/hello-world-component`

Link to test: 
http://my-drupal.loc/hello-world-component

TODO: Use suggestions
https://api.drupal.org/api/drupal/core!lib!Drupal!Core!Render!theme.api.php/group/themeable/8.2.x#sec_overriding_theme_hooks
https://drupalize.me/tutorial/discover-existing-theme-hook-suggestions?p=2512

