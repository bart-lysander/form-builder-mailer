# Module: Form Builder Mailer

Form Builder Mailer is a module for [Fork CMS](http://www.fork-cms.com).
It e-mails the end user a confirmation message after a form has been submitted.
For recipient mail address, it uses the first valid email value from the form.

## Dependenties

This module depends on [the form_builder module](http://www.fork-cms.com/extensions/detail/form-builder). Make sure it is installed.

## Versions

* Version 2.x.x will work on ForkCMS 3.7.x (not ready yet)
* Version 1.x.x will work on ForkCMS 3.6.x

## Download

Download latest release from [the release page](https://github.com/bart-lysander/form-builder-mailer/releases).

## Installation

Visit the [Fork CMS Documentation](http://www.fork-cms.com/community/documentation/detail/getting-started/adding-modules) to learn how to install a module.

## Features

* You can enable/disable the mailer using the module settings page.
* You can enable/disable logging to the defailt log handler using the module settings page. (default: %site.path_www%/app/logs/logs.log)
* You can enable/disable adding an extra form data field using the module settings page. You can see this field in the Formbuilder data details or export. It shows the email address uses to send a notification and a short status.

## Improvements

* Need to add other [languages than Dutch and English] (http://www.fork-cms.com/community/documentation/detail/module-guide/translations-or-locale)

## Trouble shoot

If it does not work, what can I check?

* Check if the module is enabled (settings)
* Check if the form has an email field for the end users email address
* Enable the log option in settings, submit a form and check the log file (default: %site.path_www%/app/logs/logs.log)

## Support

E-mail: bart@webleads.nl
