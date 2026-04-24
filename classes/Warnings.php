<?php

namespace App;

class Warnings
{
    public static function PrintMessage($text, $type)
    {
        $type = strtolower((string) $type);

        if ($type === 'danger') {
            echo "<div style='margin-top:5px;' class='alert alert-danger' role='alert'>" . $text . "</div>";
        } elseif ($type === 'success') {
            echo "<div style='margin-top:5px;' class='alert alert-success' role='alert'>" . $text . "</div>";
        } else {
            echo "<div style='margin-top:5px;' class='alert alert-primary' role='alert'>" . $text . "</div>";
        }
    }

    public function SuccessMessage()
    {
        if (isset($_GET["doneSignUp"])) {
            \App\Warnings::PrintMessage("Account created Successfully", 'Success');
        }
 
    }
}
