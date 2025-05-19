<?php

class JourneySorter {

    /** @param BoardingCard[] $cards */
    public function sort(array $cards): array {
        if (empty($cards)) {
            throw new InvalidArgumentException("No boarding cards provided.");
        }

        $fromMap = [];
        $toMap = [];

        foreach ($cards as $card) {
            $fromMap[$card->from] = $card;
            $toMap[$card->to] = true;
        }

        $start = null;
        foreach ($cards as $card) {
            if (!isset($toMap[$card->from])) {
                $start = $card->from;
                break;
            }
        }

        if (!$start) {
            throw new RuntimeException("Unable to find journey starting point.");
        }

        $sorted = [];
        $current = $start;
        $max = count($cards);
        $i = 0;

        while (isset($fromMap[$current])) {
            $card = $fromMap[$current];
            $sorted[] = $card;
            $current = $card->to;
            unset($fromMap[$card->from]);

            if (++$i > $max) {
                throw new RuntimeException("Loop detected or incomplete journey.");
            }
        }

        return $sorted;
    }

    /** @param BoardingCard[] $cards */
    public function describe(array $cards): array {
        return array_merge(
            array_map(fn($card) => $card->getDescription(), $cards),
            ["You have arrived at your destination."]
        );
    }
}
