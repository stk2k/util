<?php
declare(strict_types=1);

namespace stk2k\util\handler;

use stk2k\util\MacroHandlerInterface;

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
    public function process(string $keyword) : string
    {
        foreach($this->handlers as $handler){
            if (is_callable($handler)){
                $keyword = $handler($keyword);
            }
            else if ($handler instanceof MacroHandlerInterface){
                $keyword = $handler->process($keyword);
            }
        }
        return $keyword;
    }
}