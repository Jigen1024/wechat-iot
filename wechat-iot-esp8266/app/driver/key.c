#include "../include/driver/key.h"

void ICACHE_FLASH_ATTR
key_init(void) {
	gpio_init();
	PIN_FUNC_SELECT(PERIPHS_IO_MUX_GPIO5_U, FUNC_GPIO5);//����GPIO����
	GPIO_DIS_OUTPUT(GPIO_ID_PIN(5));//����GPIO5Ϊ����
	PIN_PULLUP_EN(PERIPHS_IO_MUX_GPIO5_U);//GPIO5ʹ������
}

//
void ICACHE_FLASH_ATTR
key_set_onclick_listener(void (*func1)()) {
	ETS_GPIO_INTR_DISABLE();//�ر��ж�
	ETS_GPIO_INTR_ATTACH(func1, NULL);//�����жϺ���
	gpio_pin_intr_state_set(GPIO_ID_PIN(5), GPIO_PIN_INTR_NEGEDGE);//�����ж�����
}

void ICACHE_FLASH_ATTR
key_start_listener(void) {
	ETS_GPIO_INTR_ENABLE();//���ж�
}
