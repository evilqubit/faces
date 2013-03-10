#!/usr/bin/python
# -*- coding: utf-8 -*-
import sys, os
from opencv.cv import *
from opencv.highgui import *

def detectObjects(image):
  """Converts an image to grayscale and prints the locations of any
faces found"""
  grayscale = cvCreateImage(cvSize(image.width, image.height), 8, 1)
  cvCvtColor(image, grayscale, CV_BGR2GRAY)

  storage = cvCreateMemStorage(0)
  cvClearMemStorage(storage)
  cvEqualizeHist(grayscale, grayscale)
  cascade = cvLoadHaarClassifierCascade(
    'haarcascade_frontalface_default.xml',
    cvSize(1,1))
  faces = cvHaarDetectObjects(grayscale, cascade, storage, 1.2, 2,
                             CV_HAAR_DO_CANNY_PRUNING, cvSize(50,50))

  if faces:
    output=""
    for f in faces:
      #print("[(%d,%d) -> (%d,%d)]" % (f.x, f.y, f.x+f.width, f.y+f.height))
      #print ("%dx%d+%d+%d;" % (f.width,f.height,f.x,f.y))
      output+=str(f.width)+"x"+str(f.height)+"+"+str(f.x)+"+"+str(f.y)+";"
    print output

def main():
  image = cvLoadImage(sys.argv[1]);
  detectObjects(image)

if __name__ == "__main__":
  main()

