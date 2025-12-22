<?php
// ===========================
// STRATEGY INTERFACE
// ===========================
// This defines a "contract". Any class that implements this interface 
// MUST have a function called 'validate'.
interface ValidationStrategy {
    // ...$params is a "Variadic" argument. It allows us to pass 
    // extra data (like a minimum length number) if needed.
    public function validate(string $input, ...$params): bool;
}

// ===========================
// CONCRETE STRATEGIES
// ===========================
// Strategy 1: Check if input is empty
class RequiredValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        return !empty($input); // Returns true if text exists
    }
}

// Strategy 2: Check if input is a valid email
class EmailValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        // filter_var is a built-in PHP security function
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }
}

// Strategy 3: Check minimum length (requires a parameter)
class MinLengthValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        // Get the limit (e.g., 6 characters) from the params array
        $length = $params[0] ?? 0;
        return strlen($input) >= $length;
    }
}

// Strategy 4: Check maximum length
class MaxLengthValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $length = $params[0] ?? PHP_INT_MAX;
        return strlen($input) <= $length;
    }
}

// Strategy 5: Check if two fields match (e.g., Password & Confirm Password)
class MatchValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $other = $params[0] ?? ''; // The second password to compare against
        return $input === $other;
    }
}

// Strategy 6: Check if input is a number
class NumericValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        return is_numeric($input);
    }
}

// Strategy 7: Check custom Regex pattern (Advanced)
class RegexValidation implements ValidationStrategy {
    public function validate(string $input, ...$params): bool {
        $pattern = $params[0] ?? '';
        return preg_match($pattern, $input) === 1;
    }
}

// ===========================
// VALIDATOR CONTEXT
// ===========================
// This is the main class you use in your controllers.
// It holds a "Strategy" and uses it to validate data.
class ValidatorContext {
    
    // This variable holds the current rule (e.g., EmailValidation)
    private ValidationStrategy $strategy;

    // Constructor: Set the initial rule when creating the object
    public function __construct(ValidationStrategy $strategy) {
        $this->strategy = $strategy;
    }

    // Allow changing the rule on the fly (e.g., switch from "Required" to "Email")
    public function setStrategy(ValidationStrategy $strategy): void {
        $this->strategy = $strategy;
    }

    // The main function that runs the validation
    public function validate(string $input, ...$params): bool {
        return $this->strategy->validate($input, ...$params);
    }

    // ----------------------
    // STATIC HELPER FUNCTIONS
    // ----------------------
    // These allow you to use the Validator without creating an object ("new ValidatorContext")
    // This is useful for simple checks in your Controllers.
    
    public static function sanitize(string $input): string {
        // htmlspecialchars: Prevents script injection (XSS attacks)
        // strip_tags: Removes HTML tags like <b> or <script>
        // trim: Removes spaces from start/end
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
// This block ensures the tests ONLY run if you open "Validator.php" directly.
// They will NOT run when your Login/Register pages include this file.
// ============================================================================

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    
    // Add some CSS to make the test results look nice (Green for Pass, Red for Fail)
    echo "<style>body{font-family:sans-serif; padding:20px;} .pass{color:green; font-weight:bold;} .fail{color:red; font-weight:bold;}</style>";
    echo "<h1>🚀 Automated Unit Tests</h1>";

    class ValidatorTests {
        
        // Helper function to print "PASS" or "FAIL"
        public static function assert($testName, $condition) {
            if ($condition) {
                echo "<div class='pass'>✅ PASS: $testName</div>";
            } else {
                echo "<div class='fail'>❌ FAIL: $testName</div>";
            }
        }

        // Test Suite 1: Testing the Validation Logic
        public static function runValidationTest() {
            echo "<h3>🧪 Test Suite 1: Validation Logic</h3>";
            
            // Test 1: Check Required Strategy
            $ctx = new ValidatorContext(new RequiredValidation());
            self::assert("Required: 'Hello' should pass", $ctx->validate("Hello") === true);
            self::assert("Required: Empty string should fail", $ctx->validate("") === false);

            // Test 2: Check Email Strategy
            // We switch the strategy on the existing object
            $ctx->setStrategy(new EmailValidation());
            self::assert("Email: 'test@example.com' should pass", $ctx->validate("test@example.com") === true);
            self::assert("Email: 'invalid-email' should fail", $ctx->validate("invalid-email") === false);
        }

        // Test Suite 2: Testing Security (Sanitization)
        public static function runSanitizationTest() {
            echo "<h3>🛡️ Test Suite 2: Security Sanitization</h3>";
            
            // Test removing HTML tags
            $dirty = "<b>Hello</b>";
            $clean = ValidatorContext::sanitize($dirty);
            self::assert("Sanitize: Remove HTML tags", $clean === "Hello");

            // Test trimming spaces
            $dirty = "   user   ";
            $clean = ValidatorContext::sanitize($dirty);
            self::assert("Sanitize: Trim whitespace", $clean === "user");
        }
    }

    // Run the tests immediately
    ValidatorTests::runValidationTest();
    ValidatorTests::runSanitizationTest();
    
    echo "<br><hr><b>🏁 All automated tests completed.</b>";
}
?>