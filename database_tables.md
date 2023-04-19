# cart_items table:

cart_item_id	int unsigned	primary key
customer_id		int unsigned	foreign key
product_id		int unsigned	foreign key
quantity		int unsigned


# categories table:

category_id		int unsigned 	primary key
name 			varchar(255)

# customers table:

customer_id		int unsigned	primary key
store_id		int unsigned 	foreign key
first_name		varchar(255)	
last_name		varchar(255)
email			varchar(255) 	(unique)
password		varchar(255)
account_number	int unsigned
cc_number		varchar(255)
exp_date		varchar(255)
cvv_number		varchar(255)
address			varchar(255)
state			enum('AL','AK','AZ','AR','CA','CO', ...)
zip_code		int unsigned
phone			varchar(255)

# inventory table:

inventory_id   	int unsigned    primary key
product_id   	int unsigned    foreign key
store_id		int unsigned	foreign key
quantity		int unsigned 	

# order_items table:

order_item_id	int unsigned 	primary key
order_id		int unsigned 	foreign key
product_id		int unsigned 	foreign key
quantity 		int unsigned
price			decimal unsigned

# orders table:

order_id		int unsigned	primary key
customer_id		int unsigned	foreign key
store_id		int unsigned	foreign key
order_date		datetime
tracking_number	int unsigned
status			enum('Pending','Processing','Shipped','Delivered','Cancelled','Returned')
delivery_date	datetime	


# po_items table:

po_item_id		int unsigned	primary key
po_id			int unsigned 	foreign key
product_id		int unsigned	foreign key
quantity		int unsigned 	
price 			decimal unsigned

# products table:

product_id   	int unsigned    primary key
name  			varchar(255)
description  	varchar(255)
thumbnail_path  varchar(255)
price  			double
category_id  	int unsigned    foreign key
brand  			varchar(65)
featured  		bit(1)

# purchase_orders table:

po_id			int unsigned	primary key
store_id		int unsigned	foreign key
order_date		datetime
tracking_number int unsigned
status			enum('Pending','Processing','Shipped','Delivered','Cancelled')
delivery_date	datetime	
received		bit(1)			

# stores table:

store_id		int unsigned	primary
name			varchar(255)
address			varchar(255)
city			varchar(255)
state			enum('AL','AK','AZ','AR','CA','CO', ...)
zip_code		int unsigned
phone			varchar(255)
