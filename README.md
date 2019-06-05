# ITDB-schema
This repository is supplied by OMB for the CPIC Community. It's purpose is to act as a distribution point of CPIC Guidance schema files and also to act as as collaborative forum for reviewing proposed schema changes. Here CPIC developers can find the latest [OMB CPIC Guidance](https://www.whitehouse.gov/wp-content/uploads/2018/06/fy-2020-it-budget-guidance.pdf), XSD files, and their respective sample XML submission files, all of which will allow them to validate the structure of their own XML submission files. Our goals are as follow:
 
 * Community members should be able to download the latest [CPIC Guidance](https://www.whitehouse.gov/wp-content/uploads/2018/06/fy-2020-it-budget-guidance.pdf), XSD and XML files.
 * As changes are made by OMB developers, the XSD and sample XML files will be automatically updated.
 * Members of this repository receive alerts when files are updated, assuming they subscribe to GitHub notifications.
 * Members of this repository may fork and create pull requests of this code base if they wish to suggest changes to the schema or later submit additional XML test files.
 * A core team will monitor this repository for issues, questions and pull requests and will respond as issues and questions are raised and changes are proposed.


## Last 5 Sprint/Hotfix Deployment Dates

|  Event  |  Date |  Highlights | Deployed |
|:-------------:|---|--------|:---:|
|  GSA Deployment v2019.02.A Hotfix 1                   | February 18, 2019 | <ul><li>Production Defect: SIL Get File OMB assigned identifiers disabled</li><li>Production Defect: Unable to update multiple products</li></ul> | &#x2714;|
|  GSA Deployment v2019.02.A Data Change                   | March 13, 2019 | <ul><li>Change Request: Flipped a Budget Code from inactive to active.</li><li>Production Defect: Concatenated PIID truncation at 500 characters</li></ul> | &#x2714;|
|  GSA Deployment v2019.04.A                   | April 4, 2019 | <ul><li>Production Defect: Business Case PDFs/HTML Header mismatch with the 2020 Guidance lettering system</li><li>Maintenance Update: Updated Drupal and related libraries</li></ul> | &#x2714;|
|  GSA Deployment v2019.04.B/C                   | April 18, 2019 | <ul><li>Production Defect: Life Cycle Costs Data Feed FY + 3 pulling incorrect data</li><li>Production Defect: Part 03 Data masked within four Data Feeds</li></ul> | &#x2714;|
|  GSA Deployment v2019.05.A                   | May 3, 2019 | <ul><li>Production Defect: Unauthenticated users can access Part 03 Investment Summary Pages</li><li>Production Defect: Part 03 Investments excluded from Investment Listing Table</li><li>Change Request: Implemented Conditional Press Feedback Form on IT Dashboard</li><li>Maintenance Update: Updated Drupal and related libraries</li></ul> | &#x2714;|


## Pre-Submission Dates FY 2021 

|  Event  |  Date |
|:-------------|:-------------:|
|  95% Guidance Released |  June 5, 2019  |
|  FY 2021 Data Dictionary Release Date | TBD |
|  FY 2021 Validations Document Release Date | TBD |
|  FY 2021 Submission Instructions Release Date  |  TBD |
|  FY 2021 XSD Release Date |  TBD |
|  FY 2021 UAT Site Released to Vendors   |  TBD |
|  FY 2021 UAT Site Released to Agencies   |  TBD |
|  Draft IT Portfolio Submissions  |  TBD |
|  IT Dashboard 'Go Dark' Period  |  TBD |
|  FY 2021 Pre-Decisional Submissions   |  TBD |


## Quick Links to Files

  * **Guidance**
    * [FY20 Guidance](https://www.whitehouse.gov/wp-content/uploads/2018/06/fy-2020-it-budget-guidance.pdf)
  * **A11**
    * [OMB Circular A-11](https://www.whitehouse.gov/wp-content/uploads/2018/06/a11.pdf) 
  * **XSD**
    * [FY20 XSD Final](https://github.com/ombegov/ITDB-schema/tree/v2.0.8)
  * **SAMPLE XML**
    * [ITBudget](https://github.com/ombegov/ITDB-schema/tree/master/src/ITBudget/Examples)
    * [Business Case](https://github.com/ombegov/ITDB-schema/tree/master/src/BusinessCase/Examples)
    * [Standard Investment Report](https://github.com/ombegov/ITDB-schema/tree/master/src/InvestmentReport/examples)
    * [CIO Rating](https://github.com/ombegov/ITDB-schema/tree/master/src/CIORating/Examples)
    * [Contracts Report](https://github.com/ombegov/ITDB-schema/tree/master/src/ContractsReport/examples)
    * [Systems Inventory List](https://github.com/ombegov/ITDB-schema/tree/master/src/SystemsInventory/examples)
    * [Submission Confirmation](https://github.com/ombegov/ITDB-schema/tree/master/src/SubmissionConfirmation/examples)
* **Data Dictionary & Validations** 
  * [FY20 Data Dictionary](https://github.com/ombegov/ITDB-schema/blob/master/BY20%20Data%20Dictionary%20%26%20Enumerations.xlsx)
  * [FY20 Validations](https://github.com/ombegov/ITDB-schema/blob/master/BY20%20Application%20Validations.xlsx)
  

## Submission Dates FY 2020 
|  Event  |  Date |
|:-------------|:-------------:|
|  BY19 Submission Ends  |  August 31, 2018 |
|  IT Dashboard 'Go Dark' Period  |  September 1, 2018 through September 9, 2018 |
|  Draft Pre-Decisional Budget Agency IT Portfolio Summary Submission Starts  | August 20, 2018 |
|  Pre-Decisional Budget Agency IT Portfolio Summary Submission Starts  | September 10, 2018 |
|  Pre-Decisional Budget Agency IT Portfolio Summary Submission Ends  |  September 14, 2018 |
|  Pre-Decisional Business Case (BC) and Standard Investment Report (SIR) Submission Starts  |  September 11, 2018 |
|  Pre-Decisional BC and SIR Submission Ends  |  September 21, 2018 |
|  President's Budget Agency IT Portfolio Summary Submission Starts  |  February 12, 2019 |
|  President's Budget Agency IT Portfolio Summary Submission Ends  |  February 19, 2019 |
|  President's Budget BC and SIR Submission Starts  |  February 13, 2019 (AITPD submissions are open immediately following a completed AITPS Submission Confirmation) |
|  President's Budget BC and SIR Submission Ends  |  March 8, 2019 |
|  IT Dashboard Data from President's Budget Submission Displays on Public Site  |  March 18, 2019 |
|  Congressional Justification Agency IT Portfolio Summary Submission Starts  |  March 18, 2019 |
|  Congressional Justification  Agency IT Portfolio Summary Submission Ends  | April 5, 2019 |
|  Mid-Session Review Agency IT Portfolio Summary Submission Starts  |  June 24, 2019 |
|  Mid-Session Review Agency IT Portfolio Summary Submission Ends  | June 28, 2019 |

  
  
## Asking questions and getting help

We encourage the CPIC community to comment and ask questions here on GitHub. 
Go to the Issues  and add comments and questions. If you wish to contribute to the code base please fork this repository and, after committing your changes to the schema files, create a pull request. As issues are updated, community subscribers will receive automated updates from Github. You can check the status of your issue by visiting the issue page.

We recommend the following format for submitting issues:

  * **Name:** Your name here
  * **Organization:** Your organization here
  * **Urgency:** Critical/High/Medium/Low
  * **Description of Issue:** Describe your issue here
  * **Associated Links:** Put internal and external links to resources or data related to this issue here

When submitting issues on GitHub, do not include any sensitive data. If you have questions or issues pertaining to agency specific data, please directly contact [itdb-support@gsa.gov](mailto:itdb-support@gsa.gov). 

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




