"""
Uses shoebot to create background picture
"""

import os
import shoebot
from urllib import quote
from random import random
from shoebot.core import CairoCanvas, CairoImageSink, NodeBot

import sys
import Image

PATH = os.path.join('themes', 'commentpress', 'style', 'images', 'backgrounds')

def convert_image(img_file, colour=(0,0,0,255)):
    im = Image.open(img_file)
    im.thumbnail((240, 240), Image.ANTIALIAS)
    
    im = im.convert("1")
    im = im.convert("RGBA")
    pixdata = im.load()
    
    # if colour is specified as html colour
    if isinstance(colour, basestring):
        colour = colour.replace("#","")
        colour = tuple([ord(c) for c in colour.decode('hex')] + [255])
    
    for y in xrange(im.size[1]):
        for x in xrange(im.size[0]):
            # make white pixels transparent:
            if pixdata[x, y] == (255, 255, 255, 255):
                pixdata[x, y] = (255, 255, 255, 0)
            # black pixels take on specified colour:
            else:
                pixdata[x,y] = colour

    im.save(img_file.replace('jpg','png'))

def convert_images(colour='#e4da2f'):
    imgs = [os.path.join(PATH, i) for i in os.listdir(PATH) if 'jpg' in i and 'small' in i]
    for img in imgs:
        convert_image(img, colour)

def swarm_bot():
    images_folder = PATH
    output_image = os.path.join(PATH, "background.png")
    imgs = [os.path.join(images_folder, i) for i in os.listdir(images_folder) if 'png' in i and 'small' in i]
    
    sink = CairoImageSink(output_image, "png", multifile = False)
    canvas = CairoCanvas(sink, enable_cairo_queue=True)
    bot = shoebot.core.NodeBot(canvas)
    
    scale = 24
    upto = 32
    if len(imgs) < 32:
        upto = len(imgs)
    
    points = [((i**2 % 25), i**2 / 8) for i in range(0, upto)]
    
    HEIGHT = 38 * scale
    WIDTH = 38 * scale
    bot.size(WIDTH, HEIGHT);
    bot.background((1,1,1,0))
    
    for i in range(0, upto):
        bot.image(imgs[i], points[i][0] * 2 * scale * random(), points[i][1] * 3 * scale)
    
    bot._canvas.flush(frame=0)


if __name__ == "__main__":
    convert_images()
    swarm_bot()