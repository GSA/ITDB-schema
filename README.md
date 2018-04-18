
# ITDB-schema
This repository is supplied by OMB for the CPIC Community. It's purpose is to act as a distribution point of CPIC Guidance schema files and also to act as as collaborative forum for reviewing proposed schema changes. Here CPIC developers can find the latest [OMB CPIC Guidance](https://community.max.gov/download/attachments/1206026892/FY19%20IT%20Budget%20Capital%20Planning%20Guidance%20%2895%20Solution%29.pdf?api=v2), XSD files, and their respective sample XML submission files, all of which will allow them to validate the structure of their own XML submission files. Our goals are as follow:
 
 * Community members should be able to download the latest [CPIC Guidance](https://community.max.gov/download/attachments/1206026892/FY19%20IT%20Budget%20Capital%20Planning%20Guidance%20%2895%20Solution%29.pdf?api=v2), XSD and XML files.
 * As changes are made by OMB developers, the XSD and sample XML files will be automatically updated.
 * Members of this repository receive alerts when files are updated, assuming they subscribe to GitHub notifications.
 * Members of this repository may fork and create pull requests of this code base if they wish to suggest changes to the schema or later submit additional XML test files.
 * A core team will monitor this repository for issues, questions and pull requests and will respond as issues and questions are raised and changes are proposed.

## Repo Deliverables

| In Repo  | Object  |  Date Expected |
|:-------------:|---|---|
| 	&#x2714; |  [FY18 Guidance](https://github.com/ombegov/ITDB-schema/blob/master/docs/TechnicalDocumentation/FY18_Guidance.pdf) |  DONE |
| &#x2714; | [FY19 Guidance]https://www.whitehouse.gov/sites/whitehouse.gov/files/omb/assets/egov_docs/fy19_it_budget_guidance.pdf  |  DONE  |
| &#x2714;  | Baseline XSD Schema   | DONE  |
| &#x2714;  | Baseline XML Sample Files   | DONE |
| &#x2714;  | [Baseline Validations](https://github.com/ombegov/ITDB-schema/blob/master/docs/validations.md)   | DONE  |
| &#x2714;  | [Technical Documents](https://github.com/ombegov/ITDB-schema/tree/master/docs/TechnicalDocumentation)   | DONE  |
| &#x2714; | [How to submit data to IT Dashboard for FY19](https://github.com/ombegov/ITDB-schema/blob/master/docs/ITDB_Submission_Instructions_v3_25.pdf) | DONE  |
| &#x2714; | [XSD Candidate](https://github.com/ombegov/ITDB-schema/tree/master/src) | DONE |
| &#x2714; | [2019 UAT Server Available](https://myuat-2019.itdashboard.gov) **MAX REQ**| DONE |
|  | Diagram of submission process  | SOON  |
|  | Archive versions (BY17, BY16, etc) | TBD  |


## Important Dates 

|  Event  |  Date |
|:-------------|:-------------:|
|  Guidance Released |  May 11, 2018  |
|  Finalized target dates based on guidance |  May 11, 2018 |
|  Published Baseline XSD (v1.9)|  Published |
|  Publish Baseline XSD Plus (v1.9)|  Published |
|  Validation logic development | May/June 2018 (Tentative) |
| [2020 UAT Server Available] | May 28, 2018 (Tentative) |
|  Target Date for [XSD Candidate] (v2.0.0) shared to vendors    |  May 28, 2018 (Tentative) |
|  Target Date for [XSD **Plus** Candidate] (v2.0.0)  |  July 13, 2018 (Tentative) |
|  Final Guidance  and [A-11]  |  Late June 2018 (Tentative) |
|  2020 UAT Server Delivered with Integrated Validations |  July 20, 2018 (Tentative) |
|  Publish XSD  &  XSD **Plus** (v2.0.x)    |  July 9, 2018 (Tentative) |
|  Publish Final XSD  &  XSD **Plus** (v2.0.x)    |  July 16, 2018 (Tentative) |
|  Draft IT Portfolio Pre-submission  |  August 20, 2018 (Tentative) |
|  Open 2020 UAT Server Delivered with Complete Integrated Validations |  August 7, 2018 (Tentative)  |
|  Open 2020 UAT Servers to Agencies for Testing (Validations)    |  August 7, 2018 (Tentative) |
|  2020 IT Portfolio Data Feed available to Agencies    |  TBD |
|  2020 Business Case & Standard Investment Report Data Feeds available to Agencies    |  TBD   |


## Submission Dates (Tentative) 
|  Event  |  Date |
|:-------------|:-------------:|
|  BY19 Submission Ends  |  TBD |
|  Pre-Decisional Budget Submission Starts  | TBD |
|  Pre-Decisional Budget Submission Ends  |  TBD  |
|  Pre-Decisional Business Case (BC) and Standard Investment Report (SIR) Submission Starts  |  TBD |
|  Pre-Decisional BC and SIR Submission Ends  |  TBD |
|  President's Budget Agency IT Portfolio Summary Submission Starts  |  TBD |
|  President's Budget Agency IT Portfolio Summary Submission Ends  |  TBD |
|  President's Budget BC and SIR Submission Starts  |  TBD |
|  President's Budget BC and SIR Submission Ends  |  TBD |
|  IT Dashboard Data from President's Budget Submission Displays on Public Site  |  TBD |

## Sprint/Hotfix Deployment Dates

|  Event  |  Date |  Highlights | Deployed |
|:-------------:|---|--------|:---:|
|  Sprint 2017.12B                    |  December 19, 2017 | <ul> <li> Resolved GitHub Issue #182 </li> <li> Removed Baselined Fields in Regular Updates - **Schema Change** </li> </ul> | &#x2714; |
|  Sprint 2018.01A                    |  January 2, 2018   | <ul> <li> Resolved GitHub Issue #207 </li> <li> Added Validations for IT Towers </li> <li> Added Shared Services Identifier "0099" </li> <li> **No Schema Changes** </li> </ul>  | &#x2714; |
|  Sprint 2018.01B                    |  January 15, 2018  | <ul> <li> Resolved GitHub Issue #222 </li> <li> Resolved GitHub Issue #218 </li> <li> Resolved Error Message Reference to Performance Metrics vs. Contracts </li> <li> Resolved Duplicated Cost Pool Data Response </li> <li> **No Schema Changes** </li></ul>| &#x2714; |
 |  Sprint 2018.01C                    |  January 29, 2018  | <ul> <li> Resolved GitHub Issue #212 </li> <li> Enabled Investment Summary Pages </li> <li> Enabled Business Case PDF Links </li> <li> Updates to ITDB Chart Visualizations </li> <li> **No Schema Changes** </li> </ul> | &#x2714; |
|  Sprint 2018.02A                    |  February 12, 2018 | <ul> <li> Updated verbiage on CIO Evaluation Report validation</li> <li> Display Type 5 and 6 Investments to Public (Individual Investments table, IT Portfolio, and IT Portfolio Funding Sources Feed) </li> <li> Modified SIR Data Feed Names </li> <li> **No Schema Changes** </li></ul> | &#x2714; |
|  Sprint 2018.02B                    |  February 26, 2018 | <ul><li>Resolved GitHub Issue #224</li><li>Resolved GitHub Issue #232</li><li>Updated CIO Evaluation Warning to Display Correct Number of Investments w/o Rating</li><li>Updated Add Investment Validation Check on UII</li><li>Made Validation of Funding Code in IT Budget More Consistent</li><li>**No Schema Changes**</li></ul>| &#x2714; |
|  Sprint 2018.03A                    |  March 12, 2018    | <ul> <li> Resolved GitHub Issue #220 </li> <li> Fixed validControlNumber Error when Submission has No OMB Control Number Elements </li> <li> Stripped "-ext" from Submission Requests During Upload </li><li> **No Schema Changes** </li> </ul> | &#x2714; |
|  Sprint 2018.03B                    |  March 26, 2018    |<ul><li>Add FY17 E-Gov Act Reports</li><li>Add Search Feature to Individual Investments Table</li><li>Add SDLC Methodology Highlights Table</li><li>Fix Bug in ITBudget Post Procedure</li><li>**No Schema Changes**</li></ul>| &#x2714; |
|  Sprint 2018.04A                    |  April 9, 2018    |<ul><li>Propose Error Code Prefixes</li><li>Removal of Eliminated Investments from Counts in Investments Highlights Table</li><li>Fix Chrome Data Feed Bug</li><li>**No Schema Changes**</li></ul>| &#x2714; |
|  Sprint 2018.04B                   |  April 23, 2018    |<ul><li>Fix CIO Rating Evaluations Response Not Showing Warnings</li><li>Include Public URLs on Investment Pages</li><li>Fix Agile Activities Missing from Business Case PDFs Bug</li><li>Insert Contracts on Individual Investment Pages</li><li>**No Schema Changes**</li></ul>| |
|  Sprint 2018.05A                    |  May 7, 2018    | <ul><li>Add DCOI Cost Savings Chart</li><li>Insert Contracts on Individual Investment Pages</li><li>Add Error Code Prefixes to Validation Documentation</li><li>Finalize CPIC Vendor GH Page</li><li>Create FY20 Development Branching</li><li>FY20 CPIC Guidance Change Preparations</li><li>**No Schema Changes**</li></ul>| |
|  Sprint 2018.05B                    |  May 21, 2018    | | |
|  Sprint 2018.06A                    |  June 4, 2018    | | |
|  Sprint 2018.06B                    |  June 18, 2018    | | |

## Quick Links to Files

  * **Guidance**
    * [FY19 Guidance](https://community.max.gov/download/attachments/1206026892/FY19%20IT%20Budget%20Capital%20Planning%20Guidance%20%2895%20Solution%29.pdf?api=v2)
    * [FY19 Guidance FAQs](https://community.max.gov/download/attachments/1206026892/CPIC%20CoP%20Meeting_20170627.pptx?api=v2)
    * [FY18 Guidance](https://github.com/ombegov/ITDB-schema/blob/master/docs/TechnicalDocumentation/FY18_Guidance.pdf)
    * [Data Dictionary](https://github.com/ombegov/ITDB-schema/blob/master/draftBY19/)
  * **A11**
    * [OMB Circular A-11](https://www.whitehouse.gov/omb/circulars_a11_current_year_a11_toc)
  * **XSD**
    * [BY19 Draft](https://github.com/ombegov/ITDB-schema/tree/master/draftBY19)
    * [XSD Candidate](https://github.com/ombegov/ITDB-schema/tree/master/src)
  * **SAMPLE XML**
    * [Business Case](https://github.com/ombegov/ITDB-schema/tree/master/src/BusinessCase/Examples)
    * [ITBudget](https://github.com/ombegov/ITDB-schema/tree/master/src/ITBudget/Examples)
* **Validations**    
  * [Current Validations](https://github.com/ombegov/ITDB-schema/blob/master/docs/validations.md)
  
## Asking questions and getting help

We encourage the CPIC community to comment and ask questions here on GitHub. 
Go to the Issues  and add comments and questions. If you wish to contribute to the code base please fork this repository and, after committing your changes to the schema files, create a pull request. As issues are updated, community subscribers will receive automated updates from Github. You can check the status of your issue by visiting the issue page.

We recommend the following format for submitting issues:

  * **Name:** Your name here
  * **Organization:** Your organization here
  * **Urgency:** Critical/High/Medium/Low
  * **Description of Issue:** Describe your issue here
  * **Associated Links:** Put internal and external links to resources or data related to this issue here

When submitting issues on GitHub, do not include any sensitive data. If you have questions or issues pertaining to agency specific data, please directly contact [itdashboardsupport@omb.eop.gov](mailto:itdashboardsupport@omb.eop.gov). 

Below you will find documentation outlining the GitHub Issue Process:
  * [Issue Work Flow](https://github.com/ombegov/ITDB-schema/blob/master/GitHubFlowChart2.jpg)
  * [Issue Review Process](https://github.com/ombegov/ITDB-schema/blob/master/GitHub_Flow-v2.jpg)


## Contributing to the Repo

If you are interested in making modifications to the code in our repo please fork this repo. Once you have implemented your changes, please do a pull request. Once we receive the pull request, we will review the suggested list of changes and update the status. Track the status of your pull request here: [Pull Requests](https://github.com/ombegov/ITDB-schema/pulls).



## FAQ
### How do I view releases/weekly changes to files?

On the ITDB-schema page under the code tab you will see releases – please select
![screenshot a](/docs/help/Capture1.PNG?raw=true "On the ITDB-schema page under the code tab you will see releases – please select")


Under Releases you will see each release – if you select the hashtag icon it will take you to the file changes
![screenshot a](/docs/help/Capture2.PNG?raw=true "Under Releases you will see each release – if you select the hashtag icon it will take you to the file changes")

Changes to the file will be high-lighted in green. 
![screenshot a](/docs/help/Capture3.PNG?raw=true "Changes to the file will be high-lighted in green ")

If you see no changes, then the last version of the file is the latest version of the file!

<br>
 
### I'm having issues downloading Data Feeds with Chrome?

This is a known issue. Please use IE, Firefox or Safari to download Data Feeds.




