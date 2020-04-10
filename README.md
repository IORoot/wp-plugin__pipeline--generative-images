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

What I'm using : WP 5.4, ACF Pro 5.8.7, Inkscape 0.92.4, PHP 7.3.9, imagick module 3.4.4.
   
## The query

You have an option of:

1. Single Post / CPT
2. Single Taxonomy
3. WP_Query

These can be changed using ACF depending on your use case.

## Save options

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

# The filters

## none

This filter does nothing. It allows you to 'switch off' a layer without deleting it. Handy for testing your output.

## image

This will create an `<image>` tag with the image of the source post. This will be the basis of the SVG size, so is needed to define the width/height of the SVG data.
Therefore, the base image has its height/width automatically set based on the source, which you can't change. 

`<image xlink:href="../../../../wp-content/uploads/2020/03/my_image.jpg" width="1280" height="720" {{params}}></image>`

    Parameter [STRING]
    The parameter is anything extra you want to add into the image string. Good for adding filters. i.e. filter="url(#myfilter)"

## image_free

This will create an `<image>` tag with the image of the source post. The difference here is that no width or height is set. Which is good for overlays and resizing/repositioning.

`<image xlink:href="../../../../wp-content/uploads/2020/03/my_image.jpg" {{params}}></image>`

    Parameter [STRING]
    The parameter is anything extra you want to add into the image string. Good for adding widths / heights / x /y / filters / etc..


## svg_element

This create a layer of anything you want into the SVG at that level. This is used to insert new SVG shapes / Paths / Polygons / Logos / Images / other SVGs / groups / etc...  into it.

`your text`

    Parameter [STRING]
    Any manual text you want to inject in. 


## svg_definition

This creates a layer within the SVG `<defs></defs>` tags at the top. Allows you to insert filters into it.

`your filter text`

    Parameter [STRING]
    Any manual text you want to inject into the def tags. 

  
## noise

Creates a transparent 'noise' layer. This is a tileable img supplied with the plugin. This creates a pattern definition and rectangle element to layer over everything underneath.

    Parameters [INT] 0 to 1.
    You can control the opacity with the parameter field. 


## darken

Creates a black rectangle of 100% height and width.

`<rect height="100%" width="100%" x="0" y="0" fill-opacity="{{your value}}" fill="#000000"></rect>`

    Parameters [INT] 0 to 1.
    You can control the opacity with the parameter field. 


## whiten

Creates a white rectangle of 100% height and width.

`<rect height="100%" width="100%" x="0" y="0" fill-opacity="{{your value}}" fill="#ffffff"></rect>`

    Parameters [INT] 0 to 1.
    You can control the opacity with the parameter field.


## random_colour

Creates a linear-gradient definition with a random hex colour. 

`<linearGradient id="{{your id}}"><stop stop-color="#98BC2A"/></linearGradient>`

    Parameters [STRING] 
    This allows you to set the ID of the linear-gradient definition. Therefore referencing it in any element with a
    fill=url(#randomID)

## text

Similar to the 'svg_element' layer, but with a caveat... allows you to do text substitutions on wp_post parameters using moustache brackets.
As an example, using `{{post_title}}` in the parameter field will be substituted for the actual post_title. It also checks against any ACF fields attached to the post object too.
Just use the name of the field you wish to return in the moustache brackets. {{my_acf_colour_field_added_to_the_post}}

    Parameters [STRING] 
    Add an element with substitutions with the post object.

The text substitution also has a few extra 'functions' that you can prefix the field with.

1. `uc:` will make the output text of the field UPPERCASE.
2. `hy:` will remove everything BEFORE (and including) a hypen. 'HELLO - WORLD' will become 'WORLD'.
3. `w1:` will split the line by hypens and output the first. 'FIRST - SECOND - THIRD - FOURTH` will return 'FIRST'.
4. `w2:` will split the line by hypens and output the second. 'FIRST - SECOND WORD - 3RD - FOURTH` will return 'SECOND WORD'.
5. `w3:` will split the line by hypens and output the third. 'FIRST - SECOND WORD - 3RD - FOURTH` will return '3RD'.
6. `w3:` will split the line by hypens and output the fourth. 'FIRST - SECOND WORD - 3RD - FOURTH` will return 'FOURTH'.

### Example

    <text>{{w2:uc:post_title}}</text>
    This will split by hypens, output the second one in UPPERCASE.

## acf_post_tax_field



## acf_term_field

## acf_term_field_defintion

## generate_shape
