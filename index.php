<?php
/**
 * Wunschliste
 * Dateiname: index.php
 * @autor Jan Dietrich
 * @version 1.0
 * @date 25.05.2024
 */

// schreibt den header und h1
function writeHeaderAndHeadline()
{
    echo "<!DOCTYPE html>
          <html lang=\"de\">
          <head>
          <title>Wishlist</title>
          <link rel=\"stylesheet\" href=\"style.css\">
          </head>
          <body>
          <h1>Wunschliste</h1>";
}

// schreibt den Start des Formulars
function startForm($method, $url){
    echo "<form method=\"$method\" action=\"$url\">";
}

// Schreibt ein Inputfeld
function writeInputField($text, $name, $value = ''){
    echo "<label for=\"$name\">$text: </label>
          <input type=\"text\" name=\"$name\" id=\"$name\" value=\"" . htmlspecialchars($value) . "\">
          <br><br>";
}

// Schließt das Formular und den Footer
function closeFormAndFooter(){
    echo "<input type=\"submit\" value=\"weiter\">
          </form>
          </body></html>";
}


// überprüft die Wünsche
function validateWishes($wishes){
    foreach ($wishes as $wish) {
        if (!empty($wish) && preg_match('/^[a-zA-Z0-9 ]+$/', $wish)) {
            return true;
        }
    }
    return false;
}

// schreibt die Lieferangaben
function writeDeliveryForm($wishes){
    echo "<h2>Lieferangaben</h2>";
    startForm("post", "index.php");
    writeInputField("Name", "name");
    writeInputField("Adresse", "address");
    writeInputField("PLZ", "postalcode");
    writeInputField("Stadt", "city");
    foreach ($wishes as $index => $wish) {
        echo "<input type=\"hidden\" name=\"wish$index\" value=\"" . htmlspecialchars($wish) . "\">";
    }
    closeFormAndFooter();
}

// überprüft die eingegebenen Daten
function validateDelivery($data){

    return preg_match('/^[a-zA-Z0-9 ]+$/', $data['name']) &&
           preg_match('/^[a-zA-Z0-9 ]+$/', $data['address']) &&
           preg_match('/^\d{5}$/', $data['postalcode']) &&
           preg_match('/^[a-zA-Z ]+$/', $data['city']);
}

//
// Beginn des Hauptprogramms
//
writeHeaderAndHeadline();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['firstwish']) || isset($_POST['secondwish']) || isset($_POST['thirdwish'])) {
        // Erste Seite - Wünscheingabe
        $wishes = [$_POST['firstwish'], $_POST['secondwish'], $_POST['thirdwish']];
        if (validateWishes($wishes)) {
            // Wünsche sind gültig, Lieferform anzeigen
            writeDeliveryForm($wishes);
        } else {
            echo "<p class=\"error\">Bitte geben Sie mindestens einen gültigen Wunsch ohne Sonderzeichen ein.</p>";
            startForm("post", "index.php");
            writeInputField("1. Wunsch", "firstwish", $_POST['firstwish']);
            writeInputField("2. Wunsch", "secondwish", $_POST['secondwish']);
            writeInputField("3. Wunsch", "thirdwish", $_POST['thirdwish']);
            closeFormAndFooter();
        }
    } elseif (isset($_POST['name'], $_POST['address'], $_POST['postalcode'], $_POST['city'])) {
        // Zweite Seite - Lieferangaben
        $wishes = [$_POST['wish0'], $_POST['wish1'], $_POST['wish2']];
        if (validateDelivery($_POST)) {
            echo "<h2>Zusammenfassung</h2>";
            echo "<h3>Lieferdaten:</h3>";
            echo "<p>Name: " . htmlspecialchars($_POST['name']) . "</p>";
            echo "<p>Adresse: " . htmlspecialchars($_POST['address']) . "</p>";
            echo "<p>PLZ: " . htmlspecialchars($_POST['postalcode']) . "</p>";
            echo "<p>Stadt: " . htmlspecialchars($_POST['city']) . "</p>";
            echo "<h3>Ihre Wünsche:</h3>";
            foreach ($wishes as $index => $wish) {
                if (!empty($wish)) {
                    echo "<p>Wunsch " . ($index + 1) . ": " . htmlspecialchars($wish) . "</p>";
                }
            }
        } else {
            echo "<p class=\"error\">Bitte füllen Sie alle Felder korrekt aus. Keine Sonderzeichen und PLZ muss 5-stellig sein.</p>";
            writeDeliveryForm($wishes);
        }
    }
} else {
    // Initiales Laden der Seite - Wünscheingabe
    startForm("post", "index.php");
    echo "<div>";
    writeInputField("1. Wunsch", "firstwish");
    writeInputField("2. Wunsch", "secondwish");
    writeInputField("3. Wunsch", "thirdwish");
    closeFormAndFooter();
}
?>