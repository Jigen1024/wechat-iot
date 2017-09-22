#include "../include/driver/key.h"

void ICACHE_FLASH_ATTR
key_init(void) {
	gpio_init();
	PIN_FUNC_SELECT(PERIPHS_IO_MUX_GPIO5_U, FUNC_GPIO5);//设置GPIO功能
	GPIO_DIS_OUTPUT(GPIO_ID_PIN(5));//设置GPIO5为输入
	PIN_PULLUP_EN(PERIPHS_IO_MUX_GPIO5_U);//GPIO5使能上拉
}

//
void ICACHE_FLASH_ATTR
key_set_onclick_listener(void (*func1)()) {
	ETS_GPIO_INTR_DISABLE();//关闭中断
	ETS_GPIO_INTR_ATTACH(func1, NULL);//设置中断函数
	gpio_pin_intr_state_set(GPIO_ID_PIN(5), GPIO_PIN_INTR_NEGEDGE);//设置中断类型
}

void ICACHE_FLASH_ATTR
key_start_listener(void) {
	ETS_GPIO_INTR_ENABLE();//打开中断
}
