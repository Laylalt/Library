# Library

This is a mock-up of a public library website. If you don't want to do the installation you can find videos in the videos folder.

## To use: 

Since it uses a database and it's not a website yet to be able to acces it you need to follow this steps: 
1. Install XAMPP
2. In the xampp>htdocs folder put all of this except for the folder library
3. Add the folder library to xampp>mysql>data 
4. Now using the XAMPP control panel you can access the database and the website by starting Apache and MySQL and then click on admin for both

## About:

The website will be different depending if you are an employee or an user.

**Both employees and users have:** 

  -List of personal active loans and history of loans
  
  -List of personal active fees and history of fees
  
  -See available books and search them by author, genre and title.
  
  -Change your user name and password
  
  -Top 5 of borrowed books
  
  -Top 5 of users
  
**Students have:**

  -Request book option, so that employees can set aside that book and you can go get it within a certain time frame.

**Employees have:**

  -Check all request, active fees and active loans
  
  -Add, edit or delete books
  
  -Add, edit or delete users 
  
  -Check in books
  
  -Check out books
  
  -Take fee payments 
  
  -Borrow books for themselves 
  
 **Restrictions (Apply for both employees and users):**
 
  -You cannot borrow more than two books at a time
  
  -You have one week to return the book, the website will automatically calculate the fee if you are late, it adds for every late day
  
  -You cannot borrow books for one week as a penalty after having a fee
  
  -You can return a book and not pay the fee, you will not be able to borrow books till you pay it but the fee will not increase
  
  -If an employee wants to return a book or pay a fee they need to be registred with another coworker to avoid foul play
  
