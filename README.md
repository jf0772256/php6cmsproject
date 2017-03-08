# Single Page MVC PHP Content Management System
## By Jesse Fender

### Purpose & Introduction:
This is a pet project to test ability to pick up PHP and deploy it in a real world like application. This project was started in early January and have been regularly added to, but only was put on git hub Febuary 5th. I have no intention of selling or using this code past the completion... This project helps prove my skill with PHP since January 1, through the help of instructors with my CIS  namely Kirsten Markley, and friend Brian Shinn, have helped me learn the language rapidly and use it in real world application.

### How to install and set up the database:
This process should be really easy. There is a connection controller (model/database.db) where you enter the connection and database details of the database. It is planned that eventually this will be tied to a file that you create when creating the installation.
After updating the connection details, open a web browser and navigate to the url where the files have been uploaded, and type in the following at the end '/administration/installer/install.php' this will take you to the installer written for creation of the needed tables with in the database. Please note that the installer cleans the old tables out if you are reinstalling so that all the data is fresh... meaning that the site will reset to the day you installed it if you have issues. This was done to maintain data integrity and will ensure that the latest available version of the tables are installed...
It is pretty self explanatory from here, click on the blue buttons and follow the instructions.
You will set up the first user account here too, this is required, and since the account is privileged, you will need to ensure that you choose your passwords wisely, as you cannot reset them unless you reinstall the CMS.
As it stands right now the last button will redirect you to a link that I currently have to point to my server in my directory tree... You will want to erase all the way back to the base URL and press your enter key to be directed to the home page. Note though that I do plan on making this link dynamic and forcing a redirect back to the base index.php.

### Logging in
Use the user name and password you selected, either for the first user or a personal account, press the log in, you will be directed to your user dashboard.  To log out click the log out button and close the browser to ensure session is closed completely.

### The Dashboard:
This is an ongoing project, and thus some features have not received full implementation, when you click on one of these, provided that all the files with in the /partials/ folder were uploaded to the server you will be directed to a friendly page advising you that the feature that you have selected is not available. Also be aware that there are protections against attempting to view the files by directly navigating to the partial and included folders, these will just simply display a page stating that your are not authorized to be there and nothing else...
