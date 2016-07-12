<?php
G::LoadClass("plugin");

class twilioPlugin extends PMPlugin{
  
  
  public function twilioPlugin($sNamespace, $sFilename = null){
    $res = parent::PMPlugin($sNamespace, $sFilename);
    $this->sFriendlyName = "Twilio Integration";
    $this->sDescription  = "Twilio integration - custom rest api extension";
    $this->sPluginFolder = "twilio";
    $this->sSetupPage    = "setup";
    $this->iVersion      = 1;
    $this->aWorkspaces   = null;
    return $res;
  }

  public function setup(){}

  public function install(){}
  
  public function enable(){
    $this->enableRestService(true);
  }

  public function disable(){
    $this->enableRestService(false);
  }
  
}
$oPluginRegistry = &PMPluginRegistry::getSingleton();
$oPluginRegistry->registerPlugin("twilio", __FILE__);
