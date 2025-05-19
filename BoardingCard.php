<?php

require_once 'TransportType.php';

readonly class BoardingCard {
    public function __construct(
        public string $from,
        public string $to,
        public TransportType $type,
        public string $name,
        public ?string $seat = null,
        public ?string $gate = null,
        public ?string $baggage = null
    ) {}

    public function getDescription(): string {
        return match($this->type) {
            TransportType::Train =>
                "Take {$this->name} from {$this->from} to {$this->to}. Sit in seat {$this->seat}.",
            TransportType::Bus =>
                "Take the {$this->name} from {$this->from} to {$this->to}. " . ($this->seat ? "Sit in seat {$this->seat}." : "No seat assignment."),
            TransportType::Flight =>
                "From {$this->from}, take {$this->name} to {$this->to}. Gate {$this->gate}, seat {$this->seat}. " . ($this->baggage ?? "")
        };
    }
}
