<?php
declare(strict_types=1);

class Dice {
    private array $faces;

    public function __construct(array $faces) {
        if (count($faces) !== 6) {
            throw new InvalidArgumentException("Each die needs 6 numbers.");
        }
        $this->faces = $faces;
    }

    public function faceCount(): int {
        return count($this->faces);
    }

    public function getValue(int $i): int {
        return $this->faces[$i];
    }

    public function __toString(): string {
        return implode(',', $this->faces);
    }
}
