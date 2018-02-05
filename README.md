
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
|  Guidance Released |  May 12, 2017 |
|  Finalized target dates based on guidance |  May 12, 2017 |
|  Published Baseline XSD (v1.8.x)|  May 19, 2017 |
|  Publish Baseline XSD Plus (v1.8.x)|  May 26, 2017 |
|  Validation logic development | June 2017  |
| [2019 UAT Server Available](https://myuat-2019.itdashboard.gov) **MAX REQ**| July 5, 2017|
|  Target Date for [XSD Candidate](https://github.com/ombegov/ITDB-schema/tree/master/src) (v1.9.0) shared to vendors    |  July 5, 2017  |
|  Target Date for [XSD **Plus** Candidate](https://github.com/ombegov/ITDB-schema/tree/master/draftBY19) (v1.9.0)  |  July 31, 2017 |
|  Final Guidance  and [A-11](https://www.whitehouse.gov/omb/circulars_a11_current_year_a11_toc)   |  August 1, 2017 |
|  2019 UAT Server Delivered with Integrated Validations |  August 4, 2017 |
  Publish XSD  &  XSD **Plus** (v1.9.7)    |  August 9, 2017  |
|  Publish Final XSD  &  XSD **Plus** (v1.9.8)    |  August 14, 2017  |
|  Draft IT Portfolio Pre-submission (Excel Spreadsheet)  |  August 21, 2017 |
|  Open 2019 UAT Server Delivered with Complete Integrated Validations Part 1 |  August 22, 2017  |
|  Open 2019 UAT Servers to Agencies for Testing (Validations)    |  August 23, 2017   |
|  Open 2019 UAT Server Delivered with Complete Integrated Validations Part 2 (Hotfix) |  August 28, 2017  |
|  2019 IT Portfolio Data Feed available to Agencies    |  September 6, 2017   |
|  2019 Business Case & Standard Investment Report Data Feeds available to Agencies    |  September 28, 2017   |


## Submission Dates 
|  Event  |  Date |
|:-------------|:-------------:|
|  BY18 Submission Ends  |  August 31, 2017 |
|  Budget Submission Starts  |  September 5, 2017  9am |
|  Budget Submission Ends  |  September 11, 2017 10pm  |
|  Business Case Submission Starts  |  September 11, 2017  6:30pm |
|  Business Case Submission Ends  |  September 18, 2017 11:59pm |
|  Standard Investment Reports Submission Starts  |  September 11, 2017  6:30pm |
|  Standard Investment Reports Submission Ends  |  September 18, 2017 11:59pm |
|  Agency IT Portfolio Summary Submission Starts  |  January 3, 2018 12:01am |
|  Agency IT Portfolio Summary Submission Ends  |  January 9, 2018 11:59pm |
|  Business Cases and Standard Investment Reports Submission Starts  |  January 10, 2018 12:30am |
|  Business Cases and Standard Investment Reports Submission Ends  |  January 12, 2018 5:00pm |
|  IT Dashboard Data from President's Budget Submission Displays on Public Site  |  February 12, 2018 12:01am |

## Sprint/Hotfix Deployment Dates

|  Event  |  Date |  Highlights | Deployed |
|:-------------:|---|--------|:---:|
|  Sprint 2017.12B                    |  December 19, 2017 | <ul> <li> Resolved GitHub Issue #182 </li> <li> Removed Baselined Fields in Regular Updates - **Schema Change** </li> </ul> | &#x2714; |
|  Sprint 2018.01A                    |  January 2, 2018   | <ul> <li> Resolved GitHub Issue #207 </li> <li> Added Validations for IT Towers </li> <li> Added Shared Services Identifier "0099" </li> <li> **No Schema Changes** </li> </ul>  | &#x2714; |
|  Sprint 2018.01B                    |  January 15, 2018  | <ul> <li> Resolved GitHub Issue #222 </li> <li> Resolved GitHub Issue #218 </li> <li> Resolved Error Message Reference to Performance Metrics vs. Contracts </li> <li> Resolved Duplicated Cost Pool Data Response </li> <li> **No Schema Changes** </li></ul>| &#x2714; |
 |  Sprint 2018.01C                    |  January 29, 2018  | <ul> <li> Resolved GitHub Issue #212 </li> <li> Enabled Investment Summary Pages </li> <li> Enabled Business Case PDF Links </li> <li> Updates to ITDB Chart Visualizations </li> <li> **No Schema Changes** </li> </ul> | &#x2714; |
|  Sprint 2018.02A                    |  February 12, 2018 | | |
|  Sprint 2018.02B                    |  February 26, 2018 | | |
|  Sprint 2018.03A                    |  March 12, 2018    | | |
|  Sprint 2018.03B                    |  March 26, 2018    | | |

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




