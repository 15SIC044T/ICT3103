<?php
define('AES_256_CBC', 'aes-256-cbc');
echo "************************************\n";
echo "**           FS Decrypter         **\n";
echo "************************************\n";

echo "Option 1: RSA Decryption\n";
echo "Option 2: AES Decryption\n";
while(TRUE) {
    echo "Enter the option: ";
    $stdin = fopen ("php://stdin","r");
    $option = trim(fgets($stdin));
    fclose($stdin);
    if($option == 1 || $option == 2) {
        break;
    }
    else {
        echo "Please enter the correct option!\n";
    }
}

if($option == 1) {
    echo "Enter your encrypted AES key: ";
    $stdin = fopen ("php://stdin","r");
    $encAES = trim(fgets($stdin));
    fclose($stdin);
    if(!file_exists($encAES)) {
        echo "No such file!";
        exit(0);
    }
    else {
        $ext = pathinfo($encAES, PATHINFO_EXTENSION);
        if($ext == "key") {
            $aesData = file_get_contents($encAES);
        }
        else {
            echo "Not a key file!";
            exit(0);
        }
    }

    echo "Enter your private key: ";
    $stdin2 = fopen ("php://stdin","r");
    $privKey = trim(fgets($stdin2));
    fclose($stdin2);
    if(!file_exists($privKey)) {
        echo "No such file!";
        exit(0);
    }
    else {
        $ext = pathinfo($privKey, PATHINFO_EXTENSION);
        if($ext == "key") {
            $privData = file_get_contents($privKey);
        }
        else {
            echo "Not a key file!";
            exit(0);
        }
    }
    
    openssl_private_decrypt($aesData, $dAesData, $privData);
    file_put_contents($encAES, $dAesData);
    echo "Decrypted successfully!";
}
else {
    echo "Enter the path of the file: ";
    $stdin = fopen ("php://stdin","r");
    $fpath = trim(fgets($stdin));
    fclose($stdin);
    if (!file_exists($fpath)) {
        echo "No such file!";
        exit(0);
    }
    $encData = file_get_contents($fpath);

    echo "Enter the path of the AES key: ";
    $stdin2 = fopen ("php://stdin","r");
    $kpath = trim(fgets($stdin2));
    fclose($stdin2);
    if(!file_exists($kpath)) {
        echo "No such file!";
        exit(0);
    }
    else {
        $ext = pathinfo($kpath, PATHINFO_EXTENSION);
        if($ext == "key") {
            $aes = file_get_contents($kpath);
        }
        else {
            echo "Not a key file!";
            exit(0);
        }
    }
    
    $parts = explode(':', $encData);
    $decrypted = openssl_decrypt($parts[0], AES_256_CBC, $aes, 0, base64_decode($parts[1]));
    file_put_contents($fpath, $decrypted);
    echo "Decrypted successfully!";
}
?>

