# Generative Images for Wordpress posts

## TL;DR
This is highly cowboy-coded plugin I created to enable me to manipulate all of my post images. The premise was to be able to automatically alter ALL posts/CPT/Taxonomy featured images in some way to give them a more consistent tone and feel. This plugin does that through SVGs and conversion.

At a broad sense it does the following:

1. You define what you want your 'filter' to do. Basically, how you want to build your SVG up with layers.
2. You can then write a WP-_Query to apply this filter to.
3. Specify the output you want - SVG, JPG, PNG.
4. And whether you want to change the original post to have this new image.
5. Run.


WP_Query --> Posts --> Run filter --> Build SVG with featured image of post --> Convert to JPG/SVG/PNG --> Write back to post.

## Dependencies

1. Wordpress
2. ACF - Dashboard and options page built with this.
3. Inkscape - Converts SVG --> PNG
4. PHP with Imagemagick (Imagick) - Converts PNG --> JPG
   
## The query

You have an option of:

1. Single Post / CPT
2. Single Taxonomy
3. WP_Query

These can be changed using ACF depending on your use case.

## The save options

Once the SVG is built up, you can opt to do:

1. Save to SVG file
2. Save to PNG file
3. Save to JPG file

WARNING - You ALSO have the write back to post option. Which allows you to write the JPG back to the post.
Be aware that if this setting is kept on, it'll recursively apply the filter over the top every time.

## The shortcode

There is a shortcode `[andyp_gen_image]` which will allow you to put the results on a page. You execute the whole process by visiting the shortcode page.

## Building up your SVG

The SVG data is built up with layers that you define. The order of the layers matter because it's how the output will be rendered.
There are two slightly different filter lists for the posts and the taxonomy because of the query that is run and the options you can pull back.

## The filters

### none

This filter does nothing. It allows you to 'switch off' a layer without deleting it. Handy for testing your output.

### darken

Creates a black rectangle of 100% height and width.
`<rect height="100%" width="100%" x="0" y="0" fill-opacity="{{your value}}" fill="#000000"></rect>`

#### Parameters
Opacity value [INT]. 0 to 1.
You can control the opacity with the parameter field. 