<?php
namespace Services\Api\twilio;

use Luracast\Restler\RestException;
use ProcessMaker\Services\Api;
use \ProcessMaker\Util;

/**
 * @protected
 */

class Sms extends Api{
    /**
     * @url PUT /sms/routeCase/:app_uid
     * @param string $app_uid {@min 32}{@max 32}
     * @param string $phone_number {@from body}
     */

    public function routeCase($app_uid, $phone_number = null){
        try {
            /**
             * First, we need to check if the phone number provided is valid
             * If it isn't then we throw an exception
             */
            if($phone_number == null)
                throw new \Exception('Phone number is null. Please provide a valid phone number.');
            //We need to include the Twilio SDK
            require_once(PATH_PLUGINS . 'twilio/vendor/twilio/sdk/Services/Twilio.php');
            //Then we need to provide the Account ID and Auth Token - these are taken from your Twilio developer account
            $account_sid = '<acount-id>';
            $auth_token = '<auth-token>';
            //Let's instantiate the SDK now
            $twilioClient = new \Services_Twilio($account_sid, $auth_token);
            //Let's now instantiate the ProcessMaker rest controller for the cases endpoints
            $case = new \ProcessMaker\BusinessModel\Cases();
            //We need to get the currently logged in users ID so that we can start the case as the user making the rest request
            $userUid = $this->getUserId();
            //We need to get the general case information so that we can provide to the requestor the case number
            /**
             * @Note we are passing two parameters
             * 1: the application UID that we passed from the request
             * 2: the requestors user UID that we just got, so that the case is started as the requesting user
             */
            $caseInfo = $case->getCaseInfo($app_uid, $userUid);
            //Now, we send the message using the Twilio SDK
            $sms = $twilioClient->account->messages->create(array(
                //We are now using the phone number provided by the request
                'To' => $phone_number,
                //Add here the Twilio phone number that you have
                'From' => "<twilio-phone-number>",
                //The message we send through Twilio. Note that we added the case number to the message
                'Body' => "The ProcessMaker REST API has successfully routed Case #" . $caseInfo->app_number,
            ));

            //We check if there was not an error code. If there was, we return a simple message that there was an error sending the sms.

            if($sms->error_code !== null)
                throw new \Exception('Error sending sms. Case not routed.');

            $del_index = \AppDelegation::getCurrentIndex($app_uid);

            $case->updateRouteCase($app_uid, $userUid, $del_index);

            return array("response" => "success");

        } catch (\Exception $e) {
            throw (new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage()));
        }


    }
}