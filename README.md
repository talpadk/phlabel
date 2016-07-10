# phlabel
A small program to generate label images from a template file and "CSV" file

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

#### setFont(path_to_ttf, size)
Sets the font to use for printing text onto the label.   
Must be used before any print command.

* path_to_ttf, a relative or absolute path to a TrueType font.
* size, the font size to use.

#### print(x,y,string)
Prints a string onto the canvas / label.

* x, is the x coordinate of the base point for the fist letter.
* y, is the y coordinate of the base point for the fist letter.
* string, the string to print, please note that any white spaces after the comma is also printed.