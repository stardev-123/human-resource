<?php return array (
    'welcome-email' => array(
    	'category' => 'user',
      	'subject' => 'Welcome Email | [COMPANY_NAME]',
        'fields' => 'NAME, USERNAME, EMAIL, PASSWORD, COMPANY_NAME, COMPANY_EMAIL, COMPANY_PHONE, COMPANY_WEBSITE, COMPANY_ADDRESS, COMPANY_LOGO, CURRENT_DATE,CURRENT_DATE_TIME'
    ),
    'birthday-email' => array(
      'category' => 'user',
      'subject' => 'Happy Birthday [NAME] | [COMPANY_NAME]'
    ),
    'anniversary-email' => array(
      'category' => 'user',
      'subject' => 'Wish You a Very Happy Anniversary [NAME] | [COMPANY_NAME]'
    ),
    'payroll' => array(
      'category' => 'payroll',
      'subject' => 'Payroll Generated | [COMPANY_NAME]'
    ),
);
