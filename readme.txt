=== Account Manager ===
Contributors: signpostmarv
Tags: MozLabs, Firefox, amcd
Requires at least: 2.6
Tested up to: 2.9.2
Stable tag: 0.1

Adds support for the Mozilla Labs "Account Manager" to WordPress

== Description ==

This plugin provides the Mozilla Labs [Account Manager](https://mozillalabs.com/blog/2010/03/account-manager/) prototype with the data it needs to allow Firefox to log the user in and out of a WordPress blog.

When a post or page hasn't been published, pressing Ctrl-S on your keyboard will save the page/post as draft, if it has been published, then it will be updated.

To extend the data presented in the AMC Document, play with the provided `mozlabs_amcd` filter.

== Installation ==

1. Upload the `account-manager` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== To-Do ==
1. Add support for bbPress

== ChangeLog ==

0.1 (2010-04-28)
------------------
1. Initial release
1. Features a workaround for the current version of the FF extension not supporting GET for the disconnect operation (this workaround will be removed once this bug in the extension has been fixed)