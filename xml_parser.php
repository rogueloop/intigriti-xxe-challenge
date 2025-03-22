<?php
libxml_disable_entity_loader(true); // Critical security setting

function sanitize($in) {
    // Remove DOCTYPE declarations
    $in = preg_replace('/<!DOCTYPE[^>[]*(\[[^]]*\])?>/i', '', $in);
    
    $bad = ["<IENTITY", "<!ENTITY", " SYSTEM ", " PUBLIC "];
    foreach ($bad as $kw) {
        if (stripos($in, $kw) !== false) {
            throw new Exception("Forbidden XML structure detected!");
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
            'desc' => htmlspecialchars($e->getElementsByTagName('desc')->item(0)->nodeValue)
        ];
    }
    return $data;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $xml = $_POST['data'] ?? '';
        if (!empty($xml)) {
            $result = parse(sanitize($xml));
            echo "<h2>Parsed data:</h2><pre>";
            print_r($result);
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo "Error: " . $e->getMessage();
    }
}
?>