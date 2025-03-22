<?php
function sanitize($in) {
    $bad = ["<!ENTITY>", "<!DOCTYPE>", "SYSTEM", "PUBLIC"];
    foreach ($bad as $kw) {
        if (stripos($in, $kw) !== false) {
            throw new Exception("Forbidden keyword!");
        }
    }
    return $in;
}

function parse($xml) {
    $dom = new DOMDocument();
    $dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);

    $data = [];
    foreach ($dom->getElementsByTagName('data') as $e) {
        $data[] = [
            'title' => htmlspecialchars($e->getElementsByTagName('title')->item(0)->nodeValue),
            'desc'  => htmlspecialchars($e->getElementsByTagName('desc')->item(0)->nodeValue)
        ];
    }
    return $data;
}

$xml = $_POST['data'] ?? '';
if (!empty($xml)) {
    $result = parse(sanitize($xml));
    echo "<h2>Parsed data:</h2><pre>";
    print_r($result);
}
?>