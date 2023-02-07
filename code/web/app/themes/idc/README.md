# Garrison Hughes WP Base Theme

The official Garrison Hughes base Wordpress theme. Powered by npm, Gulp and Bower. Best paired with the GH Base WP Stack or the GH WP Bedrock Base - but not necessary.

## Requirements

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Node.js 0.12.x  | `node -v`    | [nodejs.org](http://nodejs.org/) |
| Composer		  | `composer -v` | `curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer` |
| gulp >= 3.8.10  | `gulp -v`    | `npm install -g gulp` |
| Bower >= 1.3.12 | `bower -v`   | `npm install -g bower` |


## Setup

`cd` into the theme root, then run `npm install` and `bower install` to install all the dev dependencies.

...and that's it! You should be ready to rock! Just run `gulp` and start devv'ing!

## Features

* [Gulp](http://gulpjs.com/) build script that compiles LESS, checks for JavaScript errors, optimizes images, and concatenates and minifies files
* [BrowserSync](http://www.browsersync.io/) for keeping multiple browsers and devices synchronized while testing, along with injecting updated CSS and JS into your browser while you're developing
* [Bower](http://bower.io/) for front-end package management
* [asset-builder](https://github.com/austinpray/asset-builder) for the JSON file based asset pipeline
* [Theme wrapper](https://roots.io/sage/docs/theme-wrapper/)
* ARIA roles and microformats as well as semantic HTML5
* Cleaner output of `wp_head` and enqueued assets per page
* Security improvements in the Wordpress installation

## Theme Setup

There are a few prerequisites you want to make sure you have prior to working with this theme. If you have run the GH Interactive Setup script, you can skip this part as you already have these dependencies.

### Install gulp and Bower

Building the theme requires [node.js](http://nodejs.org/download/). We recommend you update to the latest version of npm: `npm install -g npm@latest`.

From the command line:

1. Install [gulp](http://gulpjs.com) and [Bower](http://bower.io/) globally with `npm install -g gulp bower`
2. Navigate to the theme directory, then run `npm install`
3. Run `bower install`

You now have all the necessary dependencies to run the build process.

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory as well as start watching for changes
* `gulp build` — Compile assets for production (no source maps) and without watching

### Using BrowserSync

To use BrowserSync during `gulp` you need to update `devUrl` at the bottom of `assets/manifest.json` to reflect your local development hostname.

For example, if your local development URL is `http://project-name.dev` you would update the file to read:

"config": {
    "devUrl": "http://project-name.dev"
  }

If you have never used BrowserSync, you will find it is pretty awesome.

## Development

The GH WP theme uses [gulp](http://gulpjs.com/) as its build system and [Bower](http://bower.io/) to manage front-end packages.

### Structure

Here is a breakdown of the theme file/directory structure and a little bit about each.

```shell
web/   							# → Root of the theme
├── bower_components/			# → Location Bower packages get installed to
├── data/						# → Any local data sources should live here
├── lang/						# → Language translations (.pot) files should live here
├── lib/
│   ├── vendor/          		# → Location Composer packages get installed to
│   └── theme/         			# → Directory that contains modular files that make up the functions.php
│   	  ├── assets.php     	# → Class that provides asset pipeline to asset-builder as well as enqueues styles and scripts
│   	  ├── custom.php     	# → Custom functions that pertain to the project
│		  └── rest.php    		# → For custom REST API routes if used in WP site. If none are used, delete this file.
│   	  ├── setup.php      	# → The main setup for the theme
│   	  └── walker.php    	# → The WP core class used to implement a tree like data structure for a navigation system.
│   	  └── wrapper.php    	# → The SageWrapper class written by the Roots guys (never edit)
├── partials/					# → Directory that contains partial templates. This is where your individual page markup should live
├── services/					# → Any data services should live here
├── src/                		# → Directory containing the source of all static assets
│   └── static/
│   	  ├── manifest.json     # → JSON object that tells asset-builder what to do
│   	  ├── fonts/            # → Theme fonts
│   	  ├── images/           # → Theme images
│   	  ├── scripts/          # → Theme JS
│   	  └── styles/           # → Theme LESS stylesheets
├── templates/            		# → All custom theme templates (that you would select in the CMS) should live here **REMOVE THIS?
├── archive.php         		# → Template file for Wordpress archive pages
├── .babelrc        			# → define which preset to use to complie JS in gulpfile.babel.js
├── base.php         			# → The base template file
├── bower.json         			# → The Bower JSON object. (try not to edit)
├── composer.json         		# → Composer file (never edit)
├── composer.lock         		# → Composer lock file (never edit)
├── dist/                 		# → Compiled static theme assets (never edit anything in here)
├── functions.php         		# → The actual functions file. Loads all files from `lib/theme/` here
├── index.php             		# → Never manually edit
├── gulpfile.babel.js         		# → Gulpfile. (do not edit)
├── node_modules/         		# → Node.js packages (never edit)
├── package.json          		# → Node.js dependencies and scripts
├── page.php         			# → Base template for the `page` post type
├── screenshot.png        		# → Theme screenshot for WP admin
└── search.php         			# → Template for a search results
└── single.php         			# → Template for a single post
```

### manifest.json

This setup uses an npm package called [asset-builder](https://github.com/austinpray/asset-builder) to manage all of the static assets during build. This is good for combining a handful of files together into 1 file (ex. jQuery plugins into 1 .js file. etc).

This is where you would add/create references to CSS files that should be created by the Gulp process.

This file lives at: `src/static/manifest.json` - edit at your leisure.

Gulp will also watch for changes in this file and rebuild as necessary.

### Gulp

Gulp is what drives the static portion of this theme. Gulp takes care of the build which includes:

* compiling LESS/js into `dist/static` directory based on paths of items in `manifest.json`
* minifying and concat'ing files
* running Browsersync and injecting updated files directly into the browser
* optimizing images

Just `cd` into this themes directory in your Wordpress website and run `gulp`.

### CSS/LESS

This template is set up to use LESS. Gulp will lint, compile and minify your styles as long as they are included in the `manifest.json` file so `asset-builder` knows to compile the streams.

Upon a LESS compile error, Gulp will spit out a Growl notification. Refer to your terminal for the error and go fix that shit!

### functions.php

Traditionally, everything is crammed into the functions.php file in the root of the theme. Often times this becomes unmanageable and very hard to read.

In our theme - the functions.php file is very modular and made up of several individual PHP files. These files live under the `lib/theme/` directory. Below is an explanation of what each file does:

* `wrapper.php`
	* This file was created by the Roots.io guys and controls the templating. *DO NOT EDIT THIS FILE*
* `setup.php`
	* use this file to enable or disable theme features, setup navigation menus, post thumbnail sizes, post formats, and sidebars.
* 'assets.php'
	* use this file to enqueue static assets for certain pages or posts (remember to use the `dist/` directory)
* 'custom.php'
	* use this file to make any project specific or additional customization that should appear in the functions.php file
* 'rest.php'
	* use this file to make any project specific or additional REST APTI customization(s). Delete if no REST APIs are needed.
* 'walker.php'
	* use this file to make use of WP core class for tree-like data structure of the navigation.

### Templates

The template structure is probably the most different part of this theme and may be a little confusing at first. But you'll find it works very well compared to traditional Wordpress templating and requires less code and dev time. The markup is handled by one file and not scattered across multiple files. The templates also include ARIA roles and microformats as well as semantic HTML5 baked in.

Here is the low down:

The GH WP theme is powered by a theme wrapper class written by the [Roots.io](https://roots.io/) guys. It is used in their theme - Sage (which is what this theme is inspired by). Written with the DRY (Don't Repeat Yourself) principle in mind, Wordpress template inheritance is done inside this class and allows us to write the least amount of code possible as well as keep everything very modular and tidy.

Wordpress is pretty smart. Everytime a request is loaded, it looks for the most relevant template available in the theme and load it. This is the [Template Hierarchy](http://codex.wordpress.org/Template_Hierarchy) in action and it enables us to easily customize our sites with minimal markup. Traditional Wordpress templates are WET (Write Everything Twice), things like `get_header`, `get_footer`, etc. are always repeated throughout the templates, along with basic structure of the page and everything.

The SageWrapper class allows us to put all the base page markup (`<head>`, `<body>`, default page structure, etc.) in 1 file that is used across all pages. This file is called `base.php`.

From there, `base.php` will include the `Wrapper` class and fetch the `template_path` of this page (by default, it will load the file `page.php`).

`page.php` contains "The Loop", and wraps the Loop around the actual page markup for this page.

The line `get_template_part('partials/content-page', $post->post_name);` will look in the `partials/` directory (where the page markup partial templates live) and look for the content for the page by it's slug, following a naming convention that is set as part of Wordpress' Template Hierarchy.

For example, a page with the slug of "dashboard" - the request would look like:

`get_template_part('partials/content-page', 'dashboard');`

and would look for a file in the `partials/` directory called `content-page-dashboard.php`

This file is where your custom individual page markup should live.

For a typical Wordpress website, you would only have to create new "content" partials if you wish to have any custom markup on particular pages. No more individually creating page templates, as they would all inherit from `base.php` and `page.php`

The same convention applies to individual posts `single.php` and archive pages `archive.php`. They all inherit from the `base.php` file, however Wordpress knows which template to grab.

### Data Providers

Any files that provide data streams (JSON, database type things, etc) should live in `data/` - just to keep things in their place.

### Web Services

Any PHP service scripts that have AJAX calls to them should live in `/services/` - just to keep things tidy.

## Notes

Have any notes? Please suggest to add them here!

## MK - Things to add/modify/remove

Add:
```shell
├── acf-json				# To ensure ACF blocks transfer with theme vs db
├── 404.php					# Not listed? Shouldn't we add it?
```