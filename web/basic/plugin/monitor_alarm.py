#!/usr/bin/python

import urllib
import urllib2
import time
import json
import os
import threading
import ConfigParser

class GetAlarm(threading.Thread):  
    def __init__(self, urlName, url):  
        threading.Thread.__init__(self)
        self.urlName = urlName
        self.url = url
  
    def run(self): 
        try:
            data =[]
            response = urllib2.urlopen(self.url, timeout = 50)
            b=response.read()
            if (b):
                results = json.loads(b)
                for result in results:
                    result["cluster"] = self.urlName
                    data.append(result)
                alarmFile = open("./alarm/" + self.urlName, 'w')
                alarmFile.write(json.dumps(data))
            else:
                alarmFile = open("./alarm/" + self.urlName, 'w')
                alarmFile.write("")

        except Exception, e:
            print e
            if os.access("./alarm/" + self.urlName, os.F_OK):
                pass
            else:   
                open("./alarm/" + self.urlName, 'w')
            
def Monitor():  
    global data
    data = []
    cf = ConfigParser.ConfigParser()
    cf.read("alarm.conf")
    kvs = cf.items("monitor")
    opt = cf.options("monitor")
    deletfile(opt)
    for sec in kvs:
        t = GetAlarm(sec[0], sec[1])
        t.setDaemon(True)
        t.start()
        t.join(2)
    scheual = threading.Timer(5, Monitor)
    scheual.start() 
    time.sleep(50)

def deletfile(opt):
    fileList = GetFileList('./alarm',[])
    opt = ['./alarm/'+opts for opts in opt]
    file_delete = list(set(fileList).difference(set(opt)))
    for files in file_delete:
        os.remove(files)
    
def GetFileList(dir,fileList):
    newDir = dir
    if os.path.isfile(dir):
        fileList.append(dir.decode('gbk'))
    elif os.path.isdir(dir):
        for s in os.listdir(dir):
            newDir=os.path.join(dir,s)
            GetFileList(newDir,fileList)
    return fileList

def writeAlarm(data):
    alarmFile = open('alarm', 'w')
    alarmFile.write(json.dumps(data))
    

if __name__=='__main__':
    Monitor()
