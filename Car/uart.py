import serial
import threading

class Uart :
    ser = 0

    def __init__(self) :
        self.ser = serial.Serial(port='/dev/ttyS0', baudrate=115200)
        #self.ser = serial.Serial(baudrate=115200)
            
    def uart_receive(self) :
        if (self.ser.in_waiting > 0) :
            data = self.ser.read(1)
            data = data.decode()
            return data

    def uart_transmit(self, str_data) :
        str_data = ('\x02'+str_data+'\x03').encode('ascii')
        self.ser.write(str_data)

def _uart_receive_thread():
    while True :
        data = _uart.uart_receive()

        if data != None :
            print(data)

if __name__ == "__main__" :
    _uart = Uart()

    uart_receive_thread_object = threading.Thread(target=_uart_receive_thread)
    uart_receive_thread_object.start()

    while True:
        a = input()
        _uart.uart_transmit(a)
