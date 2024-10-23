<!DOCTYPE html>
<html>
<head>
    <title>Vigenere + Affine Cipher</title>
</head>
<body>

<h2>Enkripsi dan Dekripsi (Vigenere + Affine Cipher)</h2>

<form method="POST">
    <label for="text">Input Text:</label><br>
    <input type="text" id="text" name="text" required><br><br>

    <label for="key_vigenere">Key Vigenere:</label><br>
    <input type="text" id="key_vigenere" name="key_vigenere" required><br><br>

    <label for="a_affine">Affine Cipher (a):</label><br>
    <input type="number" id="a_affine" name="a_affine" value="" required><br><br>

    <label for="b_affine">Affine Cipher (b):</label><br>
    <input type="number" id="b_affine" name="b_affine" value="" required><br><br>

    <input type="submit" name="encrypt" value="Enkripsi">
    <input type="submit" name="decrypt" value="Dekripsi">
</form>

<?php
// Fungsi untuk Enkripsi Vigenere Cipher
function vigenere_encrypt($text, $key) {
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);
    $encryptedText = '';
    for ($i = 0, $j = 0; $i < strlen($text); $i++) {
        if (ctype_alpha($text[$i])) {
            $encryptedText .= chr(((ord($text[$i]) + ord($key[$j]) - 2 * ord('A')) % 26) + ord('A'));
            $j = ($j + 1) % $keyLength;
        } else {
            $encryptedText .= $text[$i];
        }
    }
    return $encryptedText;
}

// Fungsi untuk Dekripsi Vigenere Cipher
function vigenere_decrypt($text, $key) {
    $text = strtoupper($text);
    $key = strtoupper($key);
    $keyLength = strlen($key);
    $decryptedText = '';
    for ($i = 0, $j = 0; $i < strlen($text); $i++) {
        if (ctype_alpha($text[$i])) {
            $decryptedText .= chr(((ord($text[$i]) - ord($key[$j]) + 26) % 26) + ord('A'));
            $j = ($j + 1) % $keyLength;
        } else {
            $decryptedText .= $text[$i];
        }
    }
    return $decryptedText;
}

// Fungsi untuk Enkripsi Affine Cipher
function affine_encrypt($text, $a, $b) {
    $encryptedText = '';
    for ($i = 0; $i < strlen($text); $i++) {
        if (ctype_alpha($text[$i])) {
            $char = strtoupper($text[$i]);
            $encryptedText .= chr(((($a * (ord($char) - ord('A'))) + $b) % 26) + ord('A'));
        } else {
            $encryptedText .= $text[$i];
        }
    }
    return $encryptedText;
}

// Fungsi untuk Dekripsi Affine Cipher
function affine_decrypt($text, $a, $b) {
    $decryptedText = '';
    $modInverse = mod_inverse($a, 26);
    for ($i = 0; $i < strlen($text); $i++) {
        if (ctype_alpha($text[$i])) {
            $char = strtoupper($text[$i]);
            $decryptedText .= chr((($modInverse * ((ord($char) - ord('A') - $b + 26)) % 26)) + ord('A'));
        } else {
            $decryptedText .= $text[$i];
        }
    }
    return $decryptedText;
}

// Fungsi untuk mencari modulo invers dari a (affine cipher)
function mod_inverse($a, $m) {
    for ($x = 1; $x < $m; $x++) {
        if ((($a * $x) % $m) == 1) {
            return $x;
        }
    }
    return 1;
}

// Main logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST["text"];
    $keyVigenere = $_POST["key_vigenere"];
    $aAffine = $_POST["a_affine"];
    $bAffine = $_POST["b_affine"];

    if (isset($_POST['encrypt'])) {
        // Langkah 1: Enkripsi  with Vigenere Cipher
        $vigenereEncrypted = vigenere_encrypt($text, $keyVigenere);

        // Langkah 2: Enkripsi with Affine Cipher
        $finalEncrypted = affine_encrypt($vigenereEncrypted, $aAffine, $bAffine);

        echo "<h3>Hasil Enkripsi: $finalEncrypted</h3>";

    } elseif (isset($_POST['decrypt'])) {
        // Langkah 1: Dekripsi with Affine Cipher
        $affineDecrypted = affine_decrypt($text, $aAffine, $bAffine);

        // Langkah 2: Dekripsi with Vigenere Cipher
        $finalDecrypted = vigenere_decrypt($affineDecrypted, $keyVigenere);

        echo "<h3>Hasil Dekripsi: $finalDecrypted</h3>";
    }
}
?>

</body>
</html>
