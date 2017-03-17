#!/usr/bin/env python

import time
import urllib2
import base64
import json
import RPi.GPIO as GPIO
import socket
import uuid
import paho.mqtt.client as mqtt

LED_RED = 17
LED_GREEN = 22
LED_YELLOW = 18

URL_PREFIX = '<URL>'
URL_ENDPOINT = '/api/v1/command'
URL_USERNAME = '******'
URL_PASSWORD = '******'
MQTT_HOST = '95.85.5.39'
MQTT_CLIENT = uuid.uuid4().urn[9:]
MQTT_CHANNEL = 'fishpi/#'

def initLeds():
  GPIO.setmode(GPIO.BCM)
  GPIO.setwarnings(False)
  GPIO.setup(LED_RED,GPIO.OUT)
  GPIO.setup(LED_GREEN,GPIO.OUT)
  GPIO.setup(LED_YELLOW,GPIO.OUT)
  offLed(LED_YELLOW)
  offLed(LED_GREEN)
  offLed(LED_RED)

def getCommand():
  url = URL_PREFIX+URL_ENDPOINT
  return callURL(url)

def callURL(url):
  base64string = base64.b64encode('%s:%s' % (URL_USERNAME, URL_PASSWORD))
  req = urllib2.Request(url)
  req.add_header("Authorization", "Basic %s" % base64string)
  try:
    res = urllib2.urlopen(req)
    return res.read();
  except Exception as e:
    print e.read()

def sendIP(ip):
  url = URL_PREFIX+URL_ENDPOINT+'/ip?ip='+ip
  callURL(url)


def parseCommand(command):
  print "Parse command: "+command
  try:
    data = json.loads(command)
  except ValueError, e:
    return;
  if not data:
    print("empty result, do nothing")
    return
  if "command" not in data:
    print("command missing, do nothing")
    return
  if "data" not in data:
    print("data missing in command, do nothing")
  runCommand(data["command"], data["data"])

def runCommand(command, data):
  print "Run command: "+command+" with data"
  if command == "setled":
    runSetLed(data)
  elif command == "testrun":
    runTest(0.3)
  elif command == "compose":
    runCompose(data)
  elif command == "askip":
    runGetIp()

def runGetIp():
  ip = get_ip_address()
  sendIP(ip)

def runCompose(data):
  for step in data:
    led = getLed(step)
    clearLeds()
    if led > 0:
      onLed(led)
    if step["time"] < 20:
      time.sleep(step["time"])
  clearLeds()

def get_ip_address():
  s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
  s.connect(("8.8.8.8", 80))
  return s.getsockname()[0]

def runSetLed(data):
  led = getLed(data)
  if led < 1:
    return
  if "clear" in data and  data["clear"]:
    clearLeds()
  if "onOrOff" in data and data["onOrOff"] == "off":
    offLed(led)
  else:
    onLed(led)
  if "time" in data and  data["time"]:
    time.sleep(float(data["time"]))
    offLed(led)

def getLed(data):
  if data["led"] == "red":
    led = LED_RED
  elif data["led"] == "green":
    led = LED_GREEN
  elif data["led"] == "orange":
    led = LED_YELLOW
  else:
    led = 0
  return led

def runTest(speed):
  clearLeds()
  onLed(LED_GREEN)
  time.sleep(speed)
  offLed(LED_GREEN)
  onLed(LED_YELLOW)
  time.sleep(speed)
  offLed(LED_YELLOW)
  onLed(LED_RED)
  time.sleep(speed)
  offLed(LED_RED)
  speed-=0.01
  if speed>0:
    runTest(speed)

def offLed(led):
  GPIO.output(led,GPIO.LOW)

def onLed(led):
  GPIO.output(led,GPIO.HIGH)

def setLed(led, time, clear):
  if clear:
    clearLeds()
  onLed(led)
  sleep(time)
  offLed(led)

def clearLeds():
  offLed(LED_YELLOW)
  offLed(LED_GREEN)
  offLed(LED_RED)

def mqttConnect(client, data, flags, rc):
  m="Connected flags"+str(flags)+"result code "+str(rc)+"client1_id  "+str(client)
  print(m)

def mqttMessage(client, userdata, message):
  ret = str(message.payload.decode("utf-8"))
  print("message received  ", ret)
  parseCommand(ret)

initLeds()

client = mqtt.Client(MQTT_CLIENT)
client.on_connect = mqttConnect
client.on_message = mqttMessage
time.sleep(1)
client.connect(MQTT_HOST)
client.loop_start()
client.subscribe(MQTT_CHANNEL)
while 1:
  time.sleep(.1)