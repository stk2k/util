<?php
declare(strict_types=1);

namespace stk2k\Util;

use stk2k\Util\Exception\MacroHandlerException;
use stk2k\Util\Handler\SystemMacroHandler;
use stk2k\Util\Handler\DateMacroHandler;
use stk2k\Util\Handler\ServerMacroHandler;
use stk2k\Util\Handler\EnvMacroHandler;
use stk2k\Util\Handler\CookieMacroHandler;
use stk2k\Util\Handler\SessionMacroHandler;
use stk2k\Util\Handler\GetMacroHandler;
use stk2k\Util\Handler\PostMacroHandler;
use stk2k\Util\Handler\RequestMacroHandler;
use stk2k\Util\Enum\EnumMacroHandler;

class MacroProcessor
{
    /**
     * @var callable[]
     */
    private $handlers;
    
    /**
     * MacroProcessor constructor.
     *
     * @param array $default_handlers
     */
    public function __construct(array $default_handlers = [])
    {
        foreach($default_handlers as $handler)
        {
            if (is_string($handler)){
                $this->addNamedHandler($handler);
            }
            else if (is_callable($handler)){
                $this->addHandler($handler);
            }
            else if (is_array($handler)){
                $this->addHandler(function ($keyword) use($handler){
                    return $handler[$keyword] ?? false;
                });
            }
        }
    }
    
    /**
     * Register macro handler
     *
     * @param callable|MacroHandlerInterface $handler
     *
     * @return MacroProcessor
     */
    public function addHandler($handler) : MacroProcessor
    {
        if (is_callable($handler)){
            $this->handlers[] = $handler;
        }
        else if ($handler instanceof MacroHandlerInterface){
            $this->handlers[] = array($handler, 'process');
        }
        else if (is_array($handler)){
            $this->handlers[] = function ($keyword) use($handler){
                return $handler[$keyword] ?? false;
            };
        }
        return $this;
    }
    
    /**
     * Register macro handler
     *
     * @param string $key
     *
     * @return MacroProcessor
     */
    public function addNamedHandler(string $key) : MacroProcessor
    {
        switch($key){
        case EnumMacroHandler::HANDLER_KEY_SYSTEM:
            self::addHandler(new SystemMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_DATE:
            self::addHandler(new DateMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_SERVER:
            self::addHandler(new ServerMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_ENV:
            self::addHandler(new EnvMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_COOKIE:
            self::addHandler(new CookieMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_SESSION:
            self::addHandler(new SessionMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_GET:
            self::addHandler(new GetMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_POST:
            self::addHandler(new PostMacroHandler());
            break;
        case EnumMacroHandler::HANDLER_KEY_REQUEST:
            self::addHandler(new RequestMacroHandler());
            break;
        }
        return $this;
    }
    
    /**
     * Clear macro handlers
     *
     * @return MacroProcessor
     */
    public function removeAllHandlers() : MacroProcessor
    {
        $this->handlers = null;
    
        return $this;
    }
    
    /**
     * Expand string by macro keyword
     *
     * @param string $str
     * @param callable $handler
     *
     * @return string
     *
     * @throws
     */
    public function process(string $str, callable $handler = null) : string
    {
        $keyword_list = self::findMacroKeywords($str);

        if (empty($keyword_list)){
            return $str;
        }

        $replace_map = [];
        foreach($keyword_list as $keyword)
        {
            $replace = false;

            if ($handler){
                $replace = ($handler)($keyword);
            }

            if ($replace === false){
                $replace = $this->callHandlers($keyword);
            }

            if ($replace === false){
                // no replacement found
                continue;
            }

            // macro handler must return string/int/float/NULL value
            if (is_object($replace)){
                throw new MacroHandlerException('Macro handlers returned object result at keyword: ' . $keyword);
            }
            if (is_array($replace)){
                throw new MacroHandlerException('Macro handlers returned array result at keyword: ' . $keyword);
            }
            if (is_resource($replace)){
                throw new MacroHandlerException('Macro handlers returned resource result at keyword: ' . $keyword);
            }

            $replace_map["%{$keyword}%"] = $replace;
        }

        return strtr($str, $replace_map);
    }

    /**
     * Find macro keyword
     *
     * @param string $string
     *
     * @return array
     */
    private static function findMacroKeywords(string $string)
    {
        if (preg_match_all('/%([0-9a-zA-Z].*?)%/',$string,$matches)) {
            if (is_array($matches)) {
                return $matches[1];
            }
        }
        return [];
    }
    
    /**
     * Callback all handlers
     *
     * @param string $keyword
     *
     * @return bool|string
     */
    private function callHandlers(string $keyword)
    {
        if ($this->handlers)
        {
            $stack = null;
            foreach($this->handlers as $handler){
                $replace = $handler($keyword);
                if ($replace !== false){
                    return $replace;
                }
            }
        }
        return false;
    }
}