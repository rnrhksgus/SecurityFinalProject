from appJar import gui
from uart import Uart
from web_server import WS

import time
import threading

# flag 변수
rl_on_off = 1
ll_on_off = 1
scale = 0

def button_press(name):
    global rl_on_off, ll_on_off, scale
    if name == 'go':
        print(name, 'Click')
        app.addListItem('list', 'go')
        uart.uart_transmit('FRONT LAMP ON')
    elif name == 'back':
        print(name, 'Click')
        app.addListItem('list', 'back')
        uart.uart_transmit('FRONT LAMP OFF')
    elif name == 'right':
        if (rl_on_off == 1):
            print(name, 'Lamp On!')
            app.addListItem('list', 'right')
            uart.uart_transmit('FRONT SIDE LAMP RIGHT ON')
            rl_on_off = 0
        elif (rl_on_off == 0):
            print(name, 'Lamp OFF!')
            app.addListItem('list', 'right')
            uart.uart_transmit('FRONT SIDE LAMP RIGHT OFF')
            rl_on_off = 1
    elif name == 'left':
        if (ll_on_off == 1):
            print(name, 'Lamp On!')
            app.addListItem('list', 'left')
            uart.uart_transmit('FRONT SIDE LAMP LEFT ON')
            ll_on_off = 0
        elif (ll_on_off == 0):
            print(name, 'Lamp OFF!')
            app.addListItem('list', 'left')
            uart.uart_transmit('FRONT SIDE LAMP LEFT OFF')
            ll_on_off = 1
    elif name == 'stop':
        scale = 0
        print(name, 'Clicked!')
        app.addListItem('list', 'stop')
        uart.uart_transmit('MIDDLE FRONT LEFT MOTOR STOP')
        uart.uart_transmit('MIDDLE FRONT RIGHT MOTOR STOP')
        uart.uart_transmit('MIDDLE FRONT RIGHT MOTOR STOP')
        uart.uart_transmit('MIDDLE BACK LEFT MOTOR STOP')
        uart.uart_transmit('MIDDLE BACK RIGHT MOTOR STOP')
    elif name == 'move':
        if(scale<100):
            scale = scale + 10
        if(scale >=100):
            scale=100
        print(scale)
        motor_list = ['MIDDLE BACK RIGHT SPEED ',
                      'MIDDLE FRONT RIGHT SPEED ',
                      'MIDDLE BACK LEFT SPEED ',
                      'MIDDLE FRONT LEFT SPEED ']
        for i in motor_list:
            send_data = i + str(scale)
            uart.uart_transmit(send_data)
        print(name, "Forward!")
        app.addListItem('list', 'move!!')
        uart.uart_transmit('MIDDLE FRONT LEFT MOTOR FORWARD')
        uart.uart_transmit('MIDDLE FRONT RIGHT MOTOR FORWARD')
        uart.uart_transmit('MIDDLE BACK LEFT MOTOR FORWARD')
        uart.uart_transmit('MIDDLE BACK RIGHT MOTOR FORWARD')


uart_command = {
    '0': {  # ultra_sonic
        '0': 'front_left_sonic',
        '1': 'front_center_sonic',
        '2': 'front_right_sonic',
        '3': 'back_left_sonic',
        '4': 'back_right_sonic'
    },

    '1': {  # photo_transistor
        '0': 'front_left_photo',
        '1': 'front_right_photo'
    },

    '2': {  # encoder
        '0': 'middle_front_left_encoder',
        '1': 'middle_front_right_encoder',
        '2': 'middle_back_left_encoder',
        '3': 'middle_back_right_encoder'
    },

    '3': {  # mpu6050
        '0': 'main_mpu6050_ax',
        '1': 'main_mpu6050_ay',
        '2': 'main_mpu6050_az',
        '3': 'main_mpu6050_gx',
        '4': 'main_mpu6050_gy',
        '5': 'main_mpu6050_gz'
    },

    '4': {  # battery
        '0': 'battery'
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


def thread_web_server():
    ws.start_server(9999)


if __name__ == "__main__":
    try:
        app = gui('Smart Car Control', '800x500')

        # LEFT_1
        app.startFrame('LEFT_1', row=0, column=0)
        app.addLabel('Smart Car Control', row=0, column=1)
        app.stopFrame()

        # LEFT_2
        app.startFrame('LEFT_2', row=1, column=0)
        app.addButton('go', button_press, 0, 1)
        app.addButton('left', button_press, 1, 0)
        app.addButton('right', button_press, 1, 2)
        app.addButton('back', button_press, 2, 1)
        app.addButton('stop', button_press, 1, 1)
        app.addButton('move', button_press, 3, 1)
        app.addButton('Up', button_press, 3, 2)
        app.addButton('Down', button_press, 3, 3)
        app.stopFrame()

        # RIGHT
        app.startFrame('RIGHT', row=0, column=1, rowspan=2)
        app.setFont(10)
        app.addListBox('list')
        app.stopFrame()

        uart = Uart()
        uart_receive = threading.Thread(target=uart_receive_thread)
        uart_receive.start()

        ws = WS(app, uart)
        web_server = threading.Thread(target=thread_web_server)
        web_server.start()

        app.go()

    except Exception as e:
        print(str(e))
