gs-image-converter
==================

PHP Image File Type Converter - Convert from one image type to another 

## Supported Formats
``JPEG`` ``GIF`` ``PNG`` <-- --> ``JPEG`` ``GIF`` ``PNG``

## How to use
### Format 1
Specify source image path and extension to convert it to. It will save the new image with the new type in the same directory with the same file name. In the xample below it will save new image to ``/home/gs/my_img.jpeg``
```
GsImageConverter::convert('/home/gs/my_img.png', 'jpeg');
```

### Format 2
Specify source image and destination path for new image with new extension. In the example below it will save new image to ``/home/gs/new/my_new_file.jpeg``
```
GsImageConverter::convert('/home/gs/my_img.png', '/home/gs/new/my_new_file.jpeg');
```

### Background Color when converting to JPEG
When converting an image from a type that supports transparency to one
that does not ex: png --> jpeg, a color needs to fill the space. It can be specified in the third parameter in an array of RGB.
```
GsImageConverter::convert('/home/gs/my_img.png', '/home/gs/new/my_new_file.jpeg', array(0, 0, 0));
```

### License
The MIT License

Copyright (c) 2013-2014 Garagesocial, Inc. http://garagesocial.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
