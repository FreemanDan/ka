## OpenGraph Meta

OpenGraph Meta (7.x-2.x) provides an UX improved form for editing OpenGraph metatags. In contrast to the 7.x-1.x it doesn't implement the storing and rendering of the metatags. It uses [metatag](https://www.drupal.org/project/metatag) for this purpose instead.

### Features

* The form for OpenGraph metatags is split into multiple fieldsets. Only the most common fields are displayed outside those (sub)fieldsets.
* You can choose an image from the node's content via radios instead of entering the URL manually. Of course manually entering a URL is still possible.

### Roadmap

* Migrate the content-type and global settings from 7.x-1.x to 7.x-2.x.
* Also find images in other fields and search in their filtered output.
* Make this module work with all types of entities.
