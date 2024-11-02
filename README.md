
# Courier Management System(CMS)
This is a Courier Website which allows user to place the Courier, check the delivery status, give the feedback and delete the feedback ,registration and updation of staff details,addition and deletion of franchise of the CMS. This supports all the **CRUD** opertaions.


#### "Efficient Delivery at Your Doorstep" : A Courier Management System
## Tech Stack

- Front-end:
    - HTML
    - CSS
    - BOOTSTRAP
- Back-end:
    - PHP
- Database:
    - MySQL     (Ver 8.0.31 for Win64) 

## Tools and Utilities Used

- Visual Studio Code
- XAMPP (Version 8.1.12 )
- Git and GitHub


## Installation

1. Clone the repository or download the ZIP file of the project.

2. Install XAMPP or a similar web server software if you haven't already.

3. Start the Apache and MySQL modules in XAMPP.

4. Run the setup_db.bat to create db for cms.
        
5. Move the project files (login folder) into the htdocs folder in your XAMPP installation directory.

    - Inside the login folder, go to partials, then to _dbconnect(php file).
    - Change the username and password accordingly to your MySQL.

6. Now Open your web browser and navigate to ""http://localhost/login/signup.php"".

7. Do Signup to the website and then Login.
    - Now open the MySQL and query the "users" Table 
    ~~~
    Select * from users;
    ~~~
It will show the registered users. (**CREATE Operation** is performed here and when the user doing **Signup** to the website).

It reads the data from the database (Users Table), and then performs the desired operation (**READ Operation**).

_Similarly, Place Courier and Check for delivery will work(Create and Read operations).
Check for delivery status will show the delivery status after 24 Hours._


- To check in MySQL(cms) database the details which user entered in place courier form, write the query given below :

    ~~~
    Select * from place_courier;
    ~~~


8. Now, Feedback feature allows the user to give the feedback for the User Experience.
If the user wants to delete his/her feedback or to edit the feedback, we provided the feature of *Delete Feedback* which performs the **DELETE Operation**

**"Only the user who has signed up and logged in can place a courier and provide Feedback."**

_All the operations whatever the user will do will be updated on the database. You can query the database and check for the operations._

- To check the feedback given/deleted by the user in MySQL (cms) Database, write the query given below:

    ~~~
    Select * from feedback;
    ~~~
 


9.Now, staff registration can also be completed here. If a user mistakenly entered incorrect details or wishes to update their information, they can do so here.(**UPDATE Operation**)
- To check the updated staff details in MySQL (cms) Database, write the query given below:

    ~~~
    Select * from staff;
    ~~~
## Authors

- [@anubhavagnihotri](https://github.com/anubhavagnihotrii)


## Support

For support, email anubahvagnihotri@gmail.com .

