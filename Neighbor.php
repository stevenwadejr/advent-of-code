<?php

enum Neighbor: string
{
    case N = 'N';
    case NE = 'NE';
    case E = 'E';
    case SE = 'SE';
    case S = 'S';
    case SW = 'SW';
    case W = 'W';
    case NW = 'NW';

    public function position(): Position
    {
        return match($this) {
            self::N => new Position(0, -1),
            self::NE => new Position(1, -1),
            self::E => new Position(1, 0),
            self::SE => new Position(1, 1),
            self::S => new Position(0, 1),
            self::SW => new Position(-1, 1),
            self::W => new Position(-1, 0),
            self::NW => new Position(-1, -1),
        };
    }
}