# Food Waste Management System

## 📖 About the Project

Food wastage is a major issue, especially after events, restaurants, and large gatherings where a significant amount of edible food is left unused. At the same time, many people struggle to access regular meals.

This project was developed as a solution to help bridge that gap by creating a platform where surplus food can be donated and distributed to people in need. The system allows donors to register food donations, enables NGOs to manage and request available donations, and helps delivery personnel coordinate pickups and deliveries.

The main objective of this project is to reduce food wastage while making the donation process simple, organised, and efficient.

This project after its completion in development was deployed in AWS ec2 instance set in linux, for server used Nginx, Used Jenkins to set-up a auto building and deployment pathway using CI/CD pipelines.

---

## 🎯 Objectives

* Reduce food wastage by encouraging food donations.
* Connect food donors with NGOs and charitable organisations.
* Provide an organised process for collecting and distributing food.
* Improve transparency by tracking donations and deliveries.
* Create an easy-to-use web application for all users.

---

## 🛠️ Technologies Used

| Category     | Technology            |
| ------------ | --------------------- |
| Frontend     | HTML, CSS, JavaScript |
| Backend      | PHP                   |
| Database     | MySQL                 |
| Local Server | XAMPP                 |

---

## 👥 System Modules

The application consists of three main modules.

### 1. User Module

The User module is designed for people or organisations that want to donate excess food.

Users can:

* Register and log in
* Submit food donation details
* View their donation history
* Provide pickup information

Typical donors include:

* Restaurants
* Marriage halls
* Hotels
* Individuals

---

### 2. Admin Module

The Admin module is responsible for managing the entire donation process.

The administrator can:

* View all food donations
* Verify and manage donation requests
* Assign donations to NGOs
* Track donation status
* Manage users and delivery personnel

This module acts as the central management system for the application.

---

### 3. Delivery Module

The Delivery module is used by delivery personnel who transport food from donors to NGOs or beneficiaries.

Delivery personnel can:

* Register and log in
* View assigned pickup requests
* Check pickup and delivery locations
* Update delivery status

---

## ✨ Features

* Responsive website that works on desktop and mobile devices
* Secure user authentication
* Food donation management
* Admin dashboard for monitoring donations
* Delivery management
* Chatbot support for user assistance
* Simple and user-friendly interface

---

##  Screenshots

### User Module

<img src="img/mobile.jpg">

### Admin Module

<img src="img/Admin.jpg">

### Delivery Module

<img src="img/Delivery_module.jpg">

---

### Responsive Design

<img src="img/responsive.gif">

---

### Chatbot Support

<img src="img/chatbotsupport.jpg">

---

### Secure Login

<img src="img/hash-flow.png">

---

## 🚀 How to Run the Project

1. Download or clone this repository.
2. Copy the project folder into your XAMPP `htdocs` directory.
3. Start **Apache** and **MySQL** using the XAMPP Control Panel.
4. Open **PHPMyAdmin**.
5. Create a new MySQL database.
6. Import the `demo.sql` file from the `database` folder.
7. Open your browser and visit:

```text
http://localhost/folderName
```

Replace `folderName` with the name of your project folder.

---

## ☁️ Cloud Deployment

In addition to running locally via XAMPP, this project has been deployed on AWS using an automated CI/CD pipeline.

### Deployment Architecture

```mermaid
graph LR
    A["Developer Push\nto GitHub"] -->|Webhook Trigger| B["Jenkins Server\nUbuntu"]
    B -->|Pulls Latest Code| C["Deploy Stage"]
    C -->|Syncs Files| D["EC2 Instance\nLinux"]
    D -->|Serves via| E["Nginx + PHP-FPM"]
    E -->|Reads/Writes| F[("MySQL Database")]
    E --> G["Live Application"]
```

### Deployment Stack

| Layer | Tool/Service |
|---|---|
| Cloud Provider | AWS (EC2) |
| OS | Linux |
| Web/App Server | Nginx + PHP-FPM |
| Database | MySQL |
| CI/CD | Jenkins + GitHub Webhooks |

### How It Works

1. Code pushed to GitHub triggers a webhook.
2. Jenkins automatically pulls the latest code.
3. Files are synced to the EC2 instance.
4. Nginx + PHP-FPM serves the application; MySQL handles data.
5. No manual deployment steps required — fully automated.

---

## 📚 Learning Outcomes

Through this project, I gained practical experience in:

* Frontend web development using HTML, CSS, and JavaScript
* Backend development with PHP
* Database design and management using MySQL
* Performing CRUD operations
* Building authentication systems
* Connecting frontend, backend, and database components
* Developing a complete full-stack web application

---

## 🔮 Future Improvements

Some features that can be added in future versions include:

* Real-time donation tracking
* Google Maps integration for pickup locations
* SMS and email notifications
* Payment gateway for monetary donations
* Mobile application for Android and iOS

---

## 👨‍💻 Developed By

**Sujay Joshi**

Information Science & Engineering Student

Basaveshwar Engineering College, Bagalkote

---

## 📄 License

This project was developed as part of an academic engineering project for learning and educational purposes.
