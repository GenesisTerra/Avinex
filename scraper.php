<?php
$targetUrl = "http://localhost/260/index.php";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $targetUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$htmlContent = curl_exec($ch);

if(curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    curl_close($ch);
    exit;
}

curl_close($ch);

$dom = new DOMDocument();

libxml_use_internal_errors(true);

$dom->loadHTML($htmlContent);

libxml_clear_errors();

$xpath = new DOMXPath($dom);

$elements = $xpath->query("//h2");

foreach ($elements as $element) {
    echo $element->textContent . PHP_EOL;
}

$links = $xpath->query("//a");
foreach ($links as $link) {
    $href = $link->getAttribute('href');
    $linkText = $link->textContent;

    echo "Link text: $linkText | URL: $href" . PHP_EOL;
}
?>

<?php

$targetUrl = "http://localhost/260/public/login.php";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $targetUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    $errorMsg = curl_error($ch);
    echo "Error occurred: " . $errorMsg;
    curl_close($ch);
    exit;
}

echo $response;

curl_close($ch);
?>
