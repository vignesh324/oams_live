<?php
namespace App\Validation\Rules;

use CodeIgniter\Validation\Rules\RuleInterface;
use CodeIgniter\Validation\ValidationException;

class IsUniqueWithStatus implements RuleInterface
{
    protected $table;
    protected $field;
    protected $statusField;
    protected $statusValue;

    public function __construct(string $table, string $field, string $statusField, string $statusValue)
    {
        $this->table = $table;
        $this->field = $field;
        $this->statusField = $statusField;
        $this->statusValue = $statusValue;
    }

    public function check($value): bool
    {
        $db = db_connect();

        $builder = $db->table($this->table);
        $builder->where($this->field, $value);
        $builder->where($this->statusField, $this->statusValue);
        
        return $builder->countAllResults() === 0;
    }

    public function setParameters(array $params): RuleInterface
    {
        $this->table = $params[0] ?? $this->table;
        $this->field = $params[1] ?? $this->field;
        $this->statusField = $params[2] ?? $this->statusField;
        $this->statusValue = $params[3] ?? $this->statusValue;

        return $this;
    }

    public function getErrorMessage(): string
    {
        return 'The {field} must be unique.';
    }
}