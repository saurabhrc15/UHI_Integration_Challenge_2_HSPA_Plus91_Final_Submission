<?php

namespace Plus91\Medixcel\HSPA\API;

use Ramsey\Uuid\Uuid;

include_once dirname(__FILE__)."/../../classes/class.ScheduleManager.php";
include_once dirname(__FILE__)."/../../funServices.php";
include_once dirname(__FILE__)."/../../classes/class.Bill1.php";
include_once dirname(__FILE__)."/../../funOPDBilling.php";
include_once dirname(__FILE__)."/../../funMembershipPlans.php";

class HSPASearchAPI{

    public function search(array $aSearchRequest){
        $iCategoryID = $aSearchRequest['category'] ? $aSearchRequest['category'] : 1;
        $sMessageID = $aSearchRequest['context']['message_id'] ? $aSearchRequest['context']['message_id'] : '';
        $sTransactionID = $aSearchRequest['context']['transaction_id'] ? $aSearchRequest['context']['transaction_id'] : '';
        $sCurrentDay = strtolower(date("l"));
        $aItems = [];
        $aData = [];

        if (!$sTransactionID) {
            return [
                "context" => $aSearchRequest['context'],
                "message" => [
                    "catalog" => [],
                ],
                "error" => [
                    "type" => "Bad request",
                    "code" => 400,
                    "path" => "",
                    "message" => "No Transaction Id found.",
                ]
            ];
        }

        // Filters
        $sFilterServiceName = $aSearchRequest['message']['intent']['item']['descriptor']['name'];
        $iClinicID = $aSearchRequest['iClinicID'];
        $sFilterStaffName = $aSearchRequest['message']['intent']['fulfillment']['person']['descriptor']['name'];
        $sFilterStaffNameWithAgent = $aSearchRequest['message']['intent']['fulfillment']['agent']['name'];
        $sFilterServiceType = $aSearchRequest['message']['intent']['fulfillment']['type'];
        $tFilterFromTime = $aSearchRequest['message']['intent']['fulfillment']['start']['time']['timestamp'];
        $tFilterToTime = $aSearchRequest['message']['intent']['fulfillment']['end']['time']['timestamp'];

        if (!$sFilterStaffName && $sFilterStaffNameWithAgent) {
            $sFilterStaffName = $sFilterStaffNameWithAgent;
        }

        $iIsTeleconsultation = strtolower($sFilterServiceType) == "teleconsultation" ? 1 : 0;
        $sFilterServiceType = "consultation";

        $aSearchFilters = array(
            'service_name' => $sFilterServiceName ?? '',
            'clinics' => $iClinicID ? implode(", ", array($iClinicID)) : [],
            'staff_name' => $sFilterStaffName ?? '',
            'service_type' => $sFilterServiceType ?? '',
            'from_time' => $tFilterFromTime ? date("H:i:s", strtotime($tFilterFromTime)) : null,
            'to_time' => $tFilterToTime ? date("H:i:s", strtotime($tFilterToTime)) : null,
            'is_teleconsultation' => $iIsTeleconsultation,
            'available_day' => $sCurrentDay
        );

        $aServices = searchServiceDetails($aSearchFilters);

        if (empty($aServices)) {
            return [
                "context" => $aSearchRequest['context'],
                "message" => [
                    "catalog" => [],
                ],
                "error" => [
                    "type" => "Ok",
                    "code" => 200,
                    "path" => "",
                    "message" => "No item found",
                ]
            ];
        }

        // On search request details
        $aFulfillmentDetails = [];
        $aItemDetails = [];
        $aServiceCatalog = [];

        foreach ($aServices as $iIndex => $aService) {

            $sConsultationType = $iIsTeleconsultation == 1 ? "Teleconsultation" : "Consultation";
            $iCurrentServiceID = $aService['service_id'];
            $aServiceRateDetails = fGetInternalServiceRatesForService($iCurrentServiceID);
            $sStaffQualification = $aService['qualification'] ? $aService['qualification'] : null;

            $aItemDetails[] = array(
                "id" => $aService['service_id'],
                "descriptor" => [
                    "name" => $aService['service_name']
                ],
                "price" => [
                    "currency" => "INR",
                    "value" => $aServiceRateDetails['rate']
                ],
                "fulfillment_id" => $aService['staff_id']
            );

            $aFulfillmentDetails[] = array(
                "id" => $aService['staff_id'],
                "type" => $sConsultationType,
                "agent" => [
                    "id" => $aService['staff_id'],
                    "name" => $aService['staff_name'],
                    "gender" => "M",
                    "tags" => [
                        "@abdm/gov/in/first_consultation" => $aServiceRateDetails['rate'],
                        "@abdm/gov/in/upi_id" => "9999999999@okhdfc",
                        "@abdm/gov/in/follow_up" => null,
                        "@abdm/gov/in/experience" => null,
                        "@abdm/gov/in/languages" => "Eng, Hin",
                        "@abdm/gov/in/speciality" => $aService['service_type_category_name'],
                        "@abdm/gov/in/lab_report_consultation" => null,
                        "@abdm/gov/in/education" => $sStaffQualification,
                        "@abdm/gov/in/hpr_id" => null,
                        "@abdm/gov/in/signature" => null
                    ]
                ],
                "start" => [
                    "time" => [
                        "timestamp" => gmdate('\TH:iP', strtotime($aService['from_time']))//"T15:28+05:30"
                    ]
                ],
                "end" => [
                    "time" => [
                        "timestamp" => gmdate('\TH:iP', strtotime($aService['to_time']))//"T15:28+05:30"
                    ]
                ]
            );
        }

        // Get clinic details
        $aClinic = fGetClinicDetailsForClinicID($iClinicID);

        $aServiceCatalog = array(
            'descriptor' => [
                'name' => $aClinic['clinic_name']
            ],
            'items' => $aItemDetails,
            'fulfillments' => $aFulfillmentDetails
        );

        $aOnSearchResponse = array(
            'context' => [
                "domain" => "Health",
                "country" => "IND",
                "city" => "PUNE",
                "action" => "on_search",
                "core_version" => "NA",
                "message_id" => $sMessageID,
                "transaction_id" => $sTransactionID,
                "timestamp" => gmdate('Y-m-d\TH:i:s.u', strtotime(date("Y-m-d H:i:s")))
            ],
            "message" => [
                "catalog" => $aServiceCatalog
            ]
        );

        return $aOnSearchResponse;
    }
}