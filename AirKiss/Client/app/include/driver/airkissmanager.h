
#ifndef __AIRKISSMANAGER_H__
#define __AIRKISSMANAGER_H__

#include "smartconfig.h"
#include "airkiss.h"
#include "ets_sys.h"
#include "osapi.h"
#include "ip_addr.h"
#include "espconn.h"
#include "mem.h"
#include "os_type.h"
#include "user_interface.h"
#include "c_types.h"

LOCAL void airkiss_wifilan_time_callback(void);
LOCAL void airkiss_wifilan_recv_callbk(void *, char *, unsigned short);
void airkiss_start_discover(void);
void smartconfig_done(sc_status, void *pdata);
void airkiss_start_now(void);
void airkiss_stop_now(void);

#endif
