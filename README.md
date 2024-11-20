# Test Render

This example Drupal module provides a few examples of render caching
techniques.

To see it in action, just enable it and visit the various routes it defines.
For a more complete experience, visit the routes both as an authenticated user
and as an anonymous ones. For a full experience, enable the `big_pipe` and
[big_pipe_sessionless](https://www.drupal.org/project/big_pipe_sessionless)
modules.

Make sure you are using the database cache backend and inspect the following
cache tables:

* `cache_dynamic_page_cache`
* `cache_page`
* `cache_render`
