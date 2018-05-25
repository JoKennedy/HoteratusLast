<?php
    class PHPFatalError {

    public function setHandler() {
            register_shutdown_function('handleShutdown');
        }

    }

    function handleShutdown() {
        if (($error = error_get_last())) {
            ob_start();
                echo "<pre>";
            print_r($error);
                echo "</pre>";
            $message = ob_get_clean();
            mail("datahernandez@gmail.com","Error From Hoteratus ".$_SERVER['HTTP_HOST']."",$message); //sendEmail($message);
            ob_start();
            echo '{"status":"error","message":"Internal application error!"}';
            ob_flush();
            exit();
        }
    }
?>