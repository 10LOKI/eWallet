<?php
// Script to remove PHP comments and HTML comments from files under public/
$dir = __DIR__ . '/../public';
$files = glob($dir . '/*.php');
foreach ($files as $file) {
    $code = file_get_contents($file);
    $tokens = token_get_all($code);
    $out = '';
    foreach ($tokens as $token) {
        if (is_array($token)) {
            $id = $token[0];
            $text = $token[1];
            if ($id === T_COMMENT || $id === T_DOC_COMMENT) {
                // skip comments
                continue;
            }
            if ($id === T_INLINE_HTML) {
                // remove HTML comments like <!-- ... --> and JS comments inside <script>...</script>
                $text = preg_replace('/<!--([\\s\\S]*?)-->/', '', $text);
                // Remove JS comments inside script tags
                $text = preg_replace_callback(
                    '/(<script[^>]*>)([\\s\\S]*?)(<\\/script>)/i',
                    function ($m) {
                        $inner = $m[2];
                        // remove //... and /* ... */
                        $inner = preg_replace('/\\/\\/[^\n\r]*/', '', $inner);
                        $inner = preg_replace('/\\/\\*[\\s\\S]*?\\*\\//', '', $inner);
                        return $m[1] . $inner . $m[3];
                    },
                    $text
                );
            }
            $out .= $text;
        } else {
            $out .= $token;
        }
    }
    // Normalize line endings to LF to avoid CRLF churn
    $out = str_replace("\r\n", "\n", $out);
    file_put_contents($file, $out);
    echo "Processed: $file\n";
}
