import paho.mqtt.client as mqtt  #import the client1
import time

def on_connect(client, userdata, flags, rc):
    m="Connected flags"+str(flags)+"result code "\
    +str(rc)+"client1_id  "+str(client)
    print(m)

def on_message(client1, userdata, message):

    print("message received  "  ,str(message.payload.decode("utf-8")))


# Please add correct address
broker_address="95.85.5.39"

client1 = mqtt.Client("P2")
client1.on_connect= on_connect
client1.on_message=on_message
time.sleep(1)
client1.connect(broker_address)
client1.loop_start()
client1.subscribe("fishpi/#")
while 1:
    time.sleep(.1)
client1.disconnect()
client1.loop_stop()
