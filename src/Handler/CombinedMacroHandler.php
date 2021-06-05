<?php
declare(strict_types=1);

namespace stk2k\Util\Handler;

use stk2k\Util\MacroHandlerInterface;

final class CombinedMacroHandler implements MacroHandlerInterface
{
    /** @var array */
    private $handlers;

    /**
     * UserMacroHandler constructor.
     *
     * @param array $handlers
     */
    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * {@inheritDoc}
     */
    public function process(string $keyword)
    {
        foreach($this->handlers as $handler){
            if (is_callable($handler)){
                $res = $handler($keyword);
                if ($res !== false){
                    return $res;
                }
            }
            else if ($handler instanceof MacroHandlerInterface){
                $res = $handler->process($keyword);
                if ($res !== false){
                    return $res;
                }
            }
        }
        return false;
    }
}