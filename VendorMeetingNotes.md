# Weekly Notes from CPIC Vendor Meeting
The most recent meeting will always be on top

## July 12th, 2017

**In Room:** Austin Moffa OMB (Contractor), Keith Ott eCPIC, Mitchell Jarrett OMB (Contractor), Nico Ojeda OMB (Contractor), Matt Bailey OMB OFCIO, Dan York eCPIC
  
**Purpose:** Whiteboarding Session

**Action Items**
1.	Agencies are requesting vendor token and extension of time for their tokens.

2.	Please do not close tickets unless the agencies are 100% comfortable with an answer, eCpic community feel free to reopen a ticket if you feel its does not meet the complete answer

3.	Need better naming convention for all GitHub folders and inquiries



## June 29th, 2017

**In Room:** Austin Moffa OMB (Contractor), Keith Ott eCPIC, Mitchell Jarrett OMB (Contractor), Nico Ojeda OMB (Contractor), Matt Bailey OMB OFCIO, Dan York eCPIC

**On Phone:** Treasury, HHS, DHS, Oracle
  
**Purpose:** Whiteboarding Session

**Action Items**
1.	Text files process is between API, or Excel and the Agencies prefer Excel since that is the process they have been utilizing for past years. Follow up with Ateam.
 * Note: What task is required from development team.

2.	PIID is not a valid request for future contracts. Confirm with Kelly to remove the Field from the FY19 Guidance.

3.	GitHub ownership for the FY19 Guidance, with a n synopsis of changes, as they occur, action is to speak with Kelly regarding further updates to the guidance.

4.	FAQs for FY19 Guidance, there was one for FY18 but nothing for FY19, I have attached the link for FY18 FAQs for your convenience.
 * Official FY 2018 Budget Guidance on IT Capital Planning

5.	UII decision - when the previous investments are broken into the newer reporting methodology do they carry the UII over or should they have new UIIs?
 * Our recommendation is when an investment is separated to follow the new methodology it should be carried over with a new UII. Much easier for traceability and future reporting

6.	Pending confirmation for field validation on “Optional Fields”.


**Pending questions:**
1.	When an investment is broken into different types to meet the TBM requirements should it carry the same UII for the larger chunk?
2.	CIO evaluation should it be part of business case for major investments and different section for other investment types?
 * Our recommendation is CIO evaluation should be a separate section regardless of the type of investment.
 
 
**Weekly Commitment:**
1.	Updated XML Schema
2.	Updated Data Dictionary
3.	Update to Guidance – Justin will own this piece



## June 21st, 2017

**In Room:** Austin Moffa OMB (Contractor), Keith Ott eCPIC, Mitchell Jarrett OMB (Contractor), Nico Ojeda OMB (Contractor), Matt Bailey OMB OFCIO, Dan York eCPIC

**On Phone:** Treasury, HHS, DHS, Oracle
  
**Purpose:** Give vendors an update on our development efforts and address the action items from last week

**Timeline (posted on the GitHub page)**
Currently on schedule to deliver XSD-Candidate (v1.9.0) July 5th
       Including API and UAT
Currently on schedule to deliver XSD-Candidate **Plus** (v1.9.0) July 31st



**Recap last meeting and actions**
1.	Meeting notes from previous meetings are posted to GitHub and the Agenda outline
2.	New draft of the XSD was just pushed today, will be finalized directly after meeting
 * Plan is to start doing weekly push in order to get more
 * Will happen Monday COB
 * Can send a follow-up email with the instructions on how to get the diff between files

**Review new Deliverables from previous week**
1. Data Dictionary - Being wrapped up this week after final review, enumerations included in the Description and Example
2. Emphasized Austin’s field mapping will be subsumed by the data dictionary– if questions are agency specific/sensitive, use helpdesk e-mail. If uncertain, you can make a reference ticket for the following meeting.

**Recap Tickets / Questions**
1. 2 new questions posted to GitHub since last week&#39;s meeting.
2. Answered question on Capability codes working on answering AITPS Validations
3. Would like vendors to use Issues section of the GitHub page for as many questions and problems as possible

**General comments**
1.	None
2.	Is it more useful to be able to submit data and have it validate against the schema or that it goes into database?
 * Structure is more important and validations are important as well 
 
**Next meeting**
Whiteboard Session to replace next week’s Wednesday Vendor Call
 * **WHEN:** Thursday, June 29 1:00-2:30pm
 * **WHERE:** GSA HQ, 1800 F St NW, Room 1420
 * **PURPOSE:** Walk through a few scenarios on whiteboard to walk through cases of investments and important scenarios

**Action Items for Next Meeting**
1. Add enumerations explicitly in the data dictionary file by Thursday
2. Begin pushing XSD updates by Monday COB
3. Get to final blanket answer for Optional and Required fields by Thursday
4. Send invite for next meeting as well as scope and risks as follow up to this meeting by COBOfficial communication to explain issue submission protocol/etiquette. 



## June 14th, 2017

**In Room:** Nico Ojeda OMB REI, Justin Grime OMB, Austin Moffa OMB TCG, Jeff Rossman OMB TCG

**On Phone:** Kimler Corey OMB TCG, Tsisti Llewali OMB TCG, Dan York GSA, Giha OMB REI, Barry Cannel DHS, Bill Gehrke Oracle HHS, Dan … Treasury

**Purpose:** Give vendors an update on our development efforts and address the action items from last week

**Timeline (posted on the GitHub page)**
Currently on schedule to deliver XSD-Candidate (v1.9.0) July 5th 

**Recap**
1.	No slippage, no major deliverables this week.

2.	To improve - taking notes, making notes more available. Putting out agenda beforehand.

3.	Data dictionary availability, context, validations. Discussed selective prioritization of deliverables.

**New**
1.	Question preference: via e-mail or github. (Specifically a question regarding DOD and funding source level/investment level.)

2.	Emphasized – if questions are agency specific/sensitive, use helpdesk e-mail. If uncertain, you can make a reference ticket for the following meeting.

3.	Clarify – xsd, xsd+ (data dictionary) does or does not include validations? Answer: XSD+ will come with all supplemental technical material


**Action Items for Next Meeting**
1.	Official communication to explain issue submission protocol/etiquette. 
2.	Provide information regarding technical discussion/overview for the upcoming week.





## June 7th, 2017

**In Room:** Srida Malsava Treasury, Austin Moffa OMB TCG, Keith Ott eCPIC, Mitchell Jarrett OMB TCG, Nico Ojeda OMB REI, Todd Messer OMB TCG, Justin Grimes OMB, Dan York eCPIC

**On Phone:** Kimler Corey OMB TCG, Barry Cannell DHS, Bill Gehrke Oracle HHS, Tsitsi, ___Treasury

**Purpose:** Give vendors an update on our development efforts and address the action items from last week

**Timeline (posted on the GitHub page)**
Currently on schedule to deliver XSD-Candidate (v1.9.0) July 5th 
 * Moved date from Saturday July 1 and past July 4 holiday
 * Draft is currently uploaded to GitHub
 * Baseline has reached a point that vendors should be able to use it
 * Currently on schedule to deliver XSD-Plus Candidate (v1.9.0) on July 31st 

**Action Items from Last Meeting**
1.	Upload Data Dictionary CSV
 * Uploaded shortly before meeting
 * Would like feedback from vendor community
 * Ideally, bridges gap between guidance, developers, and database
 * Baseline plus changes made up to this point, substantial changes to be made

2.	Upload Fieldmap CSV
 * Was posted to GitHub this morning
3.	Definition of Optional
4.	Following HotFix tickets through workflow
 * Will be using tags as a way to handle this
 * Will be called “HOTFIX” and have the release date attached

**Action Items for Next Meeting**
1.	Enumerations File 
2.	Fieldmap to be sent out
3.	Working Session - Prepare diagram to walk through questions in upcoming vendor session (2 weeks from now)
 * May be able to use room at GSA for this meeting
4.	Fix email address on readme file
5.	Add Notes section to GitHub
6.	Add CIO Evaluation Section to xsd schema
7.	Add validations csv from pdf




## May 31st, 2017 

**In Room:** Bill Hough OMB (TCG), Austin Moffa OMB (TCG), Mitchell Jarrett OMB (TCG), Todd Messer OMB (TCG), Nico Ojeda OMB (REI), Justin Grimes OMB, Dan York GSA, Tim McCrosson OMB

**On Phone:** Becky Nichols DHS, Bill Gehrke HHS (Oracle), TsiTsi Liywalii OMB (TCG), Avasarala Venkata Sriram Treasury, Kimler Corey OMB (TCG), Alex Unger GSA (BAH), Suchun Hu Treasury

**Purpose:** Give vendors an update on our development efforts and address the action items from last week

**Timeline (posted on the GitHub page)**
 * No Changes since last meeting

**Deliverables**
 * Added draft additions and removals to GitHub page

**General Notes**
1.	GitHub feedback – made baseline XSD and XSD+ available (data dictionary, validations, etc.)
 * Baseline is version 1.8, final version from FY18; changes will be made to get to a 1.9
 * July 1st will be date for release candidate for version 1.9

2.	Submission of Issues Note posted to Repo
 * Issue or questions with agency-specific data should be through email rather than over GitHub
 * Necessary for issue submission: [Get what’s needed for submission from Mitchell]

3.	Open Discussion – Justin Grimes
 * Dan York - Data Dictionary is most important piece for development at this stage in the process
        Early to Mid-July – Target Date for getting first version out to users
 * Potentially could send out certain pieces of the deliverables to accommodate vendor schedule
 * Current data dictionary is dealing with technical debt in updating to the FY19 cycle
 * Alex Unger - Enumerations in that same file would be useful as well 
 * Better ways to provide this information (format, presentation, etc.) are appreciated
 * Timeline is currently high-level, and the tension between the hot fixes and schedule is being thought through; will be addressed in next meeting
 
4.	Validations – Kimler Corey
 * Validation Description is currently on the GitHub repo
 * Data Dictionary will be here shortly
 * Diffs will be up on the GitHub as development of the validations continues
 * List of Removals will be one key point for the vendor community
 * Will be up in a non-pdf format very soon, most likely csv now for ease of use, markdown in the future
 * Early data dictionary and List of Fields should be ready by the next vendor call for better understanding of validations

5.	Draft investmentreport.xsd– Austin Moffa
 * New type of investment requires new type of submission detailed on GitHub repo
 * Very similar to business case at the moment, with different fields
 * Much less strict than the business case was
 * All sections are complex types rather than sequences
 * No implicit field type validation in this form either
 * Optionality doesn’t change the shape of the data in this version
 * Order of operations may factor into the structure
 * Connections between the sections of the investment reports may cause an issue
 * Austin’s Report Field Mapping to be provided by the next vendor call
 * Need to clarify the abstraction on optionality, especially in new and optional reports
 * Operations engaged should also be factored into validations
 * Question of how to communicate required and optional needs to be finalized around data validations and data dictionary
 * Order of operations will be followed up on
 

**Action Items for Next Meeting**
1.	Timeline is currently high-level, and the tension between the hot fixes and schedule is being thought through; will be addressed in next meeting
2.	Will be up in a non-pdf format very soon, most likely csv now for ease of use, markdown in the future
3.	Early data dictionary and List of Fields should be ready by the next vendor call for better understanding of validations
4.	Austin’s Report Field Mapping to be provided by the next vendor call
5.	Question of how to communicate required and optional needs to be finalized around data validations and data dictionary
6.	Order of operations will be followed up on



