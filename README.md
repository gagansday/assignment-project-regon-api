##### Requirements

-   [docker](https://www.docker.com/)
-   [docker-compose](https://docs.docker.com/compose/)
-   [docker-desktop](https://docs.docker.com/desktop/) (optional)

#### Only if you are cloing repository for the first time.

Install dependences if you don't have composer installed on your local system

**bash shell**

`docker run --rm -v ${PWD}:/app composer install --ignore-platform-reqs`

**fish shell**

`docker run --rm -v $PWD:/app composer install --ignore-platform-reqs`

#### Quick Start

Create environment file:

`cp .env.example .env`

Build and run docker environment:

`sail up --build -d`

Install dependences:

`sail composer install`

`sail npm install`

Migrate Database:

`sail artisan migrate`

Build assets:

`sail npm run prod`

Run artisan commands:

`sail artisan [command]`

To test mail feature:

`sail artisan queue:work`

Stop docker environment:

`sail down`

## Task

Basically you have to develop a web application that will create User account and verification
Access without an account is not possible. To set them up, the user provides an email address, password and tax identification number.

https://api.stat.gov.pl/Home/RegonApi
The system makes a query to the REGON database looking for the NIP number.
If he finds an entry and in the activities carried out, according to PKD, it is "6920Z". This is the message displayed:
“The verification was successful. An account has been created for NAZWA, ADDRESS (from the REGON database) ”.

If the condition is not met, it is impossible to connect to the REGON database, there is no entry in it or it does not contain from the appropriate PKD, the user receives the following message:
"Verification is ongoing. We will contact your office to confirm the data. "
An e-mail with the account data is sent to our address.

At all we need to be sure about your well coding practice in architecting project.. conventions, structure, how make the foundation of the app and organize files and folders through most important coding guidelines and quality. This knowledge is indispensable to undertake work in our team room.

#### Some tax ids

-   7391195275
-   7123277406
