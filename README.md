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
