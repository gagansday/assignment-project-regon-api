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
