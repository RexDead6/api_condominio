<?php 
trait JsonSerializeTrait {
    function jsonSerialize() {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties(\ReflectionProperty::IS_STATIC | \ReflectionProperty::IS_PROTECTED);

        $propsIterator = function() use ($props) {
            foreach ($props as $prop) {
                yield $prop->getName() => $this->{$prop->getName()};
            }
        };

        return iterator_to_array($propsIterator());
    }
}
?>