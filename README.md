# phlabel
A small program to generate label images from a template file and "CSV" file.

All output images are written to the current directory.

## Example usage

>cat examples/componentBookValues.csv | php phlabel.php examples/componentBook.template

## The template file

The template file is supposed to define the layout and fixed data of the label.

Variables inside the template are replaced by data from the "CSV" file.

$0 is replaced by the label number (zero indexed)   
$1 is data from the first row in the CVS file.   
$2 the second row.   
.   
.   
$n the n-th row.
### The template file commands
The template file consists of a list of commands that defines the label.

The commands are documented below:

#### setSize(width,height)
Sets the output canvas / label size'in pixels.   
Should be the first command in the template file.

#### setRotation(angle)
Sets the angle (counter clockwise) in degrees of the following drawing operations.  
Notice that not all rendering operations support every rendering rotation.  
Especially printing with bitmap fonts currently only support rotations of 0 and 90 deg. 


#### setBitmapFont(size)
Sets the text rendering to use one of the bitmap fonts build into GD2.  
size=1 is the smallest, size=5 is the largest.

#### setFont(path_to_ttf, size)
Sets the TTF font and size to use for printing text onto the label.   
This switches to TrueType rendering for text, while TT fonts are scaleable they don't always
render particular well on low resolution monochrome printers

* path_to_ttf, a relative or absolute path to a TrueType font.
* size, the font size to use.

#### print(x,y,string)
Prints a string onto the canvas / label.

* x and y is the x and y coordinate of the string
* string, the string to print, please note that any white spaces after the comma is also printed.

The definition of the coordinate of the string varies with the text rendering method

* True Type: The coordinate specifies the base point for the first letter.
* Bitmap fonts: It is the upper left corner (0 deg rotation) or the bottom left corner (90 deg. rotation)