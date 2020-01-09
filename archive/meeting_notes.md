**Vendor Check-In Notes**

**June 14, 2017**

In Room: Nico Ojeda OMB REI, Justin Grime OMB, Austin Moffa OMB TCG, Jeff Rossman OMB TCG

On Phone: Kimler Corey OMB TCG, Tsisti Llewali OMB TCG, Dan York GSA, Giha OMB REI, Barry Cannel DHS, Bill Gehrke Oracle HHS, Treasury

Purpose: Give vendors an update on our development efforts and address the action items from last week

**Recap:**

No slippage, no major deliverables this week.

To improve - taking notes, making notes more available. Putting out agenda beforehand.

Data dictionary availability, context, validations. Discussed selective prioritization of deliverables.

**New:**

Question preference: via e-mail or github. (Specifically a question regarding DOD and funding source level/investment level.)

Emphasized -- if questions are agency specific/sensitive, use helpdesk e-mail. If uncertain, you can make a reference ticket for the following meeting.

Clarify -- xsd, xsd+ (data dictionary) does or does not include validations? Answer: XSD+ will come with all supplemental technical material


** Check-In Notes**
**June 7, 2017**

In Room: Srida Malsava Treasury, Austin Moffa OMB TCG, Keith Ott eCPIC, Mitchell Jarrett OMB TCG, Nico Ojeda OMB REI, Todd Messer OMB TCG, Justin Grimes OMB, Dan York eCPIC

On Phone: Kimler Corey OMB TCG, Barry Cannell DHS, Bill Gehrke Oracle HHS, Tsitsi, \_\_\_Treasury

Purpose: Give vendors an update on our development efforts and address the action items from last week

Timeline (posted on the GitHub page)

- Currently on schedule to deliver XSD-Candidate (v1.9.0) July 5th
  - Moved date from _Saturday_ July 1 and past July 4 holiday
  - Draft is currently uploaded to GitHub
  - Baseline has reached a point that vendors should be able to use it
- Currently on schedule to deliver XSD-Plus Candidate (v1.9.0) on July 31
# st


Action Items from Last Meeting

- Upload Data Dictionary CSV

1.
  - Uploaded shortly before meeting
  - Would like feedback from vendor community
  - Ideally, bridges gap between guidance, developers, and database
  - Baseline plus changes made up to this point, substantial changes to be made

- Upload Fieldmap CSV
  - Was posted to GitHub this morning
- Definition of Optional
- Following HotFix tickets through workflow
  - Will be using tags as a way to handle this
  - Will be called &quot;HOTFIX&quot; and have the release date attached

Action Items for Next Meeting

1. Enumerations File
2. Fieldmap to be sent out
3. Working Session - Prepare diagram to walk through questions in upcoming vendor session (2 weeks from now)
  - May be able to use room at GSA for this meeting
4. Fix email address on readme file
5. Add Notes section to GitHub
6. Add CIO Evaluation Section to xsd schema
7. Add validations csv from pdf

**Vendor Call Notes – May 31, 2017**

Action Items &amp; Further Discussion Points Highlighted

Attendance

In Room: Bill Hough OMB (TCG), Austin Moffa OMB (TCG), Mitchell Jarrett OMB (TCG), Todd Messer OMB (TCG), Nico Ojeda OMB (REI), Justin Grimes OMB, Dan York GSA, Tim McCrosson OMB

On Phone: Becky Nichols DHS, Bill Gehrke HHS (Oracle), TsiTsi Liywalii OMB (TCG), Avasarala Venkata Sriram Treasury, Kimler Corey OMB (TCG), Alex Unger GSA (BAH), Suchun Hu Treasury

- Deliverables
  - No Changes since last meeting, added draft additions and removals to GitHub page
- Schedule
  - No Changes since last meeting
- GitHub feedback – made baseline XSD and XSD+ available (data dictionary, validations, etc.)
  - Baseline is version 1.8, final version from FY18; changes will be made to get to a 1.9
  - July 1
# st
 will be date for release candidate for version 1.9
- Submission of Issues Note posted to Repo
  - Issue or questions with agency-specific data should be through email rather than over GitHub
  - Necessary for issue submission: [Get what&#39;s needed for submission from Mitchell]
- Open Discussion – Justin Grimes
  - Dan York - Data Dictionary is most important piece for development at this stage in the process
    - Early to Mid-July – Target Date for getting first version out to users
  - Potentially could send out certain pieces of the deliverables to accommodate vendor schedule
  - Current data dictionary is dealing with technical debt in updating to the FY19 cycle
  - Alex Unger - Enumerations in that same file would be useful as well
  - Better ways to provide this information (format, presentation, etc.) are appreciated
  - Timeline is currently high-level, and the tension between the hot fixes and schedule is being thought through; will be addressed in next meeting
- Validations – Kimler Corey
  - Validation Description is currently on the GitHub repo
  - Data Dictionary will be here shortly
  - Diffs will be up on the GitHub as development of the validations continues
  - List of Removals will be one key point for the vendor community
  - Will be up in a non-pdf format very soon, most likely csv now for ease of use, markdown in the future
  - Early data dictionary and List of Fields should be ready by the next vendor call for better understanding of validations
- Draft investmentreport.xsd– Austin Moffa
  - New type of investment requires new type of submission detailed on GitHub repo
  - Very similar to business case at the moment, with different fields
  - Much less strict than the business case was
  - All sections are complex types rather than sequences
  - No implicit field type validation in this form either
  - Optionality doesn&#39;t change the shape of the data in this version
  - Order of operations may factor into the structure
  - Connections between the sections of the investment reports may cause an issue
  - Austin&#39;s Report Field Mapping to be provided by the next vendor call
  - Need to clarify the abstraction on optionality, especially in new and optional reports
  - Operations engaged should also be factored into validations
  - Question of how to communicate required and optional needs to be finalized around data validations and data dictionary
  - Order of operations will be followed up on


