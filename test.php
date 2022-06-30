<?php

require __DIR__.'/vendor/autoload.php';

use TemKaa1337\SimpleHtmlParser\Input\StringInput;
use TemKaa1337\SimpleHtmlParser\Parser;

$testHtml = <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="30">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Page Title</title>
        <style>
            body {background-color: powderblue;}
            h1 {color: red;}
            p {color: blue;}
        </style>
        <link rel="stylesheet" href="mystyle.css">
    </head>
    <body>
        <h1>This is a Heading</h1>
        <p>This is a paragraph.</p>
    </body>
    <script src="defer.js">
    <script>
        var one = 'one';
    </script>
</html>
EOT;

$parser = new Parser(new StringInput(s: $testHtml));
$parser->parse();