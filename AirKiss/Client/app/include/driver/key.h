
#ifndef __KEY_H__
#define __KEY_H__

#include "gpio.h"
#include "c_types.h"
#include "user_interface.h"

void key_init(void);
void key_set_onclick_listener(void (*)());
void key_start_listener(void);

#endif
