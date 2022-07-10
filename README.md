
<div id="top"></div>

<div align="center">

<img src="https://svg-rewriter.sachinraja.workers.dev/?url=https%3A%2F%2Fcdn.jsdelivr.net%2Fnpm%2F%40mdi%2Fsvg%406.7.96%2Fsvg%2Fimage-filter-vintage.svg&fill=%23A855F7&width=200px&height=200px" style="width:200px;"/>

<h3 align="center">Pipeline : Generative Images</h3>

<p align="center">
Create and generate SVG images using the featured image as a base. Part of the Pipeline project.
</p>    
</div>

##  1. <a name='TableofContents'></a>Table of Contents


* 1. [Table of Contents](#TableofContents)
* 2. [About The Project](#AboutTheProject)
	* 2.1. [Built With](#BuiltWith)
	* 2.2. [Installation](#Installation)
* 3. [Usage](#Usage)
	* 3.1. [The query](#Thequery)
	* 3.2. [Save options](#Saveoptions)
	* 3.3. [The shortcode](#Theshortcode)
	* 3.4. [Building up your SVG](#BuildingupyourSVG)
* 4. [The filters](#Thefilters)
	* 4.1. [none](#none)
	* 4.2. [image](#image)
	* 4.3. [image_free](#image_free)
	* 4.4. [svg_element](#svg_element)
	* 4.5. [svg_definition](#svg_definition)
	* 4.6. [noise](#noise)
	* 4.7. [darken](#darken)
	* 4.8. [whiten](#whiten)
	* 4.9. [random_colour](#random_colour)
	* 4.10. [text](#text)
		* 4.10.1. [Example](#Example)
	* 4.11. [acf_post_tax_field](#acf_post_tax_field)
	* 4.12. [acf_term_field](#acf_term_field)
	* 4.13. [acf_term_field_defintion](#acf_term_field_defintion)
	* 4.14. [generate_shape](#generate_shape)
		* 4.14.1. [palette](#palette)
		* 4.14.2. [additional_palette](#additional_palette)
		* 4.14.3. [additional_colours](#additional_colours)
		* 4.14.4. [opacity](#opacity)
		* 4.14.5. [corners](#corners)
		* 4.14.6. [corner_size](#corner_size)
		* 4.14.7. [shapes](#shapes)
		* 4.14.8. [cell_size](#cell_size)
		* 4.14.9. [Example](#Example-1)
* 5. [ Customising](#Customising)
* 6. [Troubleshooting](#Troubleshooting)
* 7. [Contributing](#Contributing)
* 8. [License](#License)
* 9. [Contact](#Contact)
* 10. [Changelog](#Changelog)



##  2. <a name='AboutTheProject'></a>About The Project

This is highly cowboy-coded plugin I created to enable me to manipulate all of my post images. The premise was to be able to automatically alter ALL posts/CPT/Taxonomy featured images in some way to give them a more consistent tone and feel. This plugin does that through SVGs and conversion. Note that I have 'articles' CPT with 'articlecategory' and 'articletags' as taxonomy and terms. The code may have references to these. Like I said, this was a personal project... Will need refactoring.

At a broad sense it does the following:

1. You define what you want your 'filter' to do. Basically, how you want to build your SVG up with layers.
2. You can then write a WP-_Query to apply this filter to.
3. Specify the output you want - SVG, JPG, PNG.
4. And whether you want to change the original post to have this new image.
5. Run.


WP_Query --> Posts --> Run filter --> Build SVG with featured image of post --> Convert to JPG/SVG/PNG --> Write back to post.

<p align="right">(<a href="#top">back to top</a>)</p>


###  2.1. <a name='BuiltWith'></a>Built With

This project was built with the following frameworks, technologies and software.

* [ACF](https://www.advancedcustomfields.com/pro/)
* [Inkscape](https://inkscape.org/)
* [Imagemagick](https://imagemagick.org/index.php)
* [PHP](https://php.net/)
* [Wordpress](https://Wordpress.org/)


- Inkscape - Converts SVG --> PNG
- PHP with Imagemagick (Imagick) - Converts PNG --> JPG

At time of writing, this is what I'm using : 

- WP 5.4, 
- ACF Pro 5.8.7, 
- Inkscape 0.92.4, 
- PHP 7.3.9, 
- imagick module 3.4.4.


<p align="right">(<a href="#top">back to top</a>)</p>


###  2.2. <a name='Installation'></a>Installation

1. Clone the repo into your wordpress plugin folder
    ```bash
    git clone https://github.com/IORoot/wp-plugin__api-scraper ./wp-content/plugins/api-scraper
    ```
1. Activate the plugin.


<p align="right">(<a href="#top">back to top</a>)</p>


##  3. <a name='Usage'></a>Usage


###  3.1. <a name='Thequery'></a>The query

You have an option of:

1. Single Post / CPT
2. Single Taxonomy
3. WP_Query

These can be changed using ACF depending on your use case.

###  3.2. <a name='Saveoptions'></a>Save options

Once the SVG is built up, you can opt to do:

1. Save to SVG file
2. Save to PNG file
3. Save to JPG file

WARNING - You ALSO have the write back to post option. Which allows you to write the JPG back to the post.
Be aware that if this setting is kept on, it'll recursively apply the filter over the top every time.

###  3.3. <a name='Theshortcode'></a>The shortcode

There is a shortcode `[andyp_gen_image]` which will allow you to put the results on a page. You execute the whole process by visiting the shortcode page.

###  3.4. <a name='BuildingupyourSVG'></a>Building up your SVG

The SVG data is built up with layers that you define. The order of the layers matter because it's how the output will be rendered.
There are two slightly different filter lists for the posts and the taxonomy because of the query that is run and the options you can pull back.

##  4. <a name='Thefilters'></a>The filters

###  4.1. <a name='none'></a>none

This filter does nothing. It allows you to 'switch off' a layer without deleting it. Handy for testing your output.

###  4.2. <a name='image'></a>image

This will create an `<image>` tag with the image of the source post. This will be the basis of the SVG size, so is needed to define the width/height of the SVG data.
Therefore, the base image has its height/width automatically set based on the source, which you can't change. 

`<image xlink:href="../../../../wp-content/uploads/2020/03/my_image.jpg" width="1280" height="720" {{params}}></image>`

    Parameter [STRING]
    The parameter is anything extra you want to add into the image string. Good for adding filters. i.e. filter="url(#myfilter)"

###  4.3. <a name='image_free'></a>image_free

This will create an `<image>` tag with the image of the source post. The difference here is that no width or height is set. Which is good for overlays and resizing/repositioning.

`<image xlink:href="../../../../wp-content/uploads/2020/03/my_image.jpg" {{params}}></image>`

    Parameter [STRING]
    The parameter is anything extra you want to add into the image string. Good for adding widths / heights / x /y / filters / etc..


###  4.4. <a name='svg_element'></a>svg_element

This create a layer of anything you want into the SVG at that level. This is used to insert new SVG shapes / Paths / Polygons / Logos / Images / other SVGs / groups / etc...  into it.

`your text`

    Parameter [STRING]
    Any manual text you want to inject in. 


###  4.5. <a name='svg_definition'></a>svg_definition

This creates a layer within the SVG `<defs></defs>` tags at the top. Allows you to insert filters into it.

`your filter text`

    Parameter [STRING]
    Any manual text you want to inject into the def tags. 


###  4.6. <a name='noise'></a>noise

Creates a transparent 'noise' layer. This is a tileable img supplied with the plugin. This creates a pattern definition and rectangle element to layer over everything underneath.

    Parameters [INT] 0 to 1.
    You can control the opacity with the parameter field. 


###  4.7. <a name='darken'></a>darken

Creates a black rectangle of 100% height and width.

`<rect height="100%" width="100%" x="0" y="0" fill-opacity="{{your value}}" fill="#000000"></rect>`

    Parameters [INT] 0 to 1.
    You can control the opacity with the parameter field. 


###  4.8. <a name='whiten'></a>whiten

Creates a white rectangle of 100% height and width.

`<rect height="100%" width="100%" x="0" y="0" fill-opacity="{{your value}}" fill="#ffffff"></rect>`

    Parameters [INT] 0 to 1.
    You can control the opacity with the parameter field.


###  4.9. <a name='random_colour'></a>random_colour

Creates a linear-gradient definition with a random hex colour. 

`<linearGradient id="{{your id}}"><stop stop-color="#98BC2A"/></linearGradient>`

    Parameters [STRING] 
    This allows you to set the ID of the linear-gradient definition. Therefore referencing it in any element with a
    fill=url(#randomID)

###  4.10. <a name='text'></a>text

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

####  4.10.1. <a name='Example'></a>Example

    <text>{{w2:uc:post_title}}</text>
    This will split by hypens, output the second one in UPPERCASE.


###  4.11. <a name='acf_post_tax_field'></a>acf_post_tax_field

This will allow the post get ACF fields on the category its attached to. Use the moustache brackets to define the category ACF field you wish to use. Has all functionality of the `text` type layer.

    Parameters [STRING] 
    Add an element with substitutions with the post object.

I created this because my categories have ACF colour fields on them. Any post attached to the category can now get the category colour.


###  4.12. <a name='acf_term_field'></a>acf_term_field

Only available on the 'Category' source. 

Allows you to specify an ACF field attached to the taxonomy and output the value. Moushache brackets, same as the `text` layer type.

    Parameters [STRING] 
    Add an element with substitutions with the term object.


###  4.13. <a name='acf_term_field_defintion'></a>acf_term_field_defintion

Same as the `acf_term_field` but puts the parameter into the `<defs></defs>` area of the SVG.


###  4.14. <a name='generate_shape'></a>generate_shape

This is the big 'generative' part of the plugin. Allows you to generate shapes onto a patchwork-quilt like space over the image. Also includes moustache {{}} substitution.

    Parameters [ARRAY]
    Define how the generative art will be created.

The following settings can be used:

####  4.14.1. <a name='palette'></a>palette

The 'palette' setting tells the generator which base colours to add to the primary palette. This palette is used to select random colours from.

    'palette' => '{{acf_taxonomy_colour}}, #FAFAFA',

####  4.14.2. <a name='additional_palette'></a>additional_palette

You can define a secondary palette that the generator can select from and add to the primary palette.

    'additional_palette' => '#000000,#242424,#424242,#757575,#E0E0E0,#F5F5F5,#FAFAFA,#FFFFFF',

####  4.14.3. <a name='additional_colours'></a>additional_colours

How many random colours to add to the primary palette from the secondary one.

    'additional_colours' => 1,

####  4.14.4. <a name='opacity'></a>opacity

What is the opacity of the shapes generated.

    'opacity' => 0.8,

####  4.14.5. <a name='corners'></a>corners

The generative shapes are added to the corners of the image. This selects which corners to use. If left blank, ALL corners are used.
Options are:

1. tl (top left)
2. tr (top right)
3. br (bottom right)
4. bl (bottom left)


    'corners' => 'tl,br',

####  4.14.6. <a name='corner_size'></a>corner_size

Furthest number of tiles to come out from the corner.

    'corner_size' => 4,

####  4.14.7. <a name='shapes'></a>shapes

Select the shapes you wish to randomly pick from. If left blank, ALL shapes are used.
Options are:

1. 'rect',
2. 'cross',
3. 'square_cross',
4. 'square_plus',
5. 'triangle',
6. 'right_angled_triangle',
8. 'leaf',
9. 'dots',
10. 'lines',
11. 'wiggles',
12. 'diamond',
13. 'flower',
14. 'stripes',
15. 'bump',

    'shapes' => 'leaf, cross,bump',

####  4.14.8. <a name='cell_size'></a>cell_size

Scale the size of the shape tiles up or down. Number in pixels. Default is 80.

    'cell_size' => 40,



####  4.14.9. <a name='Example-1'></a>Example

    [
        'palette' => '{{taxonomy_colour}}, #FAFAFA',
        'additional_palette' => '#000000,#242424,#424242,#757575,#E0E0E0,#F5F5F5,#FAFAFA,#FFFFFF',
        'additional_colours' => 1,
        'opacity' => 0.8,
        'corners' => 'tl,br',
        'corner_size' => 4,
        'shapes' => 'leaf, cross,bump',
        'cell_size' => 40,
    ]



##  5. <a name='Customising'></a> Customising

None.

##  6. <a name='Troubleshooting'></a>Troubleshooting

None.

<p align="right">(<a href="#top">back to top</a>)</p>


##  7. <a name='Contributing'></a>Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue.
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">(<a href="#top">back to top</a>)</p>



##  8. <a name='License'></a>License

Distributed under the MIT License.

MIT License

Copyright (c) 2022 Andy Pearson

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

<p align="right">(<a href="#top">back to top</a>)</p>



##  9. <a name='Contact'></a>Contact

Author Link: [https://github.com/IORoot](https://github.com/IORoot)

<p align="right">(<a href="#top">back to top</a>)</p>

##  10. <a name='Changelog'></a>Changelog

- v1.0.0 - Initial Commit
