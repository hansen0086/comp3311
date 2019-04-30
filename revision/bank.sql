
CREATE TABLE Branch(
    branchName text,
    address text,
    assets int,
    PRIMARY KEY (branchName)
);

CREATE TABLE Account(
   branchName text,
   accountNo text check(accountNo like'^[A-Z]-[0-9]{3}$'),
   balance integer check(balance >=0),
   PRIMARY KEY (accountNo),
   FOREIGN KEY (branchNmae) REFERENCES Branch(branchName)

);
CREATE TABLE Customer(
    CustomerNo integer,
    name text,
    address text, 
    homeBranch text,
    PRIMARY KEY (customerNo),
    FOREIGN KEY (homeBranch) REFERENCES Branch(branchName)
);
CREATE TABLE HeldBy(
    account integer,
    customer integer,
    PRIMARY KEY(account, customer),
    FOREIGN KEY(account)REFERENCES Account(accountNo),
    FOREIGN KEY(customer) REFERENCES Customer(customerNo)
);

