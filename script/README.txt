This is the README file for the use of the Decrypter PHP script on a Windows machine 

The following components are needed in order for Decrypter to work:
1) OpenSSL
2) PHP

For the ease of installation, XAMPP installer is used to install both the OpenSSL and PHP on the Windows machine. You can download it at https://www.apachefriends.org/download.html.

Configure your Windows System Environment
1) Access the PC system properties at Control Panel > System and Security > System > Advanced system settings.
2) Click on Environment Variables under Advanced tab.
3) Click on New under System variables.
4) Set the Variable name as OPENSSL_CONF and Variable value as C:\xampp\apache\conf\openssl.cnf. Click OK once done.
5) Select Path under the System variables and click on Edit.
6) Create New and enter the PHP path(C:\xampp\php).
7) Restart your computer for the changes to take effect.

Execute the Decrypter
1) Open up a command prompt.
2) Change the directory to location of Decrypter.
3) Enter command "php decrypter.php" to execute the script.
