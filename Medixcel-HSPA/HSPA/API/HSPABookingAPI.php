<?php

namespace Plus91\Medixcel\HSPA\API;

use Ramsey\Uuid\Uuid;
use Plus91\Medixcel\FHIR\API\FHIRPatient;
use Plus91\Medixcel\FHIR\API\FHIRAppointment;

include_once dirname(__FILE__)."/../../classes/class.ScheduleManager.php";
include_once dirname(__FILE__)."/../../funServices.php";
include_once dirname(__FILE__)."/../../classes/class.Bill1.php";
include_once dirname(__FILE__)."/../../classes/class.Patient.php";
include_once dirname(__FILE__)."/../../funOPDBilling.php";
include_once dirname(__FILE__)."/../../funMembershipPlans.php";

class HSPABookingAPI{

    public function select(array $aSelectRequest){
        $iClinicID = $aSelectRequest['message']['order']['provider']['id'] ? $aSelectRequest['message']['order']['provider']['id'] : 0;
        $aOrderServices = $aSelectRequest['message']['order']['items'] ? $aSelectRequest['message']['order']['items'] : [];
        $aClinic = fGetClinicDetailsForClinicID($iClinicID);
        $sCurrentDay = strtolower(date("l"));

        // Body
        $aOrderContext = [];
        $aOrderDetails = [];
        $aBillQuoteDetails = [];
        $aPriceBreakup = [];
        $aClinicDetails = [];
        $iTotalBillAmount = 0;

        $aClinicDetails = array(
            "id" => $iClinicID,
            "descriptor" => [
                "name" => $aClinic['clinic_name']
            ]
        );

        // Update context action
        $aSelectRequest['context']['action'] = "on_select";
        $aSelectRequest['context']['timestamp'] = gmdate('Y-m-d\TH:i:s.u', strtotime(date("Y-m-d H:i:s")));

        foreach ($aOrderServices as $iIndex => $aOrderService) {

            $iServiceID = $aOrderService['id'];
            $iStaffID = $aOrderService['fulfillment_id'];

            $aServiceDetails = fGetServiceDetailsForServiceID($iServiceID);
            $oStaff = (new \Staff($iStaffID));
            $aServiceRateDetails = fGetInternalServiceRatesForService($iServiceID);
            $aAvailabilityTimes = (new \Staff)->getDoctorAvailabilitiesByFilters([
                'iStaffID' => $iStaffID,
                'iClinicID' => $iClinicID,
                'sDay' => $sCurrentDay
            ]);
            $tAvailableFromTime = $aAvailabilityTimes[0]['from_time'];
            $tAvailableToTime = $aAvailabilityTimes[0]['to_time'];

            $aOrderDetails['items'][] = array(
                "id" => $iServiceID,
                "descriptor" => [
                    "name" => $aServiceDetails['service_name']
                ],
                "fulfillment_id" => $iStaffID,
                "provider_id" => $iClinicID
            );

            $aOrderDetails['fulfillment'][] = array(
                "id" => $iStaffID,
                "type" => $aServiceDetails['service_type'],
                "person" => [
                    "id" => $iStaffID,
                    "name" => $oStaff->sStaffName,
                    "gender" => $oStaff->sStaffGender,
                    "image" => null,
                    "cred" => null
                ],
                "agent" => [
                    "id" => $iStaffID,
                    "name" => $oStaff->sStaffName,
                    "gender" => $oStaff->sStaffGender,
                    "image" => null,
                    "cred" => null
                ],
                "time" => [
                    "range" => [
                        "start" => $tAvailableFromTime,
                        "end" => $tAvailableToTime
                    ]
                ]
            );

            $aPriceBreakup[] = array(
                $aServiceDetails['service_name'] => $aServiceRateDetails['rate']
            );
            $iTotalBillAmount = $iTotalBillAmount + $aServiceRateDetails['rate'];
        }

        $aBillQuoteDetails['price'] = array(
            "currency" => "INR",
            "value" => $iTotalBillAmount,
            "breakup" => $aPriceBreakup
        );

        $aOnSelectRequestResource = array(
            "context" => $aSelectRequest['context'],
            "message" => [
                "order" => [
                    "provider" => $aClinicDetails,
                    "items" => $aOrderDetails['items'],
                    "fulfillment" => $aOrderDetails['fulfillment'],
                    "quote" => $aBillQuoteDetails
                ]
            ]
        );

        return $aOnSelectRequestResource;
    }

    public function init(array $aInitRequest){
        $iClinicID = $aInitRequest['iClinicID'] ? $aInitRequest['iClinicID'] : 0;
        $aOrderDetails = $aInitRequest['message']['order'] ? $aInitRequest['message']['order'] : [];
        $aInitBiilingData = $aInitRequest['message']['order']['billing'] ? $aInitRequest['message']['order']['billing'] : [];
        $aContext = $aInitRequest['context'] ? $aInitRequest['context'] : [];
        $iServiceID = $aOrderDetails['item']['id'] ? $aOrderDetails['item']['id'] : 0;
        $iStaffID = $aOrderDetails['fulfillment']['agent']['id'] ? $aOrderDetails['fulfillment']['agent']['id'] : 0;
        $aStartTime = $aOrderDetails['fulfillment']['start'] ? $aOrderDetails['fulfillment']['start'] : 0;
        $aEndTime = $aOrderDetails['fulfillment']['end'] ? $aOrderDetails['fulfillment']['end'] : 0;

        // Track
        $sMessageID = $aContext['message_id'] ? $aContext['message_id'] : '';
        $sTransactionID = $aContext['transaction_id'] ? $aContext['transaction_id'] : '';

        $aClinic = fGetClinicDetailsForClinicID($iClinicID);
        $sCurrentDay = strtolower(date("l"));

        // Body
        $aOrderContext = [];
        $aOrderDetails = [];
        $aBillQuoteDetails = [];
        $aPriceBreakup = [];
        $aClinicDetails = [];
        $aPaymentDetails = [];
        $iTotalBillAmount = 0;

        // clinic details
        $aClinicDetails = array(
            "id" => $iClinicID,
            "descriptor" => [
                "name" => $aClinic['clinic_name']
            ]
        );

        // Update context action
        $aInitRequest['context']['action'] = "on_init";
        $aSelectRequest['context']['timestamp'] = gmdate('Y-m-d\TH:i:s.u', strtotime(date("Y-m-d H:i:s")));

        $aServiceDetails = fGetServiceDetailsForServiceID($iServiceID);
        $oStaff = (new \Staff($iStaffID));
        $aServiceRateDetails = fGetInternalServiceRatesForService($iServiceID);
        $sStaffQualification = $oStaff->sQualification ? $oStaff->sQualification : null;
        $sConsultationType = "PhysicalConsultation";

        // Check if service is teleconsultation
        if($aServiceDetails['is_video_teleconsultation'] || $aServiceDetails['is_audio_teleconsultation']){
            $sConsultationType = "Teleconsultation";
        }

        $aOrderDetails['item'] = array(
            "id" => $aServiceDetails['service_id'],
            "descriptor" => [
                "name" => $aServiceDetails['service_name']
            ],
            "price" => [
                "currency" => "INR",
                "value" => $aServiceRateDetails['rate']
            ],
            "fulfillment_id" => $iStaffID
        );

        $aOrderDetails['fulfillment'] = array(
            "id" => $iStaffID,
            "type" => $sConsultationType,
            "agent" => [
                "id" => $iStaffID,
                "name" => $oStaff->sStaffName,
                "gender" => $oStaff->sStaffGender,
                "tags" => [
                    "@abdm/gov/in/first_consultation" => $aServiceRateDetails['rate'],
                    "@abdm/gov/in/upi_id" => "9999999999@okhdfc",
                    "@abdm/gov/in/follow_up" => null,
                    "@abdm/gov/in/experience" => null,
                    "@abdm/gov/in/languages" => "Eng, Hin",
                    "@abdm/gov/in/speciality" => $sConsultationType,
                    "@abdm/gov/in/lab_report_consultation" => null,
                    "@abdm/gov/in/education" => $sStaffQualification,
                    "@abdm/gov/in/hpr_id" => null,
                    "@abdm/gov/in/signature" => null
                ]
            ],
            "start" => $aStartTime,
            "end" => $aEndTime
        );

        // consultation price breakup
        $aPriceBreakup = array(
            "quote" => [
                "price" => [
                    "currency" => "INR",
                    "value" => $aServiceRateDetails['rate'],
                ],
                "breakup" => [
                    [
                        "title" => "Consultation",
                        "price" => [
                            "currency" => "INR",
                            "value" => $aServiceRateDetails['rate'],
                        ]
                    ]
                ]
            ]
        );

        // Payment details
        $aPaymentDetails = array(
            "uri" => "payto://ban/98273982749428?amount=INR:110&ifsc=SBIN0000575&message=hello",
            "type" => "ON-ORDER",
            "status" => "PENDING",
            "tl_method" => null,
            "params" => null
        );

        $sOrderID = (string) Uuid::uuid4();
        $bResult = (new \ScheduleManager)->mapHSPAReceivedScheduleDetails($sOrderID, $sMessageID, $sTransactionID, 0);

        $aOnInitRequestResource = array(
            "context" => $aInitRequest['context'],
            "message" => [
                "order" => [
                    "id" => $sOrderID,
                    "state" => "PROVISIONALLY_BOOKED",
                    "provider" => $aClinicDetails,
                    "item" => $aOrderDetails['item'],
                    "billing" => $aInitBiilingData,
                    "fulfillment" => $aOrderDetails['fulfillment'],
                    "quote" => $aPriceBreakup,
                    "payment" => $aPaymentDetails
                ]
            ]
        );

        return $aOnInitRequestResource;
    }

    public function confirm(array $aConfirmRequest){

        $aContext = $aConfirmRequest['context'];
        $sDomain = $aContext['domain'];
        $sCountry = $aContext['country'];
        $sCity = $aContext['city'];
        $sAction = $aContext['action'];
        $sCoreVersion = $aContext['core_version'];
        $sConsumerID = $aContext['consumer_id'];
        $sConsumerURI = $aContext['consumer_uri'];
        $sProviderID = $aContext['provider_id'];
        $sProviderURI = $aContext['provider_uri'];
        $sTransactionID = $aContext['transaction_id'];
        $sMessageID = $aContext['message_id'];
        $sTimeStamp = $aContext['timestamp'];
        $sKey = $aContext['key'];
        $sTTL = $aContext['ttl'];
        $aScheduleInfo = $aConfirmRequest['message']['order'];
        $aData = [];

        if (!$aScheduleInfo) {
            return [
                "context" => $aConfirmRequest['context'],
                "message" => [
                    "order" => [],
                ],
                "error" => [
                    "type" => "Bad request",
                    "code" => 400,
                    "path" => "",
                    "message" => "No confirmation details found.",
                ]
            ];
        }

        $iClinicID = $aScheduleInfo['provider']['id'] ? $aScheduleInfo['provider']['id'] : DEFAULT_CLINIC_ID;
        $aServiceItem = $aScheduleInfo['item'];
        $aPatientInfo = $aScheduleInfo['billing'];
        $sConsultationType = $aScheduleInfo['fulfillment']['type'];
        $tStartTime = $aScheduleInfo['fulfillment']['start']['time']['timestamp'];
        $tEndTime = $aScheduleInfo['fulfillment']['end']['time']['timestamp'];
        $iPatientID = 0;

        $aPatients = (new \Patient)->getPatientByFilters([
            "patient_name" => $aPatientInfo['name']
        ]);

        if (count($aPatients) >= 1) {
            $aPatient = array_pop($aPatients);
            $iPatientID = $aPatient ? $aPatient['patient_id'] : 0;
        }

        //! Register new patient..
        if (!$iPatientID) {
            try {
                $aPatientName = explode(" ", $aPatientInfo["name"]);
                $sPatientName = $aPatientInfo['name'];
                $sFirstName = $aPatientName[0];
                $sMiddleName = $aPatientName[1] ? $aPatientName[1] : '';
                $sLastName = $aPatientName[count($aPatientName) - 1];
                $sGender = 'NA';
                $dDOB = 'NA';
                $sUID = '';
                $sEmail = $aPatientInfo['email'] ? $aPatientInfo['email'] : '';
                $aMobileNo = $aPatientInfo['phone'] ? $aPatientInfo['phone'] : '';
                $sAddress = $aPatientInfo['address']['name'] ? $aPatientInfo['address']['name'] : '';

                $aPatientData = [
                    'name' => [
                        [
                            'text' => $sPatientName,
                            'family' => $sLastName,
                            'given' => $sMiddleName,
                            'prefix' => '',
                        ],
                    ],
                    'telecom' => [
                        [
                            'system' => 'email',
                            'value' => $sEmail,
                        ],
                        [
                            'system' => 'phone',
                            'use' => 'mobile',
                            'value' => $aMobileNo,
                        ],
                    ],
                    'gender' => $sGender,
                    'address' => $sAddress,
                    'photo' => null,
                    'managingOrganization' => null,
                    'birthDate' => $dDOB,
                    'deceasedDateTime' => null,
                    'deceasedBoolean' => null,
                    'blood_group' => null,
                    'digital_health_id' => $sUID,
                    'preffered_language' => null,
                    'ethnicity' => null,
                ];

                $oFHIRPatientResponse = (new FHIRPatient)->addNewPatient($aPatientData);
                $aFHIRPatientResponse = json_decode(json_encode($oFHIRPatientResponse), true);

                if (!$aFHIRPatientResponse['error']) {
                    $iPatientID = $aFHIRPatientResponse['id'];
                } else {
                    return [
                        "context" => $aConfirmRequest['context'],
                        "message" => [
                            "order" => [],
                        ],
                        "error" => [
                            "type" => "Internal server error",
                            "code" => 500,
                            "path" => "",
                            "message" => "Error while registering patient. ".$aFHIRPatientResponse['message'],
                        ]
                    ];
                }
            } catch (Exception $e) {
                return [
                    "context" => $aConfirmRequest['context'],
                    "message" => [
                        "order" => [],
                    ],
                    "error" => [
                        "type" => "Internal server error",
                        "code" => 500,
                        "path" => "",
                        "message" => "Error while registering patient. ".$e->getMessage(),
                    ]
                ];
            }
        }

        $aServices = [];
        $aServiceItemInfo = [];

        $iServiceID = (int) $aServiceItem['id'];
        $iStaffID = (int) $aScheduleInfo['fulfillment']['agent']['id'];
        $aServiceDetails = fGetServiceDetailsForServiceID($iServiceID);

        if ($iServiceID && $iStaffID) {
            $aServices[] = [
                'practitioner' => [
                    'id' => $iStaffID
                ],
                'specialty' => [
                    'id' => $iServiceID
                ]
            ];
            $aServiceItemInfo = [
                "id" => $iServiceID,
                "descriptor" => [
                    "name" => $aServiceDetails['service_name'],
                ],
                "fulfillment_id" => $iStaffID,
                "provider_id" => $iClinicID,
            ];
        }

        $aSchedule = [
            'start' => date("Y-m-d H:i:s", strtotime($tStartTime)),
            'end' => date("Y-m-d H:i:s", strtotime($tEndTime)),
            'comment' => "Scheduled via HSPA interface",
            'organization' => [
                'id' => $iClinicID
            ],
            'services' => $aServices
        ];

        $aResult = (new FHIRAppointment)->addNewSchedule($iPatientID, $aSchedule);

        if(!$aResult['id']){
            return [
                "context" => $aConfirmRequest['context'],
                "message" => [
                    "order" => [],
                ],
                "error" => [
                    "type" => "Internal server error",
                    "code" => 500,
                    "path" => "",
                    "message" => "Error while saving visits. ".$aResult['message'],
                ]
            ];
        }

        
        $sOrderID = (new \ScheduleManager)->fGetHSPAOrderIDByTransactionID($sTransactionID);
        $iScheduleID = $aResult['id'];

        // generate teleconsultation service if the type of service is teleconsultation
        if(strtolower($sConsultationType) == "teleconsultation"){
            $oSchedule = new \ScheduleManager($iScheduleID);
            $aScheduleAppointments = $oSchedule->aScheduleAppointmentData;

            try {
                foreach($aScheduleAppointments as $aScheduleAppointment){
                    (new \ScheduleManager)->scheduleTeleconsultationConsultationForAppointment($aScheduleAppointment['schedule_appintment_id']);
                }
            } catch (\Throwable $th) {

            }
        }

        // Order ID validation
        if(!$sOrderID){
            return [
                "context" => $aConfirmRequest['context'],
                "message" => [
                    "order" => [],
                ],
                "error" => [
                    "type" => "Internal server error",
                    "code" => 500,
                    "path" => "",
                    "message" => "Error while saving visits. Unable to update Visit ID against Order ID",
                ]
            ];
        }

        $bResult = (new \ScheduleManager)->fUpdateHSPAOrderScheduleID($sOrderID, $iScheduleID);
        $aBillDetails = fGetBillDetailsForSchedule($iScheduleID);
        $iBillAmount = $aBillDetails['grand_amount'] ? $aBillDetails['grand_amount'] : 0;
        $oPatient = new \Patient($iPatientID);
        $oStaff = new \Staff($iStaffID);
        $sClinicName = fGetClinicDetailsForClinicID($iClinicID)['clinic_name'];

        if(strtolower($sConsultationType) == "teleconsultation"){
            // Get teleconsultation url
            $iSchAppID = (new \ScheduleManager)->fGetAppointmentID($iScheduleID,$iServiceID);
            $aMeetings = (new \TeleconsultationMeeting)->getMeetingsByEntity(1,$iSchAppID);
            $oTeleConsulation = $aMeetings[0];

            $aConsultationTags = array(
                "@abdm/gov/in/teleconf_url" => $oTeleConsulation->sMeetingURL ? $$oTeleConsulation->sMeetingURL : 'https://meet.google.com/mtj-wucm-vso'
            );
        }

        if ($bResult) {
            $aData = [
                "context" => [
                    "domain" => $sDomain,
                    "country" => $sCountry,
                    "city" => $sCity,
                    "action" => "on_confirm",
                    "core_version" => $sCoreVersion,
                    "transaction_id" => $sTransactionID,
                    "message_id" => $sMessageID,
                    "timestamp" => gmdate('Y-m-d\TH:i:s.u', strtotime(date("Y-m-d H:i:s")))
                ],
                "message" => [
                    "order" => [
                        "id" => $sOrderID,
                        "state" => "CONFIRMED",
                        "provider" => [
                            "id" => $iClinicID,
                            "descriptor" => [
                                "name" => $sClinicName ? $sClinicName : '',
                            ]
                        ],
                        "item" => $aServiceItemInfo,
                        "billing" => $aScheduleInfo['billing'],
                        "fulfillment" => [
                            "id" => $iStaffID,
                            "type" => "NA",
                            "person" => [
                                "id" => $iStaffID,
                                "name" => $oStaff->sStaffName,
                                "gender" => $oStaff->sGender,
                                "image" => "",
                                "cred" => "",
                            ],
                            "agent" => [
                                "id" => $iStaffID,
                                "name" => $oStaff->sStaffName,
                                "gender" => $oStaff->sGender,
                                "image" => "",
                                "cred" => "",
                                "tags" => [
                                    "@abdm/gov/in/first_consultation" => $iBillAmount,
                                    "@abdm/gov/in/upi_id" => "9999999999@okhdfc",
                                    "@abdm/gov/in/follow_up" => null,
                                    "@abdm/gov/in/experience" => null,
                                    "@abdm/gov/in/languages" => "Eng, Hin",
                                    "@abdm/gov/in/speciality" => $sConsultationType,
                                    "@abdm/gov/in/lab_report_consultation" => null,
                                    "@abdm/gov/in/education" => $oStaff->sQualification ? $oStaff->sQualification : null,
                                    "@abdm/gov/in/hpr_id" => null,
                                    "@abdm/gov/in/signature" => null,
                                ]
                            ],
                            "state" => [
                                "descriptor" => [
                                    "code" => "",
                                ],
                            ],
                            "time" => [
                                "range" => [
                                    "start" => $aSchedule['start'],
                                    "end" => $aSchedule['end'],
                                ],
                            ],
                            "tags" => $aConsultationTags
                        ],
                        "quote" => [
                            "price" => [
                                "currency" => "INR",
                                "value" => $iBillAmount,
                                "breakup" => [],
                            ],
                        ],
                        "payment" => [
                            "uri" => "",
                            "type" => "",
                            "status" => "",
                        ],
                    ]
                ]
            ];
        }

        if (!$aData) {
            return [
                "context" => $aConfirmRequest['context'],
                "message" => [
                    "order" => [],
                ],
                "error" => [
                    "type" => "Internal server error",
                    "code" => 500,
                    "path" => "",
                    "message" => "Error while saving visits. Empty set.",
                ]
            ];
        }

        return $aData;
    }

    public function status(array $aStatusRequest){
        $aContext = $aStatusRequest['context'];
        $sDomain = $aContext['domain'];
        $sCountry = $aContext['country'];
        $sCity = $aContext['city'];
        $sAction = $aContext['action'];
        $sCoreVersion = $aContext['core_version'];
        $sConsumerID = $aContext['consumer_id'];
        $sConsumerURI = $aContext['consumer_uri'];
        $sProviderID = $aContext['provider_id'];
        $sProviderURI = $aContext['provider_uri'];
        $sTransactionID = $aContext['transaction_id'];
        $sMessageID = $aContext['message_id'];
        $sTimeStamp = $aContext['timestamp'];
        $sKey = $aContext['key'];
        $sTTL = $aContext['ttl'];
        $sOrderID = $aStatusRequest['message']['order_id'];
        $aData = [];
        $iScheduleID = (new \ScheduleManager)->getScheduleIDByHSPAOrderID($sOrderID);

        if (!$sOrderID || !$iScheduleID) {
            return [
                "context" => $aStatusRequest['context'],
                "message" => [
                    "order" => [],
                ],
                "error" => [
                    "type" => "Bad request",
                    "code" => 400,
                    "path" => "",
                    "message" => "Invalid order id.",
                ]
            ];
        }

        $oSchedule = new \ScheduleManager($iScheduleID);
        $iClinicID = $oSchedule->iClinicID;
        $iPatientID = $oSchedule->iPatientID;
        $sClinicName = fGetClinicDetailsForClinicID($iClinicID)['clinic_name'];
        $dScheduleDate = $oSchedule->dDBScheduleDate;
        $tStartTime = $oSchedule->tScheduleTime;
        $tEndTime = $oSchedule->tScheduleEndTime;
        $dtStartDateTime = date("Y-m-d H:i:s", strtotime($dScheduleDate.' '.$tStartTime));
        $dtEndDateTime = date("Y-m-d H:i:s", strtotime($dScheduleDate.' '.$tEndTime));
        $aBillDetails = fGetBillDetailsForSchedule($iScheduleID);
        $iBillAmount = $aBillDetails['grand_amount'] ? $aBillDetails['grand_amount'] : 0;
        $aScheduleAppointmentData = $oSchedule->aScheduleAppointmentData;
        $aServiceItemInfo = [];
        foreach ($aScheduleAppointmentData as $iServiceIndex => $aServiceData) {
            $iServiceID = $aServiceData['service_id'];
            $sServiceName = $aServiceData['service_name'];
            $iStaffID = $aServiceData['staff_id'];
            if ($iServiceID && $iStaffID) {
                $aServiceItemInfo[] = [
                    "id" => $iServiceID,
                    "descriptor" => [
                        "name" => $sServiceName ? $sServiceName : '',
                    ],
                    "fulfillment_id" => $iStaffID,
                    "provider_id" => $iClinicID,
                ];
            }
        }
        $oPatient = new \Patient($iPatientID);

        $sScheduleStatusCode = "";

        if ($oSchedule->iIsScheduleCompleted == 1) {
            $sScheduleStatusCode = "CONSULTATION_COMPLETE";
        } else if ($oSchedule->iIsScheduleCompleted == 2) {
            $sScheduleStatusCode = "CONSULTATION_STARTED";
        } else {
            $sScheduleStatusCode = "IN_DWR";
        }

        if ($sScheduleStatusCode) {
            $aData = [
                "context" => [
                    "domain" => $sDomain,
                    "country" => $sCountry,
                    "city" => $sCity,
                    "action" => $sAction,
                    "core_version" => $sCoreVersion,
                    "transaction_id" => $sTransactionID,
                    "message_id" => $sMessageID,
                    "timestamp" => date("Y-m-d H:i:s")
                ],
                "message" => [
                    "order" => [
                        "id" => $sOrderID,
                        "provider" => [
                            "id" => $iClinicID,
                            "descriptor" => [
                                "name" => $sClinicName ? $sClinicName : '',
                            ]
                        ],
                        "state" => "CONFIRMED",
                        "items" => $aServiceItemInfo,
                        "billing" => [
                            "name" => $oPatient->sPatientName,
                            "address" => [
                                "door" => "",
                                "building" => "",
                                "street" => $oPatient->sAddress ? $oPatient->sAddress : "",
                                "area_code" => $oPatient->iPincode ? $oPatient->iPincode : "",
                            ],
                            "email" => $oPatient->sEmail ? $oPatient->sEmail : "",
                            "phone" => $oPatient->iMobile ? $oPatient->iMobile : "",
                        ],
                        "fulfillment" => [
                            "id" => $iStaffID,
                            "type" => "NA",
                            "person" => [
                                "id" => $iStaffID,
                                "name" => $oStaff->sStaffName,
                                "gender" => $oStaff->sGender,
                                "image" => "",
                                "cred" => "",
                            ],
                            "state" => [
                                "descriptor" => [
                                    "code" => $sScheduleStatusCode,
                                ],
                            ],
                            "time" => [
                                "range" => [
                                    "start" => gmdate('Y-m-d\TH:i:s.u', strtotime($dtStartDateTime)),
                                    "end" => gmdate('Y-m-d\TH:i:s.u', strtotime($dtEndDateTime)),
                                ],
                            ],
                        ],
                        "quote" => [
                            "price" => [
                                "currency" => "INR",
                                "value" => $iBillAmount,
                                "breakup" => [],
                            ],
                        ],
                        "payment" => [
                            "uri" => "",
                            "type" => "",
                            "status" => "",
                        ],
                    ]
                ]
            ];
        }

        if (!$aData) {
            return [
                "context" => $aStatusRequest['context'],
                "message" => [
                    "order" => [],
                ],
                "error" => [
                    "type" => "Bad request",
                    "code" => 400,
                    "path" => "",
                    "message" => "No status found.",
                ]
            ];
        }

        return $aData;
    }
}