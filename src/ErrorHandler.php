<?php

namespace Bone;

use Closure;

class ErrorHandler
{
    public static function getShutdownHandler(): Closure
    {
        return function (): void {
            $message = '';
            $where = '';
            $trace = '';
            $error = error_get_last();

            if (!$error) {
                return;
            }

            if (\in_array($error['type'], [E_ERROR, E_RECOVERABLE_ERROR, E_CORE_ERROR, E_USER_ERROR, E_COMPILE_ERROR])) {
                $errorType = $error['type'];
                $message = $error["message"];

                if ($errorType === E_ERROR) {
                    $split = preg_split("/Stack\strace\:\n/", $error["message"]);
                    $split2 = preg_split("/\sin\s/", $split[0]);
                    $message = $split2[0];
                    $where = $split2[1];
                    $lines = explode("\n", $split[1]);
                    $trace = '';
                    $row = 'odd';

                    foreach ($lines as $line) {
                        $split = preg_split("/\s+/", $line, 2);
                        $traceNo = $split[0];
                        $lineInfo = $split[1];

                        if (isset($_SERVER['SERVER_NAME'])) {
                            $trace .= "<tr class='$row'><td>$traceNo</td><td>$lineInfo</td></tr>";
                        } else {
                            $trace .= "$traceNo $lineInfo\n";
                        }

                        $row = $row === 'odd' ? 'even' : 'odd';
                    }
                }

                if (\getenv('APPLICATION_ENV') === 'production') {
                    $message = 'There was an error';
                    $where = 'We apologise for the inconvenience.';
                    $trace = '';
                }

                if (isset($_SERVER['SERVER_NAME'])) {
                    $content = "
<html>
<head>
    <style type='text/css'>
        #error { 
            background: rgb(0,212,255);
            background: radial-gradient(circle, rgba(0,212,255,1) 0%, rgba(4,23,62,1) 78%); 
            display: flex;
            justify-content: center;
            font-family: Helvetica
        }
        h1, h3  {
        font-weight: initial;
        }
        #details {
            width: 75%;
            background-color: #eee;
            padding: 20px;
            margin: 20px;
        }
        table {
            border: 1px solid #999;
        }
        tr {
            
        }
        tr.odd {
            background-color: #ddd;
        }
        tr.even {
        
        }
        td {
            padding: 10px;
        }
    </style>
</head>
<body id='error'>
<div id='details'>
        <h1>$message</h1>
        <h3>$where</h3>
        <table>
            $trace
        </table>
    </div>
</body>
    
</html>";
                    echo $content;

                    return;
                }

                echo "\n💀 Error\n\n";
                echo $message . "\n\n";
                echo $where . "\n\n";
                echo $trace . "\n\n";
            }
        };
    }
}

function error_get_last()
{
    if (getenv('TEST_ERROR') === 'true') {
        return [
            'type' => E_ERROR,
            'message' => "Error in /some/file.php:123\nStack trace:\n#0 somefile.php:123\n#1 someotherfile.php:12",
        ];
    }

    return \error_get_last();
}
