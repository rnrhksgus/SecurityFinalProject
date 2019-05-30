import socket
import re
import hashlib
import base64
import threading
import struct


class WS:
    def __init__(self, app, uart):
        self.app = app
        self.uart = uart

    def uart_send_message(self, message):
        if message == 'go':
            self.uart.uart_transmit('FRONT LAMP ON')
        elif message == 'back':
            self.uart.uart_transmit('FRONT LAMP OFF')
        elif message == 'left':
            self.uart.uart_transmit('FRONT SIDE LAMP RIGHT ON')
        elif message == 'right':
            self.uart.uart_transmit('FRONT SIDE LAMP RIGHT OFF')
        elif message == 'move':
            self.uart.uart_transmit('MIDDLE FRONT LEFT MOTOR FORWARD')
            self.uart.uart_transmit('MIDDLE FRONT RIGHT MOTOR FORWARD')
            self.uart.uart_transmit('MIDDLE BACK LEFT MOTOR FORWARD')
            self.uart.uart_transmit('MIDDLE BACK RIGHT MOTOR FORWARD')
        elif message == 'stop':
            self.uart.uart_transmit('MIDDLE FRONT LEFT MOTOR STOP')
            self.uart.uart_transmit('MIDDLE FRONT RIGHT MOTOR STOP')
            self.uart.uart_transmit('MIDDLE BACK LEFT MOTOR STOP')
            self.uart.uart_transmit('MIDDLE BACK RIGHT MOTOR STOP')

    def send(self, client, msg):
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
            self.uart_send_message(msg)
        except Exception as e:
            print("send ERROR : " + str(e))

    def recv(self, client):
        first_byte = bytearray(client.recv(1))[0]
        FIN = (0xFF & first_byte) >> 7
        opcode = (0x0F & first_byte)
        second_byte = bytearray(client.recv(1))[0]
        mask = (0xFF & second_byte) >> 7
        payload_len = (0x7F & second_byte)

        if opcode < 3:
            if payload_len == 126:
                payload_len = struct.unpack_from('>H', bytearray(client.recv(2)))[0]
            elif payload_len == 127:
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

    def handshake(self, client):
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

    def handle_client(self, client, addr):
        self.handshake(client)
        try:
            while 1:
                opcode, data = self.recv(client)
                if opcode == 0x8:
                    print('close frame received')
                    break
                elif opcode == 0x1:
                    if len(data) == 0:
                        data = 'NO Message'.encode()
                    msg = data.decode('utf-8')
                    self.send(client, msg)
                else:
                    print('frame not handled : opcode=' + str(opcode) + ' len=' + str(len(data)))
        except Exception as e:
            print(str(e))
        print("disconnected")
        client.close()

    def start_server(self, port):
        sock = socket.socket()
        sock.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
        sock.bind(('', port))
        sock.listen(1)

        self.app.addListItem('list', 'Waiting for connection on port ' + str(port) + ' ...')
        client, addr = sock.accept()
        self.app.addListItem('list', 'Connection from: ' + str(addr))
        threading.Thread(target=self.handle_client, args=(client, addr)).start()


if __name__ == "__main__":
    ws = WS('app')
    ws.start_server(9999)
