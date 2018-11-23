Drupal 8 Module Development Notes

# About hooks
 - By default we use hooks only in the `*.module` file
 - Use short and concise DocBlocks
# About Routes
https://www.drupal.org/docs/8/api/routing-system/structure-of-routes
 - `path` key indicates the path we want this route to work on
 - `defaults` section defines the handler
 - we can use *Route variables* like `path: '/hello/{param}'` and/or `/hello/{node}`
# Namespaces

