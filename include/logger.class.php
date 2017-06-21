<?php

include_once 'exceptions.logger.class.php';

/**
 * Logger class.
 * Usefull to log notices, warnings, errors or fatal errors into a logfile.
 * @author gehaxelt
 * @version 1.1
 */
class Logger
{
    const NOTICE          ='[NOTICE]';
    const WARNING         ='[!WARNING!]';
    const ERROR           ='[!!ERROR!!]';
    const FATAL           ='[!!!FATAL!!!]';
    private $logfilehandle=null;

    /**
     * Contructor of Logger.
     * Opens the new logfile.
     * @param string $logfile is the path to a logfile
     */
    public function __construct($logfile)
    {
        if ($this->logfilehandle == null) {
            $this->openLogFile($logfile);
        }
    }

    /**
     * Destructor of Logger.
     */
    public function __destruct()
    {
        $this->closeLogFile();
    }

    /**
     * Logs the message into the logfile.
     * @param  string                      $message     message to write into the logfile
     * @param  int                         $messageType (optional) urgency of the messagee. Possible constants are: notice, warning, error, fatal. Default value: warning
     * @throws LogFileNotOpenException
     * @throws NotAStringException
     * @throws NotAIntegerException
     * @throws InvalidMessageTypeException
     */
    public function log($message, $messageType=self::WARNING)
    {
        if ($this->logfilehandle == null) {
            throw new LogFileNotOpenException('Logfile is not opened.');
        }

        if (!is_string($message)) {
            throw new NotAStringException('$message is not a string');
        }

        if ($messageType != self::NOTICE && $messageType != self::WARNING && $messageType != self::ERROR && $messageType != self::FATAL) {
            throw new InvalidMessageTypeException('Wrong $messagetype given');
        }

        $this->writeToLogFile('[' . $this->getTime() . ']' . $messageType . ' - ' . $message);
    }

    /**
     * Closes the current logfile.
     */
    public function closeLogFile()
    {
        if ($this->logfilehandle != null) {
            fclose($this->logfilehandle);
            $this->logfilehandle=null;
        }
    }

    /**
     * Opens a given logfile and closes the old one before, if another logfile was opened before.
     * @param  string                    $logfile is a path to a logfile
     * @throws LogFileOpenErrorException
     */
    public function openLogFile($logfile)
    {
        $this->closeLogFile(); //close old logfile if opened;

        $this->logfilehandle=@fopen($logfile, 'a');

        if (!$this->logfilehandle) {
            throw new LogFileOpenErrorException('Could not open Logfile in append-mode');
        }
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);.
     * @param string $message
     */
    public function notice($message)
    {
        $this->log($message, self::NOTICE);
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);.
     * @param string $message
     */
    public function warn($message)
    {
        $this->log($message, self::WARNING);
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);.
     * @param string $message
     */
    public function error($message)
    {
        $this->log($message, self::ERROR);
    }

    /**
     * Convenience function to wrap logger->log($message,$messagetype);.
     * @param string $message
     */
    public function fatal($message)
    {
        $this->log($message, self::FATAL);
    }

    /**
     * Writes content to the logfile.
     * @param string $message
     */
    private function writeToLogFile($message)
    {
        flock($this->logfilehandle, LOCK_EX);
        fwrite($this->logfilehandle, $message . "\n");
        flock($this->logfilehandle, LOCK_UN);
    }

    /**
     * Returns the current timestamp in dd.mm.YYYY - HH:MM:SS format.
     * @return string with the current date
     */
    private function getTime()
    {
        return date('d.m.Y - H:i:s');
    }
}
