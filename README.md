Sms Panel
===================
This repo has been added for used for NikSms and ArianTel webservice.

Installation
------------

-----
If you want you can simply write your own driver and attach it to this library.

And if you want you can publish it in this library by your own name.

If you want create your own driver please flow below steps:

1. Create your class and use psr-7 namespcae.
2. Extend your class from `MehranDashti\sms\abstracts\SmsHelper`
3. Implement your codes.
4. Attach your driver to library as described before
5. Publish your code in `drivers` library

List of drivers
-----
List of driver that published and avilable right now.

| Driver name   | namespace                                | createdBy                                   
| ------------- |:--------------------------:              | :----------------------------------------------------------
| Niksms        | \MehranDashti\sms\drivers\NikSmsPanel    | Mehran Dashti  
| ArianTel      | \MehranDashti\sms\drivers\ArianTel       | Mehran Dashti

config for NikSMS
------
| parameters    | type   | description|
| ------------- | ------ | -------    |
| username      | string | username for auth |
| password      | string | password for auth |
| ref_number    | string | sender number     |

extra config for NikSms
------
| parameters    | type   | description|
| ------------- | ------ | -------    |
| send_on       | string |            |
| send_type     | string |            |
| message_ids   | string |            |


Login Data for ArianTel
------
| parameters    | type   | description       |
| ------------- | ------ | -------           |
| username      | string | username for auth |
| password      | string | password for auth |
| ref_number    | string | sender number     |

extra config for NikSms
------
| parameters    | type   | description|
| ------------- | ------ | -------    |
| udh           | boolean|            |
| is_flash      | string |            |
| rec_id        | array  |            |
| status        | array  |            |

 Be aware use of this library and its driver is alowed for all but you should keep recources and authors names.

Help us to improve
-----
If you create your own driver we become so happy if you pulblish it in our library.


#Sms_panel
