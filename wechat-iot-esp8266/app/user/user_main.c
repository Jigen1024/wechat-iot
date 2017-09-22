#include "c_types.h"

#include "driver/key.h"
#include "set.h"
#include "driver/airkissmanager.h"

void ICACHE_FLASH_ATTR
gpio_intr_handler(void *args) {
	ETS_GPIO_INTR_DISABLE();
	//读取GPIO状态寄存器，获取中断信息
	uint32 gpio_status = GPIO_REG_READ(GPIO_STATUS_ADDRESS);
	//清除中断信息
	GPIO_REG_WRITE(GPIO_STATUS_W1TC_ADDRESS, gpio_status);

	uint16_t index = 0;
	os_delay_us(20);
	for( ; ; ) {
		if ( !GPIO_INPUT_GET(GPIO_ID_PIN(5)) ) {
			index++;
		} else {
			break;
		}
		if (index > 800) {
			break;
		}
		os_delay_us(1000);
	}

	if (index < 100) {
//		os_printf("too short\n\n");
	} else if (index < 220) {
		os_printf("short, %d\n\n", index);
	} else if (index < 500) {
		os_printf("normal, %d\n\n", index);
	} else {
		os_printf("long, %d\n\n", index);
		os_printf("start smartconfig\n\n");
		airkiss_start_now();
	}

	ETS_GPIO_INTR_ENABLE();
}

uint32 ICACHE_FLASH_ATTR
user_rf_cal_sector_set(void) {
	return set_sector();
}

void ICACHE_FLASH_ATTR
user_rf_pre_init(void) {
}

void ICACHE_FLASH_ATTR
user_init(void)
{
	key_init();
	key_set_onclick_listener(&gpio_intr_handler);
	key_start_listener();
}
