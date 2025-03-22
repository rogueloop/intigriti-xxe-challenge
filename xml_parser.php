<?php
function sanitize($in) {
    $bad = ["<!ENTITY>", "<!DOCTYPE>", " SYSTEM ", " PUBLIC "];
    foreach ($bad as $kw) {
        if (stripos($in, $kw) !== false) {
            echo "[DEBUG] Sanitization blocked due to keyword: '$kw'<br>";
            throw new Exception("Forbidden keyword!");
        }
    }
    echo "[DEBUG] Sanitization passed<br>";
    return $in;
}

function parse($xml) {
    echo "[DEBUG] Starting XML parsing<br>";
    echo "[DEBUG] XML input:<br><pre>" . htmlspecialchars($xml) . "</pre>";

    libxml_use_internal_errors(true); // Enable error capturing
    $dom = new DOMDocument();
    
    // Load XML with debug output
    if (!$dom->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD)) {
        echo "[DEBUG] XML parsing errors:<br>";
        foreach (libxml_get_errors() as $error) {
            echo "Line {$error->line}: {$error->message}<br>";
        }
        libxml_clear_errors();
        throw new Exception("XML parsing failed");
    }
    echo "[DEBUG] XML parsed successfully<br>";

    // Extract data
    $data = [];
    foreach ($dom->getElementsByTagName('data') as $e) {
        $title = $e->getElementsByTagName('title')->item(0)->nodeValue;
        $desc = $e->getElementsByTagName('desc')->item(0)->nodeValue;
        
        echo "[DEBUG] Found data element:<br>";
        echo "- Title: " . htmlspecialchars($title) . "<br>";
        echo "- Desc: " . htmlspecialchars($desc) . "<br>";
        
        $data[] = [
            'title' => htmlspecialchars($title),
            'desc'  => htmlspecialchars($desc)
        ];
    }
    return $data;
}

// Main execution
echo "<h2>XXE Test Debugger</h2>";
$xml = $_POST['data'] ?? '';

if (!empty($xml)) {
    try {
        echo "[DEBUG] Raw input received:<br><pre>" . htmlspecialchars($xml) . "</pre>";
        $clean_xml = sanitize($xml);
        $result = parse($clean_xml);
        echo "<h2>Parsed data:</h2><pre>";
        print_r($result);
        echo "</pre>";
    } catch (Exception $e) {
        echo "<b style='color:red'>Error: " . $e->getMessage() . "</b><br>";
    }
} else {
    echo "No data submitted - send XML via POST parameter 'data'";
}
?>