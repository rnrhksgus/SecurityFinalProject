from appJar import gui
import socket
import sys
import re
import hashlib
import base64
import threading
import struct

from uart import Uart

def send(client, msg):
    try:
        frame = bytearray()
        data = bytearray(msg.encode('utf-8'))
        frame.append(129)
        if len(data) > 65535:
            frame.append(127)
            frame.extend(struct.pack('>Q', len(data)))
        elif len(data) > 125:
            frame.append(126)
            frame.extend(struct.pack('>H', len(data)))
        else:
            frame.append(len(data))
        client.send(frame + data)
        app.addListItem('list', data.decode())
        if(data.decode()=='go'):
            uart.uart_transmit('FRONT LAMP ON')
        elif (data.decode()=='back'):
            uart.uart_transmit('FRONT LAMP OFF')
    except Exception as e:
        print("send ERROR : " + str(e))


def recv(client):
    first_byte = bytearray(client.recv(1))[0]
    FIN = (0xFF & first_byte) >> 7
    opcode = (0x0F & first_byte)
    second_byte = bytearray(client.recv(1))[0]
    mask = (0xFF & second_byte) >> 7
    payload_len = (0x7F & second_byte)

    if opcode < 3:
        if (payload_len == 126):
            payload_len = struct.unpack_from('>H', bytearray(client.recv(2)))[0]
        elif (payload_len == 127):
            payload_len = struct.unpack_from('>Q', bytearray(client.recv(8)))[0]
        if mask == 1:
            masking_key = bytearray(client.recv(4))
            masked_data = bytearray(client.recv(payload_len))
            data = [masked_data[i] ^ masking_key[i % 4] for i in range(len(masked_data))]
        else:
            data = bytearray(client.recv(payload_len))
    else:
        return opcode, bytearray(b'\x00')
    # print(opcode)
    # print(bytearray(data).decode('utf-8'))
    return opcode, bytearray(data)


def handshake(client):
    try:
        request = client.recv(2048).decode('utf-8')
        sec_websocket_key = re.match('[\\w\\W]+Sec-WebSocket-Key: (.+)\r\n[\\w\\W]+\r\n\r\n', request)
        request_key = sec_websocket_key.group(1) + '258EAFA5-E914-47DA-95CA-C5AB0DC85B11'
        response_key = base64.b64encode(hashlib.sha1(request_key.encode()).digest()).decode()
        response = "HTTP/1.1 101 Switching Protocols\r\n"\
                   "Upgrade: websocket\r\n"\
                   "Connection: Upgrade\r\n"\
                   "Sec-WebSocket-Accept: " + response_key + "\r\n"\
                   "\r\n"
        client.send(response.encode())
        print('request_key : ' + request_key)
        print('response_key : ' + response_key)
        print("End Handshake")
    except Exception as e:
        print("Handshake ERROR : " + str(e))


def handle_client(client, addr):
    handshake(client)
    try:
        while 1:
            opcode, data = recv(client)
            if opcode == 0x8:
                print('close frame received')
                break
            elif opcode == 0x1:
                if len(data) == 0:
                    data = 'NO Message'.encode()
                msg = data.decode('utf-8')
                send(client, msg)
            else:
                print('frame not handled : opcode=' + str(opcode) + ' len=' + str(len(data)))

    except Exception as e:
        print(str(e))
    print("disconnected")
    client.close()


def start_server(port):
    sock = socket.socket()
    sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    sock.bind(('', port))
    sock.listen(1)

    while True:
        app.addListItem('list', 'Waiting for connection on port ' + str(port) + ' ...')
        client, addr = sock.accept()
        app.addListItem('list', 'Connection from: ' + str(addr))
        threading.Thread(target=handle_client, args=(client, addr)).start()


def thread_web_server():
    start_server(8765)


def button_press(name):
    if name == 'go':
        print(name, 'Click')
        app.addListItem('list', 'go')
        uart.uart_transmit('FRONT LAMP ON')
    elif name == 'back':
        print(name, 'Click')
        app.addListItem('list', 'back')
        uart.uart_transmit('FRONT LAMP OFF')
    elif name == 'right':
        print(name, 'Click')
        app.addListItem('list', 'right')
        uart.uart_transmit('FRONT SIDE LAMP RIGHT ON')
    elif name == 'left':
        print(name, 'Click')
        app.addListItem('list', 'left')
        uart.uart_transmit('FRONT SIDE LAMP LEFT ON')

uart_command = {
    '0' : { #ultra_sonic
        '0' : 'front_left_sonic',
        '1' : 'front_center_sonic',
        '2' : 'front_right_sonic',
        '3' : 'back_left_sonic',
        '4' : 'back_right_sonic'
    },

    '1' : { #photo_transistor
        '0' : 'front_left_photo',
        '1' : 'front_right_photo'
    },

    '2' : { #encoder
        '0' : 'middle_front_left_encoder',
        '1' : 'middle_front_right_encoder',
        '2' : 'middle_back_left_encoder',
        '3' : 'middle_back_right_encoder'
    },

    '3' : { #mpu6050
        '0' : 'main_mpu6050_ax',
        '1' : 'main_mpu6050_ay',
        '2' : 'main_mpu6050_az',
        '3' : 'main_mpu6050_gx',
        '4' : 'main_mpu6050_gy',
        '5' : 'main_mpu6050_gz'
    },

    '4' : { #battery
        '0' : 'battery'
    }
}

def uart_receive_thread():
    while True:
        buf = []
        loop = 0

        # wait until STX
        while uart.uart_receive() != '\x02':
            time.sleep(0.001)

        # receive data until ETX
        while True:
            data = uart.uart_receive()

            if (data != None):

                if (data == '\x03'):
                    break

                buf.append(data)
                loop += 1
                time.sleep(0.001)

        target = uart_command[buf[0]][buf[1]]
        app.setLabel(target + "_label", buf[2:])


if __name__ == "__main__":
    try:
        app = gui('Smart Car Control', '800x500')


        # LEFT_1
        app.startFrame('LEFT_1', row=0, column=0)
        #app.addImage('picture', './car.png')
        app.stopFrame()

        #LEFT_2
        app.startFrame('LEFT_2', row=1, column=0)
        app.addButton('go', button_press, 0, 1)
        app.addButton('left', button_press, 1, 0)
        app.addButton('right', button_press, 1, 2)
        app.addButton('back', button_press, 2, 1)
        app.stopFrame()

        # RIGHT
        app.startFrame('RIGHT', row=0, column=1, rowspan=2)
        app.setFont(10)
        app.addListBox('list')
        app.stopFrame()

        uart = Uart()
        uart_receive = threading.Thread(target=uart_receive_thread)
        uart_receive.start()

        web_server = threading.Thread(target=thread_web_server)
        web_server.start()
        app.go()

    except Exception as e:
        print(str(e))
