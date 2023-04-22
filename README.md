# WiredWorld

Currently live at https://wiredworld.gibbyb.com!

WiredWorld is a DBMS course project for an online electronics vendor website. The website is entirely self-hosted (using a 2011 Macbook Pro running Debian)  using multiple docker containers including a PHP server container for server side scripting, a MySQL container for the database, an adminer container for managing the database, and Caddy as the file server & reverse proxy. 

# *Website Information*

From the live website, you can begin by either registering or logging in. Once you are logged in select the store you would like to have your orders ship from. Once you have made a selection you will be redirected to the front page. Now that you have a store tied to your account, you can see the quantity of each item from that store and add them to your cart & check out. When you check out, you may either enter your credit card information, or if you are a business, you may pay with your account number. 

# *Database Information:* 

You can access the database with the following credentials:

Server/Host: wwdb.gibbyb.com

Username: ww-db

Password: ww2023

Database Name: wired-world-db

The Database port on my (Gabriel's) router is forwarding to the database, so as long as you have an internet connection, you can access it with these credentials. If you run into any trouble, the database is accessible from https://adminer.gibbyb.com with the above credentials. 

# Authors

Gabriel Brown

Kolten Donovan

Nathan Blakeney

Katie Breaux

Hope Bullock

