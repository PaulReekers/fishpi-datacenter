#!/usr/bin/env python

import RPi.GPIO as GPIO
import time

LIGHT1 = 23
LIGHT2 = 25

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(LIGHT1,GPIO.OUT)
GPIO.setup(LIGHT2, GPIO.OUT)

#GPIO.output(LIGHT1, GPIO.HIGH)

def drawLed():
  GPIO.output(LIGHT2, GPIO.HIGH)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.LOW)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.HIGH)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.LOW)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.HIGH)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.LOW)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.HIGH)
  time.sleep(1)
  GPIO.output(LIGHT2, GPIO.LOW)

drawLed()
drawLed()
