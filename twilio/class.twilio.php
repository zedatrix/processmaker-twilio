<?php
/**
 * class.twilio.php
 *  
 */

  class twilioClass extends PMPlugin{
    function __construct() {
      set_include_path(
        PATH_PLUGINS . 'twilio' . PATH_SEPARATOR .
        get_include_path()
      );
    }

    function setup(){}

    function getFieldsForPageSetup(){}

    function updateFieldsForPageSetup(){}

  }