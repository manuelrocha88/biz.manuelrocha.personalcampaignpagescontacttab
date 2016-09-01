<?php

/**
 * PhpStorm
 *
 * Author: Manuel Rocha
 */
class CRM_Personalcampaignpagescontacttab_BAO_Personalcampaignpagescontacttab extends CRM_PCP_BAO_PCP
{
    /**
     * Class constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get action links.
     *
     * @return array
     *   (reference) of action links
     */
    public static function &pcpLinks() {
        if (!(self::$_pcpLinks)) {

            self::$_pcpLinks['contact'] = array(
                CRM_Core_Action::UPDATE => array(
                    'name' => ts('Edit Your Page'),
                    'url' => 'civicrm/pcp/info',
                    'qs' => 'action=update&reset=1&id=%%pcpId%%&component=%%pageComponent%%',
                    'title' => ts('Configure'),
                ),
            );
        }
        return self::$_pcpLinks;
    }

    public static function getPcpDataByContactId($contactId){

        //total: SELECT SUM(cc.total_amount) as total FROM civicrm_pcp pcp LEFT JOIN civicrm_contribution_soft cs ON ( pcp.id = cs.pcp_id ) LEFT JOIN civicrm_contribution cc ON ( cs.contribution_id = cc.id) WHERE pcp.id = 4 AND cc.contribution_status_id =1 AND cc.is_test = 0





        $links = self::pcpLinks();

        $query = "SELECT pcp.id, pcp.page_id, pcp.page_type, IFNULL(SUM(cc.total_amount),0) as amount_raised, count(cc.total_amount) as no_of_contributions, pcp.title as page_title, pcp.status_id, pcp.goal_amount as target_amount, cp.title as contribution_page_event
FROM civicrm_pcp pcp
LEFT JOIN civicrm_contribution_soft cs ON ( pcp.id = cs.pcp_id )
LEFT JOIN civicrm_contribution cc ON ( cs.contribution_id = cc.id)
LEFT JOIN civicrm_contribution_page cp ON (pcp.page_id = cp.id)
WHERE pcp.contact_id = %1 
group by pcp.id";

        $params = array(1 => array($contactId, 'Integer'));

        $pcpInfoDao = CRM_Core_DAO::executeQuery($query, $params);
        $pcpInfo = array();
        $hide = $mask = array_sum(array_keys($links['contact']));
        $contactPCPPages = array();

        $pcpStatus = CRM_Contribute_PseudoConstant::pcpStatus();
        $approved = CRM_Utils_Array::key('Approved', $pcpStatus);

        while ($pcpInfoDao->fetch()) {
            $mask = $hide;
            if ($links) {
                $replace = array(
                    'pcpId' => $pcpInfoDao->id,
                    'pageComponent' => $pcpInfoDao->page_type,
                );
            }

            $pcpLink = $links['contact'];
            $class = '';

            if ($pcpInfoDao->status_id != $approved || $pcpInfoDao->is_active != 1) {
                $class = 'disabled';
                if (!$pcpInfoDao->is_tellfriend_enabled) {
                    $mask -= CRM_Core_Action::DETACH;
                }
            }

            if ($pcpInfoDao->is_active == 1) {
                $mask -= CRM_Core_Action::ENABLE;
            }
            else {
                $mask -= CRM_Core_Action::DISABLE;
            }
            $action = CRM_Core_Action::formLink($pcpLink, $mask, $replace, ts('more'),
                FALSE, 'pcp.dashboard.active', 'PCP', $pcpInfoDao->id);

            if ($pcpInfoDao->page_type == 'contribute') {
                $pageUrl = CRM_Utils_System::url('civicrm/' . $pcpInfoDao->page_type . '/transact', 'reset=1&id=' . $pcpInfoDao->page_id);
            }
            else {
                $pageUrl = CRM_Utils_System::url('civicrm/' . $pcpInfoDao->page_type . '/register', 'reset=1&id=' . $pcpInfoDao->page_id);
            }

            $pcpInfo[] = array(
                'page_title' => $pcpInfoDao->page_title,
                'pcpId' => $pcpInfoDao->id,
                'contribution_page_or_event' => $pcpInfoDao->contribution_page_event,
                'no_of_contributions' => $pcpInfoDao->no_of_contributions,
                'amount_raised' => $pcpInfoDao->amount_raised,
                'target_amount' => $pcpInfoDao->target_amount,
                'page_url' => $pageUrl,
                'status' => $pcpStatus[$pcpInfoDao->status_id],
                'action' => $action,
                'class' => $class,
            );
        }

        return $pcpInfo;
    }
}