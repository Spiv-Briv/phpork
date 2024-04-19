<?php declare(strict_types=1);

namespace Framework\Terminal;

class Terminal {

    static string $previous = "";
    const DEFAULT = "\033[0m";
    const DEFAULT_FOREGROUND = "\033[39m";
    const DEFAULT_BACKGROUND = "\033[49m";

    const BLACK = "\033[30m";
    const RED = "\033[31m";
    const GREEN = "\033[32m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const MAGENTA = "\033[35m";
    const CYAN = "\033[36m";
    const WHITE = "\033[37m";
    
    const BRIGHT_BLACK = "\033[90m";
    const BRIGHT_RED = "\033[91m";
    const BRIGHT_GREEN = "\033[92m";
    const BRIGHT_YELLOW = "\033[93m";
    const BRIGHT_BLUE = "\033[94m";
    const BRIGHT_MAGENTA = "\033[95m";
    const BRIGHT_CYAN = "\033[96m";
    const BRIGHT_WHITE = "\033[97m";

    const BACKGROUND_BLACK = "\033[40m";
    const BACKGROUND_RED = "\033[41m";
    const BACKGROUND_GREEN = "\033[42m";
    const BACKGROUND_YELLOW = "\033[43m";
    const BACKGROUND_BLUE = "\033[44m";
    const BACKGROUND_MAGENTA = "\033[45m";
    const BACKGROUND_CYAN = "\033[46m";
    const BACKGROUND_WHITE = "\033[47m";
    
    const BACKGROUND_BRIGHT_BLACK = "\033[100m";
    const BACKGROUND_BRIGHT_RED = "\033[101m";
    const BACKGROUND_BRIGHT_GREEN = "\033[102m";
    const BACKGROUND_BRIGHT_YELLOW = "\033[103m";
    const BACKGROUND_BRIGHT_BLUE = "\033[104m";
    const BACKGROUND_BRIGHT_MAGENTA = "\033[105m";
    const BACKGROUND_BRIGHT_CYAN = "\033[106m";
    const BACKGROUND_BRIGHT_WHITE = "\033[107m";

    const SUCCESS = self::GREEN;
    const WARNING = self::YELLOW;
    const ERROR = self::RED;

    static function print(string|int $message, string $foreground = self::DEFAULT_FOREGROUND, string $background = self::DEFAULT_BACKGROUND, string $escapeColor = self::DEFAULT): string
    {
        if(SCRIPT_ORIGIN!="CLI") {
            return "";
        }
        return $foreground.$background.$message.$escapeColor;
    }

    static function printl(string|int $message, string $foreground = self::DEFAULT_FOREGROUND, string $background = self::DEFAULT_BACKGROUND, string $escapeColor = self::DEFAULT): string
    {
        if(SCRIPT_ORIGIN!="CLI") {
            return "";
        }
        return $foreground.$background.$message.$escapeColor."\n";
    }
    static function println(string|int $message, string $foreground = self::DEFAULT_FOREGROUND, string $background = self::DEFAULT_BACKGROUND, string $escapeColor = self::DEFAULT): string
    {
        if(SCRIPT_ORIGIN!="CLI") {
            return "";
        }
        return "\n".$foreground.$background.$message.$escapeColor."\n";
    }

    static function variable(string|int $message, string $previous): string
    {
        return self::print($message, self::WHITE, self::DEFAULT_BACKGROUND, $previous);
    }

    static function error(string|int $message): string
    {
        return self::printl($message, Terminal::RED);
    }

    static function warning(string|int $message): string
    {
        return self::printl($message, Terminal::YELLOW);
    }

    static function success(string|int $message): string
    {
        return self::printl($message, Terminal::GREEN);
    }
}