# SWDesign
### This project used ***object-oriented PHP***.
This repository is a project that was written when studying the subject 'Software Design'.

1. **Apache, PHP, MySQL** must be installed
2. Source codes must be located in Apache **<Default root folder ***(htdocs)***>**
3. The password of MySQL root must match the code ***(Database.php : line 8)***
4. ***swdesign*** database must exist in MySQL

```MySQL
CREATE DATABASE swdesign;
```

5. Use the swdesign database you just created.
```MySQL
use swdesign;
```

6. The ***board, member*** **table** must exist in MySQL.
```MySQL
CREATE TABLE board(
no int(10) unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
id varchar (32) NOT NULL,
nickname varchar (10) NOT NULL,
password varchar(32) NOT NULL,
title text NOT NULL,
content longtext NOT NULL,
date datetime NOT NULL,
hit int(10) unsigned NOT NULL default 0
);

CREATE TABLE member(
id varchar(32) NOT NULL, 
nickname varchar(10) NOT NULL,
password varchar(32) NOT NULL, 
no int(10)
);
```

7. To use an administrator account, insert ***'admin'*** in the **'member' table** of MySQL.
```MySQL
INSERT INTO member (id, nickname, password) VALUES ('admin', 'admin', 'admin');
```
8. Go to **127.0.0.1/index.php** in the web browser
+ ***ID: admin / Password: admin*** will log in as an administrator account.
+ To use as a regular user account, sign in as 'JOIN' and login
9. Main's posts will only be output if the posts are registered through 'WRITE'.
10. 'SEARCH RECIPE' at the top will search under a title within a registered post, so find the posts must be registered
11. Hyperlink section at the bottom: If you are not logged in, even 'RECOMMEND RECIPE' will appear. When logged in, the user will see 'MYPAGE', and the administrator will see 'ADMIN'.
