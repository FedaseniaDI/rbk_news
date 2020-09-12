<?php
namespace Application\Model\Base;

class Model
{
    protected array $fillableFields;

    public function __construct(array $data = null)
    {
        if($data) $this->exchangeArray($data);
    }

    public function exchangeArray(array $data): void
    {
        foreach($this->fillableFields as $field) {
            if(!empty($data[$field])) $this->{$field} = $data[$field];
        }
    }

    public function getArrayData(): array
    {
        $data = [];
        foreach($this->fillableFields as $field) {
            if(isset($this->{$field})) $data[$field] = $this->{$field};
        }
        return $data;
    }
}