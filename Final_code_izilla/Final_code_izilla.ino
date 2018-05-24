/*  G.E. Digital Hackathon
 *   Arunachalam M
 *   Sangeeth Raaj S R
 *    Amrita School of Engineering
 *
 * Pin Connections and Equipments used
 *
 * //////////////Arun, Update them here and remove the Servo 5line comment below///////////
 *
 */


#include<stdlib.h>
#include <DHT.h>
#include <DHT_U.h>
#include <SoftwareSerial.h>
#include <Servo.h>

Servo lock;

SoftwareSerial RFSerial(10, 11);

#define SSID "Jiofis"             // Wifi HotSpot Credentials   // While Uploading, give generic ones like <SSID> and <passkey>
#define PASS "qwerty1234"

#define DHTPIN 6     // what pin the DHT sensor is connected to
#define DHTTYPE DHT11   // Change to DHT22 if that's what you have
#define Baud_Rate 9600
#define DELAY_TIME 15000 //time in ms between posting data to ThingSpeak
//
//  SERVO RED at VCC (5V)
//  SERVO ORANGE AT PIN 9
//  SERVO BROWN/BLACK AT GND
//  BUTTON AT 8
//  LED at 12
//

String cmd, data;
String ser = "izilla.sangeethraaj.com";
String uri = "/add.php";
String uri1 = "/addstat.php";
String uri2 = "/addopen.php";
String devId = "iZilla001";   // the key that identifies the transport cooler box

char input[13];
int count = 0;
unsigned long prev = 0;
unsigned long curr = 0;
unsigned long prev1 = 0;
unsigned long curr1 = 0;

const int button = 8;
const int led =  12; // for Showing the status of opening button

bool updated, isOpen = 0;

DHT dht(DHTPIN, DHTTYPE);

//this runs once
void setup()
{
  RFSerial.begin(9600);

  pinMode(led, OUTPUT);
  pinMode(button, INPUT);
  lock.attach(9);
  Serial.begin(Baud_Rate);
  Serial.println("AT");

  delay(5000);

  if(Serial.find("OK")){
    //connect to your wifi netowork
    bool connected = connectWiFi();
    if(!connected){
    //   Error();
    Serial.println("wifi not connected");
    }
  }else{
    // Error()
    Serial.println("wifi not connected");
  }
  dht.begin();
  data="";
  prev = millis();
}

void loop(){

  curr = millis();

  /***********      Once every 15 secs    **************/
  if(curr - prev >= DELAY_TIME)
  {
        prev = curr;
        lock.write(0);
        float h = dht.readHumidity();
        float f = dht.readTemperature(false);
        if (isnan(h) || isnan(f)) {
          return;
        }
         updated = updateTemp(String(f), String(h));
   }
  /***********      Checks for press of button when closed    **************/
  if(!isOpen && digitalRead(button) == HIGH){
    digitalWrite(led, HIGH);delay(500);digitalWrite(led, LOW);
    if(updateopen()){
          lock.write(180);
          isOpen = 1;
          curr1=prev1=millis();
          digitalWrite(led, HIGH);delay(500);digitalWrite(led, LOW);delay(500);digitalWrite(led, HIGH);delay(1000);digitalWrite(led, LOW);delay(1000);
      }
    }
    /***********      Closes the box after 20 secs    **************/
  if(isOpen){
    curr1=millis();
    if(curr1-prev1 >= 20000){
          digitalWrite(led, HIGH);delay(1000);digitalWrite(led, LOW);delay(300);digitalWrite(led, HIGH);delay(1000);digitalWrite(led, LOW);delay(300);
           lock.write(180);
          isOpen = 0;
          digitalWrite(led, HIGH);delay(1000);digitalWrite(led, LOW);delay(1000);
      }
  }
  if(RFSerial.available())
   {
      count = 0;
      while(RFSerial.available() && count < 12)          // Read 12 characters and store them in input array
      {
         input[count] = RFSerial.read();
         count++;
         delay(5);
      }
      input[12]='\0';
      updatestat(String(input));
   }
}

/***********      Temperature and humidity uploader Code    **************/

bool updateTemp(String tenmpF, String humid){
  Serial.println("AT+CIPSTART=\"TCP\",\"izilla.sangeethraaj.com\",80");
  delay(2000);
  if(Serial.find("Error")){
    return false;
  }
  data = "temp1=" + tenmpF + "&hum1=" + humid + "&devid="+devId;

  cmd =  "POST " + uri + " HTTP/1.0\r\n" +
        "Host: " + ser + "\r\n" +
        "Accept: *" + "/" + "*\r\n" +
        "Content-Length: " + data.length() + "\r\n" +
        "Content-Type: application/x-www-form-urlencoded\r\n" + "\r\n" + data + "\r\n";
  Serial.print("AT+CIPSEND=");
  Serial.println(cmd.length());
  if(Serial.find(">")){
    Serial.print(cmd);
  }else{
    Serial.println("AT+CIPCLOSE");
  }
  if(Serial.find("OK")){
    return true;
  }else{
    return false;
  }
}

/***********      Updates Status when RFID is Swiped    **************/

bool updatestat(String sid){
  Serial.println("AT+CIPSTART=\"TCP\",\"izilla.sangeethraaj.com\",80");
  delay(2000);
  if(Serial.find("Error")){
    return false;
  }
  data = "devid=" + devId + "&sid="+ sid;

  cmd =  "POST " + uri1 + " HTTP/1.0\r\n" +
        "Host: " + ser + "\r\n" +
        "Accept: *" + "/" + "*\r\n" +
        "Content-Length: " + data.length() + "\r\n" +
        "Content-Type: application/x-www-form-urlencoded\r\n" + "\r\n" + data;
  cmd += "\r\n";
  //Use AT commands to send data
  Serial.print("AT+CIPSEND=");
  Serial.println(cmd.length());
  if(Serial.find(">")){
    //send through command to update values
    Serial.print(cmd);
  }else{
    Serial.println("AT+CIPCLOSE");
  }
  if(Serial.find("OK")){
    return true;
  }else{
    return false;
  }
}

/***********     Opening of box updater Code    **************/

bool updateopen(){
  Serial.println("AT+CIPSTART=\"TCP\",\"izilla.sangeethraaj.com\",80");
  delay(2000);
  if(Serial.find("Error")){
    return false;
  }
  data = "devid=" + devId;
  cmd =  "POST " + uri2 + " HTTP/1.0\r\n" +
        "Host: " + ser + "\r\n" +
        "Accept: *" + "/" + "*\r\n" +
        "Content-Length: " + data.length() + "\r\n" +
        "Content-Type: application/x-www-form-urlencoded\r\n" + "\r\n" + data;
  cmd += "\r\n";
  //Use AT commands to send data
  Serial.print("AT+CIPSEND=");
  Serial.println(cmd.length());
  if(Serial.find(">")){
    //send through command to update values
    Serial.print(cmd);
  }else{
    Serial.println("AT+CIPCLOSE");
  }
  delay(1000);
  if(Serial.find("OK")){
    return true;
  }else{
    return false;
  }
}

/***********      WiFi Connection Code    **************/
boolean connectWiFi(){
  Serial.println("AT+CWMODE=1");
  delay(2000);
String cmd="AT+CWJAP=\"";
  cmd+=SSID;
  cmd+="\",\"";
  cmd+=PASS;
  cmd+="\"";

  Serial.println(cmd);
  delay(5000);

  if(Serial.find("OK")){
    return true;
  }else{
    return false;
  }
}
