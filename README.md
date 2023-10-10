# WSU Accessibility Report
This plugin generates a report of all accessibility issues on one page.
## Requirements
- WSUWP Gutenberg Accessibility
## Functionality
- Ability to select the accessibility issue types that show up on the report (Errors, Alerts, Warnings). 
- Ability to email user that there are issues on their website.
	- Selecting how frequent the user should be emailed (none, weekly, monthly)
	- Gives user the capablity of keeping the default email message or entering their own. 
		- To change the default message, change the content in the ```cahnrs_generate_report_content()```
	- Users that can be emailed is pulled from the the registered users on the website (administrators and editors only).
- Creates an admin dashboard widget. 
## To do
- Show history on how accessibility issues have improved over time.