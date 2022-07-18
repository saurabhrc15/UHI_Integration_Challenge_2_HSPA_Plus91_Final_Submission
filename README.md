# UHI_Integration_Challenge_2_HSPA_Plus91_Final_Submission
Execute an end to end booking for a Tele-consultation OPD appointment, using an HSPA, resulting in an appointment confirmation 

# Teleconsultation Consultation Booking Document

# Plus91 HSPA App Microservice [Folder - medixcel-uhi]

Objective of this microservice is to handle the requests coming from gateway & EUAs & forwarding it to
the correct hospital which is using plus91 EMR software.

Workflow:

1: The microservice manages list of all the hospitals which used plus91 EMR software
2: For search request
    Microservice sends the search request to all registered hospitals with plus91 & sends search response back to the EUAs

3: The booking workflow requests:
    Microservice forwards the the requests to the correct hospital & sends the response back to the requesting EUA.

# Plus91 HSPA App Side Code [Folder - Medixcel-HSPA]

Objective of this repository is to process all the requests coming from above microservice & forward the response 
back to microservice. Then the microservice returns the response to the corresponsing EUA.


# References
![alt text](https://github.com/saurabhrc15/UHI_Integration_Challenge_2_HSPA_Plus91_Final_Submission/blob/main/img/Image1.png?raw=true)
![alt text](https://github.com/saurabhrc15/UHI_Integration_Challenge_2_HSPA_Plus91_Final_Submission/blob/main/img/Image2.png?raw=true)
![alt text](https://github.com/saurabhrc15/UHI_Integration_Challenge_2_HSPA_Plus91_Final_Submission/blob/main/img/Image3.png?raw=true)


# Our HSPA Credentials
```json
{
    "provider_id" : "plus91-HSPA",
    "provider_url" : "https://plus91hq.ddns.net/medixcel_uhi"
}
```

# Search Request
```json
{
    "message": {
        "intent": {
            "fulfillment": {
                "agent": {
                    "name": "vijay"
                }
            }
        },
        "order": null,
        "catalog": null,
        "order_id": null
    },
    "context": {
        "domain": "Health",
        "country": "IND",
        "city": "Pune",
        "action": "search",
        "timestamp": "2022-07-18T05:26:16.689Z",
        "core_version": "0.7.1",
        "consumer_id": "plus91-EUA",
        "consumer_uri": "https://c32f-2409-4042-4b00-ade3-b757-3bf1-ff61-d120.in.ngrok.io/api/v1",
        "transaction_id": "b2e9f303-cb4a-47de-8a8f-5ff9fb86f591",
        "message_id": "4fb2cefe-2d24-4b00-aa1c-f35b0760a7a5"
    }
}
```

# On Search Request
```json
{
    "context": {
        "domain": "Health",
        "country": "IND",
        "city": "PUNE",
        "action": "on_search",
        "core_version": "NA",
        "message_id": "4fb2cefe-2d24-4b00-aa1c-f35b0760a7a5",
        "transaction_id": "b2e9f303-cb4a-47de-8a8f-5ff9fb86f591",
        "timestamp": "2022-07-18T05:44:09.000000"
    },
    "message": {
        "catalog": {
            "descriptor": {
                "name": null
            },
            "items": [
                {
                    "id": "1",
                    "descriptor": {
                        "name": "Cardiology"
                    },
                    "price": {
                        "currency": "INR",
                        "value": "12"
                    },
                    "fulfillment_id": "6"
                },
                {
                    "id": "2",
                    "descriptor": {
                        "name": "Cardiology Follow Up"
                    },
                    "price": {
                        "currency": "INR",
                        "value": null
                    },
                    "fulfillment_id": "6"
                },
                {
                    "id": "3",
                    "descriptor": {
                        "name": "Chest Physician"
                    },
                    "price": {
                        "currency": "INR",
                        "value": "150"
                    },
                    "fulfillment_id": "6"
                },
                {
                    "id": "4",
                    "descriptor": {
                        "name": "Chest Physician Follow Up"
                    },
                    "price": {
                        "currency": "INR",
                        "value": null
                    },
                    "fulfillment_id": "6"
                },
                {
                    "id": "6",
                    "descriptor": {
                        "name": "Diet Follow Up"
                    },
                    "price": {
                        "currency": "INR",
                        "value": null
                    },
                    "fulfillment_id": "6"
                },
                {
                    "id": "16",
                    "descriptor": {
                        "name": "Family Physician"
                    },
                    "price": {
                        "currency": "INR",
                        "value": null
                    },
                    "fulfillment_id": "6"
                }
            ],
            "fulfillments": [
                {
                    "id": "6",
                    "type": "Consultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": "12",
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                },
                {
                    "id": "6",
                    "type": "Consultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": null,
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                },
                {
                    "id": "6",
                    "type": "Consultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": "150",
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                },
                {
                    "id": "6",
                    "type": "Consultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": null,
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                },
                {
                    "id": "6",
                    "type": "Consultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": null,
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                },
                {
                    "id": "6",
                    "type": "Consultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": null,
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                }
            ]
        }
    }
}
```

# Init Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "PUNE",
        "action": "init",
        "timestamp": "2022-07-18T05:26:37.258Z",
        "core_version": "0.7.1",
        "consumer_id": "plus91-EUA",
        "consumer_uri": "https://c32f-2409-4042-4b00-ade3-b757-3bf1-ff61-d120.in.ngrok.io/api/v1",
        "transaction_id": "b2e9f303-cb4a-47de-8a8f-5ff9fb86f591",
        "message_id": "82e6f4a3-e52a-4b12-b5a2-3092052e26a2"
    },
    "message": {
        "order": {
            "id": "a6026fde-1acc-488e-8e6e-373475fcc75c",
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Cardiology"
                },
                "price": {
                    "currency": "INR",
                    "value": "12"
                },
                "fulfillment_id": "3"
            },
            "billing": {
                "name": "Shubham Bhadale",
                "address": {
                    "door": "406",
                    "name": "Plus91 Techbologies Pvt Ltd",
                    "locality": "Vimannagar",
                    "city": "Pune",
                    "state": "Maharashtra",
                    "country": "India",
                    "area_code": "411013"
                },
                "email": "shubham.bhadale@plus91.in",
                "phone": "11111111111"
            },
            "fulfillment": {
                "id": "3",
                "type": "Consultation",
                "agent": {
                    "id": "SNO-3",
                    "name": "Vishal Trada",
                    "cred": "SNO-3",
                    "gender": "M",
                    "tags": {
                        "@abdm/gov/in/first_consultation": "12",
                        "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                        "@abdm/gov/in/follow_up": null,
                        "@abdm/gov/in/experience": null,
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/speciality": "Consultation",
                        "@abdm/gov/in/lab_report_consultation": null,
                        "@abdm/gov/in/education": null,
                        "@abdm/gov/in/hpr_id": null,
                        "@abdm/gov/in/signature": null
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-07-18T04:45"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-07-18T05:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "3"
                }
            },
            "customer": {
                "id": "61-7719-8914-2522",
                "cred": "shubham.bhadale@abdm"
            }
        }
    }
}
```

# On Init Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "PUNE",
        "action": "on_init",
        "timestamp": "2022-07-18T05:26:37.258Z",
        "core_version": "0.7.1",
        "consumer_id": "plus91-EUA",
        "consumer_uri": "https://c32f-2409-4042-4b00-ade3-b757-3bf1-ff61-d120.in.ngrok.io/api/v1",
        "transaction_id": "b2e9f303-cb4a-47de-8a8f-5ff9fb86f591",
        "message_id": "82e6f4a3-e52a-4b12-b5a2-3092052e26a2",
        "provider_id": "plus91-HSPA",
        "provider_uri": "https://plus91hq.ddns.net/medixcel_uhi"
    },
    "message": {
        "order": {
            "id": "3e794a60-97f2-4a46-8bc8-9838a7951355",
            "state": "PROVISIONALLY_BOOKED",
            "provider": {
                "id": 1,
                "descriptor": {
                    "name": "Lohegaon - Clinic"
                }
            },
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Cardiology"
                },
                "price": {
                    "currency": "INR",
                    "value": "12"
                },
                "fulfillment_id": "3"
            },
            "billing": {
                "name": "Shubham Bhadale",
                "address": {
                    "door": "406",
                    "name": "Plus91 Techbologies Pvt Ltd",
                    "locality": "Vimannagar",
                    "city": "Pune",
                    "state": "Maharashtra",
                    "country": "India",
                    "area_code": "411013"
                },
                "email": "shubham.bhadale@plus91.in",
                "phone": "11111111111"
            },
            "fulfillment": {
                "id": "3",
                "type": "Teleconsultation",
                "agent": {
                    "id": "SNO-3",
                    "name": "Vishal Trada",
                    "gender": "Male",
                    "cred": "SNO-3",
                    "tags": {
                        "@abdm/gov/in/first_consultation": "12",
                        "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                        "@abdm/gov/in/follow_up": null,
                        "@abdm/gov/in/experience": null,
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/speciality": "Teleconsultation",
                        "@abdm/gov/in/lab_report_consultation": null,
                        "@abdm/gov/in/education": null,
                        "@abdm/gov/in/hpr_id": null,
                        "@abdm/gov/in/signature": null
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-07-18T04:45"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-07-18T05:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "3"
                }
            },
            "quote": {
                "quote": {
                    "price": {
                        "currency": "INR",
                        "value": "12"
                    },
                    "breakup": [
                        {
                            "title": "Consultation",
                            "price": {
                                "currency": "INR",
                                "value": "12"
                            }
                        }
                    ]
                }
            },
            "payment": {
                "uri": "payto://ban/98273982749428?amount=INR:110&ifsc=SBIN0000575&message=hello",
                "type": "ON-ORDER",
                "status": "PENDING",
                "tl_method": null,
                "params": null
            }
        }
    },
    "status_code": 200
}
```

# Confirm Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "PUNE",
        "action": "confirm",
        "timestamp": "2022-07-18T05:26:49.626Z",
        "core_version": "0.7.1",
        "consumer_id": "plus91-EUA",
        "consumer_uri": "https://c32f-2409-4042-4b00-ade3-b757-3bf1-ff61-d120.in.ngrok.io/api/v1",
        "transaction_id": "b2e9f303-cb4a-47de-8a8f-5ff9fb86f591",
        "message_id": "d48d816c-aa82-4449-acb0-af26938d3c2e"
    },
    "message": {
        "order": {
            "id": "3e794a60-97f2-4a46-8bc8-9838a7951355",
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Cardiology"
                },
                "price": {
                    "currency": "INR",
                    "value": "12"
                },
                "fulfillment_id": "3"
            },
            "billing": {
                "name": "Shubham Bhadale",
                "address": {
                    "door": "406",
                    "name": "Plus91 Techbologies Pvt Ltd",
                    "locality": "Vimannagar",
                    "city": "Pune",
                    "state": "Maharashtra",
                    "country": "India",
                    "area_code": "411013"
                },
                "email": "shubham.bhadale@plus91.in",
                "phone": "11111111111"
            },
            "fulfillment": {
                "id": "3",
                "type": "Teleconsultation",
                "agent": {
                    "id": "SNO-3",
                    "name": "Vishal Trada",
                    "gender": "Male",
                    "cred": "SNO-3",
                    "tags": {
                        "@abdm/gov/in/first_consultation": "12",
                        "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                        "@abdm/gov/in/follow_up": null,
                        "@abdm/gov/in/experience": null,
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/speciality": "Teleconsultation",
                        "@abdm/gov/in/lab_report_consultation": null,
                        "@abdm/gov/in/education": null,
                        "@abdm/gov/in/hpr_id": null,
                        "@abdm/gov/in/signature": null
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-07-18T04:45"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-07-18T05:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "3"
                }
            },
            "quote": {
                "quote": {
                    "price": {
                        "currency": "INR",
                        "value": "12"
                    },
                    "breakup": [
                        {
                            "title": "Consultation",
                            "price": {
                                "currency": "INR",
                                "value": "12"
                            }
                        }
                    ]
                }
            },
            "payment": {
                "uri": "payto://ban/98273982749428?amount=INR:110&ifsc=SBIN0000575&message=hello",
                "params": null,
                "type": "ON-ORDER",
                "status": "PAID",
                "tl_method": null
            }
        }
    }
}
```

# On Confirm Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "PUNE",
        "action": "on_confirm",
        "core_version": "0.7.1",
        "transaction_id": "b2e9f303-cb4a-47de-8a8f-5ff9fb86f591",
        "message_id": "d48d816c-aa82-4449-acb0-af26938d3c2e",
        "timestamp": "2022-07-18T05:26:51.000000",
        "provider_id": "plus91-HSPA",
        "provider_uri": "https://plus91hq.ddns.net/medixcel_uhi"
    },
    "message": {
        "order": {
            "id": "3e794a60-97f2-4a46-8bc8-9838a7951355",
            "state": "CONFIRMED",
            "provider": {
                "id": 1,
                "descriptor": {
                    "name": "Lohegaon - Clinic"
                }
            },
            "item": {
                "id": 1,
                "descriptor": {
                    "name": "Cardiology"
                },
                "fulfillment_id": "3",
                "provider_id": 1
            },
            "billing": {
                "name": "Shubham Bhadale",
                "address": {
                    "door": "406",
                    "name": "Plus91 Techbologies Pvt Ltd",
                    "locality": "Vimannagar",
                    "city": "Pune",
                    "state": "Maharashtra",
                    "country": "India",
                    "area_code": "411013"
                },
                "email": "shubham.bhadale@plus91.in",
                "phone": "11111111111"
            },
            "fulfillment": {
                "id": "3",
                "type": "NA",
                "person": {
                    "id": "3",
                    "name": "Vishal Trada",
                    "gender": null,
                    "image": "",
                    "cred": "SNO-3"
                },
                "agent": {
                    "id": "3",
                    "name": "Vishal Trada",
                    "gender": null,
                    "image": "",
                    "cred": "SNO-3",
                    "tags": {
                        "@abdm/gov/in/first_consultation": "12",
                        "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                        "@abdm/gov/in/follow_up": null,
                        "@abdm/gov/in/experience": null,
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/speciality": "Teleconsultation",
                        "@abdm/gov/in/lab_report_consultation": null,
                        "@abdm/gov/in/education": null,
                        "@abdm/gov/in/hpr_id": null,
                        "@abdm/gov/in/signature": null
                    }
                },
                "state": {
                    "descriptor": {
                        "code": ""
                    }
                },
                "time": {
                    "range": {
                        "start": "2022-07-18 04:45:00",
                        "end": "2022-07-18 05:00:00"
                    }
                },
                "tags": {
                    "@abdm/gov/in/teleconf_url": "https://meet.google.com/mtj-wucm-vso"
                }
            },
            "quote": {
                "price": {
                    "currency": "INR",
                    "value": "12",
                    "breakup": []
                }
            },
            "payment": {
                "uri": "payto://ban/98273982749428?amount=INR:110&ifsc=SBIN0000575&message=hello",
                "params": null,
                "type": "ON-ORDER",
                "status": "PAID",
                "tl_method": null
            }
        }
    }
}
```

### API Logs for testing with EUA Postman Collection

# Search Request
```json
{
    "context": {
        "domain": "Health",
        "country": "IND",
        "city": "Pune",
        "action": "search",
        "timestamp": "2022-07-07T10:43:48.705082Z",
        "core_version": "0.7.1",
        "consumer_id": "eua-nha",
        "consumer_uri": "http://100.96.9.173:8080/api/v1/euaService",
        "transaction_id": "ae9e6d90-fde1-11ec-b66a-f551703a8c52",
        "message_id": "ae9e6d90-fde1-11ec-b66a-f551703a8da2"
    },
    "message": {
        "intent": {
            "fulfillment": {
                "agent": {
                    "name": "vijay"
                },
                "type": "Teleconsultation"
            }
        }
    }
}
```

# On Search Request
```json
{
    "context": {
        "domain": "Health",
        "country": "IND",
        "city": "PUNE",
        "action": "on_search",
        "core_version": "NA",
        "message_id": "ae9e6d90-fde1-11ec-b66a-f551703a8da2",
        "transaction_id": "ae9e6d90-fde1-11ec-b66a-f551703a8c52",
        "timestamp": "2022-07-18T07:09:11.000000",
        "provider_id": "plus91-HSPA",
        "provider_uri": "https://plus91hq.ddns.net/medixcel_uhi"
    },
    "message": {
        "catalog": {
            "descriptor": {
                "name": "Lohegaon - Clinic"
            },
            "items": [
                {
                    "id": "1",
                    "descriptor": {
                        "name": "Cardiology"
                    },
                    "price": {
                        "currency": "INR",
                        "value": "12"
                    },
                    "fulfillment_id": "6"
                }
            ],
            "fulfillments": [
                {
                    "id": "6",
                    "type": "Teleconsultation",
                    "agent": {
                        "id": "SNO-6",
                        "name": "Vijay Dandekar",
                        "cred": "SNO-6",
                        "gender": "M",
                        "tags": {
                            "@abdm/gov/in/first_consultation": "12",
                            "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                            "@abdm/gov/in/follow_up": null,
                            "@abdm/gov/in/experience": null,
                            "@abdm/gov/in/languages": "Eng, Hin",
                            "@abdm/gov/in/speciality": "Consultation",
                            "@abdm/gov/in/lab_report_consultation": null,
                            "@abdm/gov/in/education": null,
                            "@abdm/gov/in/hpr_id": null,
                            "@abdm/gov/in/signature": null
                        }
                    },
                    "start": {
                        "time": {
                            "timestamp": "T18:30+00:00"
                        }
                    },
                    "end": {
                        "time": {
                            "timestamp": "T17:30+00:00"
                        }
                    },
                    "tags": {
                        "@abdm/gov.in/slot_id": "6"
                    }
                }
            ]
        }
    }
}
```

# Init Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "std:080",
        "action": "init",
        "timestamp": "2022-07-18T08:05:55.252760Z",
        "core_version": "0.7.1",
        "consumer_id": "eua-nha",
        "consumer_uri": "http://100.96.9.173:8080/api/v1/euaService",
        "transaction_id": "ae9e6d90-fde1-11ec-b66a-f551703a8c52",
        "message_id": "c8f9ad70-034b-11ed-a6d6-c13d491ee158"
    },
    "message": {
        "order": {
            "id": "d49a3f60-e630-11ec-b41c-99795ccbf2d5",
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Cardiology"
                },
                "fulfillment_id": "6",
                "price": {
                    "currency": "INR",
                    "value": "1000"
                }
            },
            "billing": {
                "name": "Ganesh Borse",
                "address": {
                    "door": "21A",
                    "name": "ABC Apartments",
                    "locality": "Dwarka",
                    "city": "New Delhi",
                    "state": "New Delhi",
                    "country": "India",
                    "area_code": "110011"
                },
                "email": "",
                "phone": "9595163508"
            },
            "fulfillment": {
                "id": "6",
                "type": "Teleconsultation",
                "agent": {
                    "id": "SNO-6",
                    "name": "Vijay Dandekar",
                    "gender": "F",
                    "tags": {
                        "@abdm/gov/in/education": "MD Medicine",
                        "@abdm/gov/in/experience": "15.0",
                        "@abdm/gov/in/follow_up": "300.0",
                        "@abdm/gov/in/first_consultation": "500.0",
                        "@abdm/gov/in/speciality": "Neurologist",
                        "@abdm/gov/in/languages": "English, Hindi",
                        "@abdm/gov/in/upi_id": "sana.bhatt@okaxis",
                        "@abdm/gov/in/hpr_id": "12345678"
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-06-18T12:30:00"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-06-18T12:45:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "6"
                }
            },
            "customer": {
                "id": "61-8787-9274-4422",
                "cred": "ganeshborse@sbxabdm"
            }
        }
    }
}
```

# On Init Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "std:080",
        "action": "on_init",
        "timestamp": "2022-07-18T08:05:55.252760Z",
        "core_version": "0.7.1",
        "consumer_id": "eua-nha",
        "consumer_uri": "http://100.96.9.173:8080/api/v1/euaService",
        "transaction_id": "ae9e6d90-fde1-11ec-b66a-f551703a8c52",
        "message_id": "c8f9ad70-034b-11ed-a6d6-c13d491ee158",
        "provider_id": "plus91-HSPA",
        "provider_uri": "https://plus91hq.ddns.net/medixcel_uhi"
    },
    "message": {
        "order": {
            "id": "219312e1-ca96-4948-ae6a-326480c6ec00",
            "state": "PROVISIONALLY_BOOKED",
            "provider": {
                "id": 1,
                "descriptor": {
                    "name": "Lohegaon - Clinic"
                }
            },
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Cardiology"
                },
                "price": {
                    "currency": "INR",
                    "value": "12"
                },
                "fulfillment_id": "6"
            },
            "billing": {
                "name": "Ganesh Borse",
                "address": {
                    "door": "21A",
                    "name": "ABC Apartments",
                    "locality": "Dwarka",
                    "city": "New Delhi",
                    "state": "New Delhi",
                    "country": "India",
                    "area_code": "110011"
                },
                "email": "",
                "phone": "9595163508"
            },
            "fulfillment": {
                "id": "6",
                "type": "Teleconsultation",
                "agent": {
                    "id": "SNO-6",
                    "name": "Vijay Dandekar",
                    "gender": "Male",
                    "cred": "SNO-6",
                    "tags": {
                        "@abdm/gov/in/first_consultation": "12",
                        "@abdm/gov/in/upi_id": "9999999999@okhdfc",
                        "@abdm/gov/in/follow_up": null,
                        "@abdm/gov/in/experience": null,
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/speciality": "Teleconsultation",
                        "@abdm/gov/in/lab_report_consultation": null,
                        "@abdm/gov/in/education": null,
                        "@abdm/gov/in/hpr_id": null,
                        "@abdm/gov/in/signature": null
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-06-18T12:30:00"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-06-18T12:45:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "6"
                }
            },
            "quote": {
                "quote": {
                    "price": {
                        "currency": "INR",
                        "value": "12"
                    },
                    "breakup": [
                        {
                            "title": "Consultation",
                            "price": {
                                "currency": "INR",
                                "value": "12"
                            }
                        }
                    ]
                }
            },
            "payment": {
                "uri": "payto://ban/98273982749428?amount=INR:110&ifsc=SBIN0000575&message=hello",
                "type": "ON-ORDER",
                "status": "PENDING",
                "tl_method": null,
                "params": null
            }
        }
    },
    "status_code": 200
}
```

# Confirm Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "std:080",
        "action": "confirm",
        "timestamp": "2022-07-18T08:05:55.252760Z",
        "core_version": "0.7.1",
        "consumer_id": "eua-nha",
        "consumer_uri": "http://100.96.9.173:8080/api/v1/euaService",
        "transaction_id": "ae9e6d90-fde1-11ec-b66a-f551703a8c52",
        "message_id": "c8f9ad70-034b-11ed-a6d6-c13d491ee158"
    },
    "message": {
        "order": {
            "id": "219312e1-ca96-4948-ae6a-326480c6ec00",
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Consultation"
                },
                "fulfillment_id": "6",
                "price": {
                    "currency": "INR",
                    "value": "1000"
                }
            },
            "billing": {
                "name": "Amod Suresh Joshi",
                "address": {
                    "door": "21A",
                    "name": "ABC Apartments",
                    "locality": "Dwarka",
                    "city": "New Delhi",
                    "state": "New Delhi",
                    "country": "India",
                    "area_code": "110011"
                },
                "email": "joshiamod@gmail.com",
                "phone": "9970983214"
            },
            "fulfillment": {
                "id": "6",
                "type": "Teleconsultation",
                "agent": {
                    "id": "SNO-6",
                    "name": "Vijay Dandekar",
                    "gender": "M",
                    "tags": {
                        "@abdm/gov/in/education": "MBBS",
                        "@abdm/gov/in/experience": "5.0",
                        "@abdm/gov/in/follow_up": "200.0",
                        "@abdm/gov/in/first_consultation": "300.0",
                        "@abdm/gov/in/speciality": "Cardiology",
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/upi_id": "vijaydandekar@icici",
                        "@abdm/gov/in/hpr_id": "73-5232-1888-8686"
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-07-18T11:45:00"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-07-18T12:00:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "6"
                }
            },
            "quote": {
                "price": {
                    "currency": "INR",
                    "value": "1000"
                },
                "breakup": [
                    {
                        "title": "Consultation",
                        "price": {
                            "currency": "INR",
                            "value": "1000"
                        }
                    },
                    {
                        "title": "CGST @ 5%",
                        "price": {
                            "currency": "INR",
                            "value": "50"
                        }
                    },
                    {
                        "title": "SGST @ 5%",
                        "price": {
                            "currency": "INR",
                            "value": "50"
                        }
                    },
                    {
                        "title": "Registration",
                        "price": {
                            "currency": "INR",
                            "value": "400"
                        }
                    }
                ]
            },
            "payment": {
                "uri": "https://api.bpp.com/pay?amt=1500&txn_id=ksh87yriuro34iyr3p4&mode=upi&vpa=sana.bhatt@upi",
                "params": {
                    "amount": "1500",
                    "mode": "UPI",
                    "vpa": "sana.bhatt@upi",
                    "transaction_id": "abc128-riocn83920"
                },
                "type": "ON-ORDER",
                "status": "PAID",
                "tl_method": "http/get"
            },
            "customer": {
                "id": "",
                "cred": "amodjoshi@sbx"
            }
        }
    }
}
```

# On Confirm Request
```json
{
    "context": {
        "domain": "nic2004:85111",
        "country": "IND",
        "city": "std:080",
        "action": "confirm",
        "timestamp": "2022-07-18T08:05:55.252760Z",
        "core_version": "0.7.1",
        "consumer_id": "eua-nha",
        "consumer_uri": "http://100.96.9.173:8080/api/v1/euaService",
        "transaction_id": "ae9e6d90-fde1-11ec-b66a-f551703a8c52",
        "message_id": "c8f9ad70-034b-11ed-a6d6-c13d491ee158"
    },
    "message": {
        "order": {
            "id": "219312e1-ca96-4948-ae6a-326480c6ec00",
            "item": {
                "id": "1",
                "descriptor": {
                    "name": "Consultation"
                },
                "fulfillment_id": "6",
                "price": {
                    "currency": "INR",
                    "value": "1000"
                }
            },
            "billing": {
                "name": "Amod Suresh Joshi",
                "address": {
                    "door": "21A",
                    "name": "ABC Apartments",
                    "locality": "Dwarka",
                    "city": "New Delhi",
                    "state": "New Delhi",
                    "country": "India",
                    "area_code": "110011"
                },
                "email": "joshiamod@gmail.com",
                "phone": "9970983214"
            },
            "fulfillment": {
                "id": "6",
                "type": "Teleconsultation",
                "agent": {
                    "id": "SNO-6",
                    "name": "Vijay Dandekar",
                    "gender": "M",
                    "tags": {
                        "@abdm/gov/in/education": "MBBS",
                        "@abdm/gov/in/experience": "5.0",
                        "@abdm/gov/in/follow_up": "200.0",
                        "@abdm/gov/in/first_consultation": "300.0",
                        "@abdm/gov/in/speciality": "Cardiology",
                        "@abdm/gov/in/languages": "Eng, Hin",
                        "@abdm/gov/in/upi_id": "Vijay@icici",
                        "@abdm/gov/in/hpr_id": "73-5232-1888-8686"
                    }
                },
                "start": {
                    "time": {
                        "timestamp": "2022-07-18T11:45:00"
                    }
                },
                "end": {
                    "time": {
                        "timestamp": "2022-07-18T12:00:00"
                    }
                },
                "tags": {
                    "@abdm/gov.in/slot_id": "6",
                    "@abdm/gov/in/teleconf_url": "https://meet.google.com/jmy-cmkp-vfq"
                }
            },
            "quote": {
                "price": {
                    "currency": "INR",
                    "value": "1000"
                },
                "breakup": [
                    {
                        "title": "Consultation",
                        "price": {
                            "currency": "INR",
                            "value": "1000"
                        }
                    },
                    {
                        "title": "CGST @ 5%",
                        "price": {
                            "currency": "INR",
                            "value": "50"
                        }
                    },
                    {
                        "title": "SGST @ 5%",
                        "price": {
                            "currency": "INR",
                            "value": "50"
                        }
                    },
                    {
                        "title": "Registration",
                        "price": {
                            "currency": "INR",
                            "value": "400"
                        }
                    }
                ]
            },
            "payment": {
                "uri": "https://api.bpp.com/pay?amt=1500&txn_id=ksh87yriuro34iyr3p4&mode=upi&vpa=sana.bhatt@upi",
                "params": {
                    "amount": "1500",
                    "mode": "UPI",
                    "vpa": "sana.bhatt@upi",
                    "transaction_id": "abc128-riocn83920"
                },
                "type": "ON-ORDER",
                "status": "PAID",
                "tl_method": "http/get"
            },
            "customer": {
                "id": "",
                "cred": "amodjoshi@sbx"
            }
        }
    }
}
```
