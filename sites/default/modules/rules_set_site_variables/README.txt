Rules Set Site Variables | README

"Rules Set Site Variables” allows you to use the power of Rules to change site configuration and site variables. Create a rule or rules action and then add the action Set Drupal Site variable. You can configure which site variable you want to modify and text you want to change or modify.

Official Project Page: https://www.drupal.org/project/rules_set_site_variables

## How to Use?

1. Download and Enable "Rules Set Site Variables” (as well as Rules and any other modules you need)
2. Create a Rule using some kind of Reaction Event (and add any additional conditions)
3. Alternatively create an Action Component
4. After, create an Action called **"Set Drupal Site variable”** under “System”
5. Select the site variable you want to set.
6. Add the text (or use a Replacement Pattern) to set that variable.
7. Prosper.

## What is this module for?

This module for lazy site builders that want to use the power of Rules to modify site configuration in various ways. In my particular use case, I’m using Rules Scheduler to set rules to change certain site properties at different intervals. You can use this module to change site configuration according to certain events, conditions or whatever you can imagine.

Obviously changing your site configuration in this way can do some bad things to your site, so be smart and use with caution. This module is not responsible for any mistakes you make in using it.
