# Paydek Test Task

## Task 

Implement a system where you have many users
Each user has multiple accounts
Each account can be either sending or receiving and is associated with the currency
You can send money from users sending account into receiving account (own or another user)
If the currency is different between sending and receiving accounts conversion needs to take place
Use https://exchangeratesapi.io/ for the conversion rate
Use USD/EUR/GBP/RON as available currencies
Since you are building a financial system data consistency is important, make sure your solution highlights it

Free to use whatever PHP based toolset, MySQL for the database
Can be done as an API/ Command LIne Tool/ Web App

Instructions need to be clear enough that anyone can check out the code and get the app up and running and able to test 

## Solution

###Install docker-compose

For linux
```
sudo curl -L https://github.com/docker/compose/releases/download/1.17.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

For others - https://docs.docker.com/compose/install/#install-compose


###Launch 
```
./docker-compose up -d
./docker-compose exec backend php artisan db:seed
```

PMA http://localhost:8081

### Rest API

#### Users

List users
```
GET http://localhost/api/users 
```

Show one user
```
GET http://localhost/api/users/1
```

#### Accounts

List accounts
```
GET http://localhost/api/accounts
```

Show one account
```
GET http://localhost/api/accounts/1
```

#### Transfers

List transfers
```
GET http://localhost/api/transfers
```

Show one transfer
```
GET http://localhost/api/transfers/1
```

Create new transfer
```
POST http://localhost/api/transfers
sender_id=1&receiver_id=2&amount=1
```
- `sender_id` - account_id, which send money
- `receiver_id` - account_id, which receive money 
- `amount` - amount to be sent



