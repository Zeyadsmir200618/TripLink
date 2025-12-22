<?php
// ===========================
// Validation Strategy Interface
// ===========================
interface ValidationStrategy {
    public function validate(string $input, ...$params): bool;
}

// ===========================
// Concrete Strategies
// ===========================
class RequiredValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        return !empty($input);
    }
}

class EmailValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }
}

class MinLengthValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $length = $params[0] ?? 0;
        return strlen($input) >= $length;
    }
}

class MaxLengthValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $length = $params[0] ?? PHP_INT_MAX;
        return strlen($input) <= $length;
    }
}

class MatchValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $other = $params[0] ?? '';
        return $input === $other;
    }
}

class NumericValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        return is_numeric($input);
    }
}

class RegexValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $pattern = $params[0] ?? '';
        return preg_match($pattern, $input) === 1;
    }
}

// ===========================
// Validator Context
// ===========================
class ValidatorContext {
    private ValidationStrategy $strategy;

    public function __construct(ValidationStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(ValidationStrategy $strategy): void {
        $this->strategy = $strategy;
    }

    public function validate(string $input, ...$params): bool {
        return $this->strategy->validate($input, ...$params);
    }

    // ----------------------
    // Static helper functions
    // ----------------------
    public static function sanitize(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public static function email(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function required($value): bool {
        return !empty($value);
    }

    public static function minLength(string $input, int $length): bool {
        return strlen($input) >= $length;
    }

    public static function match(string $input1, string $input2): bool {
        return $input1 === $input2;
    }
}

// ============================================================================
//  AUTOMATED UNIT TESTS (REQUIREMENT: MINIMUM 2 TESTS)
// ============================================================================
// This code only runs if you access this file directly in the browser.
// It will NOT run when included in other files (like login.php).
// ============================================================================

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    
    echo "<style>body{font-family:sans-serif; padding:20px;} .pass{color:green; font-weight:bold;} .fail{color:red; font-weight:bold;}</style>";
    echo "<h1>🚀 Automated Unit Tests</h1>";

    class ValidatorTests {
        
        // Helper to print results
        public static function assert($testName, $condition) {
            if ($condition) {
                echo "<div class='pass'>✅ PASS: $testName</div>";
            } else {
                echo "<div class='fail'>❌ FAIL: $testName</div>";
            }
        }

        // Test 1: Validation Logic
        public static function runValidationTest() {
            echo "<h3>🧪 Test Suite 1: Validation Logic</h3>";
            
            $ctx = new ValidatorContext(new RequiredValidation());
            self::assert("Required: 'Hello' should pass", $ctx->validate("Hello") === true);
            self::assert("Required: Empty string should fail", $ctx->validate("") === false);

            $ctx->setStrategy(new EmailValidation());
            self::assert("Email: 'test@example.com' should pass", $ctx->validate("test@example.com") === true);
            self::assert("Email: 'invalid-email' should fail", $ctx->validate("invalid-email") === false);
        }

        // Test 2: Sanitization (Security)
        public static function runSanitizationTest() {
            echo "<h3>🛡️ Test Suite 2: Security Sanitization</h3>";
            
            $dirty = "<b>Hello</b>";
            $clean = ValidatorContext::sanitize($dirty);
            self::assert("Sanitize: Remove HTML tags", $clean === "Hello");

            $dirty = "   user   ";
            $clean = ValidatorContext::sanitize($dirty);
            self::assert("Sanitize: Trim whitespace", $clean === "user");
        }
    }

    // Execute Tests
    ValidatorTests::runValidationTest();
    ValidatorTests::runSanitizationTest();
    
    echo "<br><hr><b>🏁 All automated tests completed.</b>";
}
?>