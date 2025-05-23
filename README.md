# CourierManagementSystem
Create a PHPMyAdmin database named 'courier'. After the database is created, import the SQL file named 'courier.sql' from this repository.

To log in to the admin panel, use the username 'admin' and the password 'password'. Only the admin can register employees.

The SQL tables are empty, so you'll need to register customers and employees manually. 

Here’s a breakdown of how this localhost project works:

You can play three roles — customer, admin, and employee — to test everything.

As a customer, you can:
- Register and create your account  
- Place an order  
- Cancel your order at any point before it's received by the employee  
- Track the status of your order — whether it’s approved, declined, received, or delivered  
- View time logs, including:
  - When the order was placed  
  - When the order was received by the employee  
  - When it was delivered  
- See the name of the employee who received and delivered your order

As an admin, you can:
- Log in with the provided credentials  
- Register employees  
- Approve or decline customer orders (if a customer cancels, the order can't be approved)  
- View the full time log of each order, including when it was placed, received, and delivered  
- View the number of customers, employees, and orders in the system  
- Delete customer, employee, or order data from the database  
- The role of the admin is to register employees, approve or decline orders, and manage the system's data.  

As an employee, you can:
- Only receive and deliver orders that have been approved by the admin  
- View the order details and time logs so you know when to pick up and deliver the orders  
- See the name of the employee who handled the order, whether it’s been received or delivered

This system ensures that everyone — customers, admins, and employees — is kept up to date at every step, with clear tracking, real-time updates, and detailed order histories.
