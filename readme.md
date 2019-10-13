## Digi-X “Lightning” Assessment for Developers

### How To Setup

- Install required package. The required package only used for testing using PHPUnit. To run the actual code you can straightaway
run the code. Only required when you want to run the test.
```markdown
composer install
```

- Execute the code. The main file is `Store.php`. You should execute this file to run the code.
```markdown
php Store.php
```

### Output
```markdown
SKUs Scanned: atv, atv, atv, vga
Total expected: $249.00
Total Amount: $249

SKUs Scanned: atv, atv, atv, vga
Total expected: $2718.95
Total Amount: $2718.95

SKUs Scanned: atv, atv, atv, vga
Total expected: $1949.98
Total Amount: $1949.98
```

### Run the test
```markdown
 vendor/bin/phpunit Tests
```