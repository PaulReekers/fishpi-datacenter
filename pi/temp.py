#!/usr/bin/env python
import time
import urllib2
import StringIO
import md5

AIR_BUS = "/sys/bus/w1/devices/28-0116005b85ff/w1_slave";
WATER_BUS = "/sys/bus/w1/devices/28-031600ce7dff/w1_slave";

URL_PREFIX = '<url>'
URL_TEMP_ENDPOINT = '/api/v1/data/store'
SECRET = '<hash>'

MAX_DIFF = 3000
PREV_AIR_TEMP = 0
PREV_WATER_TEMP = 0

def getWaterTemp():
	temp = getTemp(WATER_BUS)
	return temp

def getAirTemp():
	temp = getTemp(AIR_BUS)
	return temp

def getTemp(bus):
	# Open the file that we viewed earlier so that python can see what is in it. Replace the serial number as before.
	tfile = open(bus)
	# Read all of the text in the file.
	text = tfile.read()
	# Close the file now that the text has been read.
	tfile.close()
	# Split the text with new lines (\n) and select the second line.
	secondline = text.split("\n")[1]
	# Split the line into words, referring to the spaces, and select the 10th word (counting from 0).
	temperaturedata = secondline.split(" ")[9]
	# The first two characters are "t=", so get rid of those and convert the temperature from a string to a number.
	temperature = float(temperaturedata[2:])
	# Put the decimal point in the right place and display it.
	#temperature = temperature / 1000
	return temperature

def sendTemp(air, water):
	global PREV_WATER_TEMP
	global PREV_AIR_TEMP
	t = str(int(time.time()))
	air = str(int(air))
	water = str(int(water))
	m = md5.new()
	m.update(air+water+t+SECRET)
	hash = m.hexdigest()

	url = URL_PREFIX+URL_TEMP_ENDPOINT
	url = url+'?air='+air+'&water='+water+'&time='+t+'&hash='+hash

	req = urllib2.Request(url)

	if (PREV_AIR_TEMP > 0) and (PREV_WATER_TEMP > 0) and ((abs(int(water) - PREV_WATER_TEMP) > MAX_DIFF) or (abs(int(air) - PREV_AIR_TEMP) > MAX_DIFF)):
		print 'Diff between prev and current temp is more then '+str(MAX_DIFF)+' temp '+air+' : '+water
	else:
		PREV_AIR_TEMP = int(air)
		PREV_WATER_TEMP = int(water)
		try:
			res = urllib2.urlopen(req)
			print 'Data send Air: '+air+' Water '+water
		except urllib2.HTTPError as e:
			print e.code
			print e.read()
			print url
		except urllib2.URLError as e:
			print 'URL Error'


while 1==1:
	airTemp = getAirTemp()
	waterTemp = getWaterTemp()
	sendTemp(airTemp, waterTemp)
	time.sleep(1)

