<?php

require_once 'CRM/Core/Page.php';

class CRM_Personalcampaignpagescontacttab_Page_Assigned extends CRM_Core_Page {



  function preProcess() {
    $rows =CRM_Personalcampaignpagescontacttab_BAO_Personalcampaignpagescontacttab::getPcpDataByContactId(202);

    $this->assign('rows', $rows);
  }



  public function run() {
    $this->preProcess();
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('Assigned'));

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    parent::run();
  }
}
