
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
| &#x2714; | [FY19 Guidance](https://community.max.gov/download/attachments/1206026892/FY19%20IT%20Budget%20Capital%20Planning%20Guidance%20%2895%20Solution%29.pdf?api=v2)   |  DONE  |
| &#x2714;  | Baseline XSD Schema   | DONE  |
| &#x2714;  | Baseline XML Sample Files   | DONE |
| &#x2714;  | [Baseline Validations](https://github.com/ombegov/ITDB-schema/blob/master/docs/validations.md)   | DONE  |
| &#x2714;  | [Technical Documents](https://github.com/ombegov/ITDB-schema/tree/master/docs/TechnicalDocumentation)   | DONE  |
| &#x2714; | [How to submit data to IT Dashboard](https://github.com/ombegov/ITDB-schema/blob/master/docs/ITDB_Submission_Instructions.pdf) | DONE  |
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
|  Publish XSD  &  XSD **Plus** (v1.9.7)    |  August 9, 2017  |
|  Publish Final XSD  &  XSD **Plus** (v1.9.8)    |  August 14, 2017  |
|  **Sprint 2017.08A** Deployed |  August 15, 2017 |
|  Open 2019 UAT Server Delivered with Complete Integrated Validations |  August 18, 2017 **(Tentative Date)** |
|  Draft IT Portfolio Pre-submission (Excel Spreadsheet)  |  August 21, 2017 **(Tentative Date)** |
|  Open 2019 UAT Servers to Agencies for Testing (Validations)    |  August 23, 2017 **(Tentative Date)**  |
|  **Sprint 2017.08B** Deployed |  August 28, 2017 |
|  2019 Data Feeds availbale to Agencies    |  September 11, 2017 **(Tentative Date)**  |
|  Budget Submission Starts  |  September 4, 2017  9am |
|  Budget Submission Ends  |  September 11, 2017 6pm  |
|  Business Case Submission Starts  |  September 11, 2017  6:30pm |
|  **Sprint 2017.09A Deployed** |  September 11, 2017 |
|  Business Case Submission Ends  |  September 15, 2017 6pm |




## Quick Links to Files

  * **Guidance**
    * [FY19 Guidance](https://community.max.gov/download/attachments/1206026892/FY19%20IT%20Budget%20Capital%20Planning%20Guidance%20%2895%20Solution%29.pdf?api=v2)
    * [FY19 Guidance FAQs](https://community.max.gov/download/attachments/1206026892/CPIC%20CoP%20Meeting_20170627.pptx?api=v2)
    * [FY18 Guidance](https://github.com/ombegov/ITDB-schema/blob/master/docs/TechnicalDocumentation/FY18_Guidance.pdf)
    * [Data Dictionary](https://github.com/ombegov/ITDB-schema/blob/master/draftBY19/Data%20Dictionary%20BY19%20CPIC%20Tables%20v1.9.4.xlsx)
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








