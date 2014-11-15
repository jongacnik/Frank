# Frank

Frank is a super-micro flat-file cms(ish) in a single [PHP](http://php.net/) file for basic websites.

## Overview

Frank takes cues from flat-file content management systems like [Kirby](http://getkirby.com/) and [Stacey](https://github.com/kolber/stacey) but purposefully does excruciatingly less. It's probably most useful for building single-page websites, though it could easily be combined with a router to build more complex things.

## Getting Started

Your content directory will be turned into a useful object. A single `site()` method is exposed for accessing this object:

	# app.php
	require('frank.php');
	$frank = new Frank(); // optionally pass in path to your content
	
	print_r($frank->site()); // site object
	
To install, download Frank and place it into your project. Create a content directory (by default Frank will look for this parallel to itself in `./content`, but you can name this anything and place this anywhere) and add some content.

## Structuring Content

Your content directory can be organized basically any way you like:

	# Example Directory Structure
	
	content/
		1-projects/
			1-projectA/
				1.jpg
				2.jpg
				info.json
			2-projectB/
				1.jpg
				2.jpg
				info.json
			projectC/
				1.jpg
				info.json
		2-about/
			info.json
			
The Frank site object for that directory would look like:

	# Example Site Object
	
	stdClass Object (
		[projects] => stdClass Object (
			[projectA] => stdClass Object (
				[images] => Array (
					[0] => ./content/1-projects/1-projectA/1.jpg
					[1] => ./content/1-projects/1-projectA/2.jpg
				)
				[info] => stdClass Object (
					[name] => Project A
				)
			)
			[projectB] => stdClass Object (
				[images] => Array (
					[0] => ./content/1-projects/2-projectB/1.jpg
					[1] => ./content/1-projects/2-projectB/2.jpg
				)
				[info] => stdClass Object (
					[name] => Project B
				)
			)
		)
		[about] => stdClass Object (
			[info] => stdClass Object (
				[about] => Some about text.
			)
		)
	)

#### Directories

Directories should be named with url-safe characters. Directories are arranged by a numerical index prepended to the directory name. If a directory name is not prepended with an index, Frank will ignore the directory entirely. This is useful for super quick deactivation of data.

#### Images

Any images within a directory are placed into an `array() images`

#### Info

An info.json file within a directory is decoded and the results placed into an `array() info`

#### Files

Any additional files within a directory are placed into an `array() files`

## Notes

Frank can, should, and probably will, be made more robust. For the moment, you should name your directories with slug friendly characters and be aware that certain files could break Frank entirely.  Drop bugs or suggestions into Issues, if you'd like. 