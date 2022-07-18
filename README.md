# UHI_Integration_Challenge_2_HSPA_Plus91_Final_Submission
Execute an end to end booking for a Physical OPD appointment, using an HSPA, resulting in an appointment confirmation 

# Plus91 HSPA Microservice [Folder - medixcel-uhi]

Objective of this microservice is to handle the requests coming from gateway & EUAs & forwarding it to
the correct hospital which is using plus91 EMR software.
Workflow:
1. The microservice manages list of all the hospitals which used plus91 EMR software
2. For search request
	Microservice sends the search request to all registered hospitals with plus91 & sends search response back to the EUAs
3. The booking workflow requests:
	Microservice forwards the the requests to the correct hospital & sends the response back to the requesting EUA.
