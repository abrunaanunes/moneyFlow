# Classes

### abstract class User
#### Atributtes
    - id
    - name
    - document
    - email
    - password
### Methods
    
----------------------------
### class JudicialPerson extends Person
### Atributtes
### Methods
---------------------------
### class NaturalPerson extends Person
### Atributtes
### Methods
---------------------------
### abstract class Account
### Attributes 
    - id
    - account_key
    - user_id
### Methods
    - getBalance();
---------------------------
### class JudicialAccount extends Account
### Atributtes
### Methods
---------------------------
### class NaturalAccount extends Account
### Atributtes
### Methods
    - sendMoney();
---------------------------
### class Transaction
### Atributtes
    - id
    - amount
    - status 
    - account_id (payer)
    - user_id (payee)
### Methods
    - rollbackTransaction();


