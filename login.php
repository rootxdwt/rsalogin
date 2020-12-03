<?php
/*
made by xdwhat
the user should send the key file here with POST, using name "pubkey"
*/
session_start();
function error($a) {
    echo "<script>alert('$a'); history.back(); </script>";
    exit();
}

if(isset($_POST['submit'])){

    $public_key = file_get_contents("/etc/public-key.pem"); //public key directory

    if (is_uploaded_file($_FILES['pubkey']['tmp_name'])) {

        $fSize = filesize($_FILES['pubkey']['tmp_name']) / 1024; //get the file size on kb

        if ($fSize>5) {

            error("file is too big");
        }else {
            $fileType = pathinfo($_FILES['pubkey']['name'], PATHINFO_EXTENSION);

            //check file type
            if ($fileType !== "pem") {
                error("not a key file");

            }else {
                //get the content of the uploaded key file
                $key = file_get_contents($_FILES['pubkey']['tmp_name']);

                //generate random base64 string
                $rand_string = base64_encode(bin2hex(random_bytes(64)));

                //encrypt it with the public key on the server (1)
                openssl_public_encrypt($rand_string, $encrypted, $public_key);

                //decrypt the (1)encrypted string with uploaded privatekey
                openssl_private_decrypt($encrypted, $new, $key);

                //if (1) and (2) is same
                if($new !== $rand_string) {

                    //wrong key, login failed
                    error("wrong key providen");
                }else {

                    //login success! do whatever you want (ex: generate a session)
                        }
                }

            }
        }

    }else {
        error("no keyfile uploaded");
    };

?>
