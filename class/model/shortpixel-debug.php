<?php
// The data models.
namespace ShortPixel;


class DebugItem
{
    protected $time;
    protected $level;
    protected $message;
    protected $data = array();

    const LEVEL_ERROR = 1;
    const LEVEL_WARN = 2;
    const LEVEL_INFO = 3;
    const LEVEL_DEBUG = 4;

    public function __construct($message, $args)
    {
        $this->level = $args['level'];
        $this->data = $args['data'];

        $this->message = $message;
        $this->time = microtime(true);

        // Add message to data if it seems to be some debug variable.
        if (is_object($this->message) || is_array($this->message))
        {
          $this->data[] = $this->message;
          $this->message = __('[Data]');
        }

        if (is_array($this->data))
        {
          foreach($this->data as $index => $item)
          {
            if (is_object($item) || is_array($item))
            {
              $this->data[$index] = print_r($item, true);
            }
          }
        }
    }

    public function getForFormat()
    {
      switch($this->level)
      {
          case self::LEVEL_ERROR:
            $level = 'ERR';
            $color = "\033[31m";
          break;
          case self::LEVEL_WARN:
            $level = 'WRN';
            $color = "\033[33m";
          break;
          case self::LEVEL_INFO:
            $level = 'INF';
            $color = "\033[37m";
          break;
          case self::LEVEL_DEBUG:
            $level = 'DBG';
            $color = "\033[37m";
          break;

      }
      $color_end = "\033[0m";

      return array('time' => $this->time, 'level' => $level, 'message' => $this->message, 'data' => $this->data, 'color' => $color, 'color_end' => $color_end);

    }


}
